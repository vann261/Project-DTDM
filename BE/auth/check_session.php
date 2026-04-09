<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../functions.php';

startSession();

if (isLoggedIn()) {
    echo json_encode([
        'logged_in' => true,
        'id'        => $_SESSION['user_id'],
        'name'      => $_SESSION['user_name'],
        'role'      => $_SESSION['user_role'],
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['logged_in' => false]);
}