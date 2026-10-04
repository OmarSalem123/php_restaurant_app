<?php

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../src/controllers/CategoryController.php';
require __DIR__ . '/../src/controllers/ProcutController.php';

header('Access-Control-Allow-Headers: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
} 

$routes = [
    ['GET', '/public/api/categories', 'listCategories'],

    ['GET', '/public/api/products', 'listProcuts'],
    ['GET', '/public/api/products/inactive', 'listInactiveProducts'],
    ['POST', '/public/api/products', 'createProduct'],
    ['GET', '/public/api/products/(\d+)', 'getProductById'],
    ['PUT', '/public/api/products/(\d+)', 'updateProduct'],
    ['DELETE', '/public/api/products/(\d+)', 'deleteProduct'],
    ['PUT', '/public/api/products/(\d+)/restore', 'restoreProduct'],
    ['DELETE', '/public/api/products/(\d+)/force', 'forceDeleteProduct'],
];

$method = $_SERVER['REQUEST_METHOD'];
$path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

foreach($routes as [$routeMethod, $pattern, $handler]){
    if($routeMethod === $method && preg_match('#^' . $pattern . '$#', $path, $matches)){
        try{
            $handler(...array_slice($matches, 1));
        } catch (Throwable $e) {
            json(['error' => $e->getMessage() ? $e->getMessage() : 'Server Error'], 500);
        }
        exit;
    }
}

json(['error' => 'Not Found'], 404);

?>