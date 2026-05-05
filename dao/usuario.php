<?php

require_once __DIR__ ."../config/db.php";
function findOne($email){
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam("email", $email, PDO::PARAM_STR);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}