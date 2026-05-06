<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../dao/usuario.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: /oficina/usuarios");
    exit();
}

$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST["password"];
$role = $_POST["role"];
$password_confirm = $_POST["password_confirm"];

// 1. Validação de campos vazios
if (!$nome || !$email || !$password || !$password_confirm) {
    header("Location: /oficina/usuarios?error=" . urlencode("Preencha todos os campos corretamente."));
    exit();
}

// 2. Verificação de senha coincidente
if ($password !== $password_confirm) {
    header("Location: /oficina/usuarios?error=" . urlencode("As senhas não coincidem."));
    exit();
}

// 3. Verificação de tamanho mínimo de senha
if (strlen($password) < 6) {
    header("Location: /oficina/usuarios?error=" . urlencode("A senha deve ter pelo menos 6 caracteres."));
    exit();
}

// 4. Verificar se o e-mail já está cadastrado (usando seu findOne existente)
if (findOne($email)) {
    header("Location: /oficina/usuarios?error=" . urlencode("Este e-mail já está em uso."));
    exit();
}

// 5. Criptografia da senha (Segurança)
$hash = password_hash($password, PASSWORD_DEFAULT);

// 6. Chamada da função de inserção no DAO
if (saveUser($nome, $email, $hash, $role)) {
    $msg = "Cadastro realizado com sucesso! Faça login.";
    header("Location: /oficina/login.php?msg=" . urlencode($msg));
} else {
    header("Location: /?error=" . urlencode("Erro ao salvar no banco de dados."));
}
exit();