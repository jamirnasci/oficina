<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    session_unset();
    session_destroy();
    
    $msg = "Acesso negado. Por favor, faça login para acessar o sistema.";
    header("Location: /oficina/login.php?msg=" . urlencode($msg));
    exit();
}