<?php
require_once __DIR__ . "/../dao/cliente.php";
include __DIR__ . "/../auth/isAuth.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
    // Limpa o CPF para manter apenas números
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);

    if (!$id || !$nome || strlen($cpf) !== 11) {
        header("Location: edit_cliente.php?id=$id&error=" . urlencode("Dados inválidos ou CPF incompleto."));
        exit();
    }

    // Tenta atualizar no banco
    if (updateCliente($id, $nome, $cpf, $telefone, $email)) {
        header("Location: index.php?msg=" . urlencode("Cliente atualizado com sucesso!"));
    } else {
        header("Location: edit_cliente.php?id=$id&error=" . urlencode("Erro ao atualizar ou CPF já cadastrado para outro cliente."));
    }
    exit();
} else {
    header("Location: index.php");
    exit();
}