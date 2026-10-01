<?php 

const PRODUCT_SELECT = 'SELECT p.id, p.name, p.description, p.price, p.icon, p.tags, c.slug AS category FROM products p JOIN categories c ON c.id = p.category_id';

function Products($data) {
    return [
        'id' => $data['id'],
        'name'=> $data['name'],
        'description'=> $data['description'],
        'price'=> $data['price'],
        'icon'=> $data['icon'],
        'tags'=> $data['tags'],
        'category'=> $data['category'],
    ];
}

function listProcuts() {
    $sql = PRODUCT_SELECT . ' WHERE p.is_active = 1';

    $stmt = db()->query($sql)->fetchAll();
    json(array_map('Products', $stmt));
}

function createProduct(){
    $data = body();
    $name = $data['name'] ?? null;
    $description = $data['description'] ?? null;
    $price = $data['price'] ?? null;
    $icon = $data['icon'] ?? null;
    $tags = $data['tags'] ?? null;
    $category_id = $data['category_id'] ?? null;

    if(!$name){
        json(['error' => 'Missing required field: name'], 400);
    }

    if(!$price){
        json(['error' => 'Missing required field: price'], 400);
    }
    
    if(!$category_id){
        json(['error' => 'Missing required field: category_id'], 400);
    }


    db()->prepare('INSERT INTO products (name, description, price, icon, tags, category_id) VALUES (:name, :description, :price, :icon, :tags, :category_id)')
        ->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':icon' => $icon,
            ':tags' => $tags,
            ':category_id' => $category_id
        ]);
}