<?php

function listCategories() {
    $row = db()->query('SELECT * FROM categories')->fetchAll();
    foreach ($row as $category) {
        $category['id'] = (int) $category['id'];
    }
    json($row, 200);
}