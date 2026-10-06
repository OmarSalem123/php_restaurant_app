<?php

function issueToken($userId) {
    $token = bin2hex(random_bytes(32));
    db()->prepare('INSERT INTO api_token (user_id, token, expires_at) VALUES (:userId, :token, DATE_ADD(NOW(), INTERVAL 7 DAY))')
    ->execute(['userId' => $userId, 'token' => $token]);

    return $token;
}

function registerUser(){
    $newUser = body();
    $name = $newUser["name"];
    $email = $newUser["email"];
    $password = $newUser["password"];  

    $errors = [];
    if($name === "") {
        $errors['name'] = "Name is required";
    }
    if($email === "") {
        $errors['email'] = "Email is required";
    }
    if($password === "") {
        $errors['password'] = "Password is required";
    }

    if($errors) {
        json(['errors' => $errors], 422);
        return;
    }

    $stmt = db()->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    if($stmt->fetch()){
        json(['error' => 'Email already exists'], 409);
        return;
    }


    db()->prepare('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password_hash, :role)')
    ->execute([
        'email'=> $email,
        'name'=> $name,
        'password_hash'=> password_hash($password, PASSWORD_DEFAULT),
        'role' => 'customer',
    ]);

    $newUserId = db()->lastInsertId();
    $token = issueToken($newUserId);
    json(['user' => ['id' =>$newUserId, 'name' => $name, 'email' => $email, 'role' => 'customer'], 'token' => $token],
     201);
}

function loginUser(){
    $user = body();
    $email = $user["email"];
    $password = $user["password"];  

    $errors = [];
    if($email === "") {
        $errors['email'] = "Email is required";
    }
    if($password === "") {
        $errors['password'] = "Password is required";
    }

    if($errors) {
        json(['errors' => $errors], 422);
        return;
    }
    
    $stmt = db()->prepare('SELECT id, name, email, role, password FROM users WHERE email = :email');
    $stmt->execute([
        'email'=> $email
    ]);
    $user = $stmt->fetch();

    if(!password_verify($password, $user['password'] ?? '') || !$user){
        json(['error' => 'Invalid credentials'], 401);
        return;
    }

    json(['user'=> $user], 200);
}