<?php

require_once __DIR__ . "../config/db.php";
require_once __DIR__ . "../dao/usuario.php";

$email = $_POST["email"];
$password = $_POST["password"];

if (!$email || !$password) {
    $msg = "Forneça usuário e senha para fazer o login";
    header("Location: /login.php?msg=" . urlencode($msg));
    exit();
}

$user = findOne($email);
if (!$user) {
    $msg = "Usuário não encontrado";
    header("Location: /login.php?msg=" . urlencode($msg));
    exit();
}

if (password_verify($password, $user->password)) {
    session_start();
    $_SESSION["user_id"] = $user->id;
    $_SESSION["email"] = $user->email;
    $_SESSION["username"] = $user->nome;
    header("Location: /ordens");
} else {
    $msg = "Senha incorreta, tente novamente";
    header("Location: /login.php?msg=" . urlencode($msg));
    exit();
}


