<?php

function json($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
    exit;
}

function body() {
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : null;
}