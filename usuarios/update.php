<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../dao/usuario.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$id = $_POST['id'];
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$role = $_POST['role'];
$new_password = $_POST['new_password'];

if (!$id || !$nome || !$email) {
    header("Location: edit.php?id=$id&error=" . urlencode("Campos obrigatórios vazios."));
    exit();
}


if (update($id, $nome, $email, $role, $new_password)) {
    header("Location: /oficina/usuarios?msg=" . urlencode("Usuário atualizado com sucesso!"));
} else {
    header("Location: /oficina/usuarios.php?id=$id&error=" . urlencode("Erro ao atualizar."));
}