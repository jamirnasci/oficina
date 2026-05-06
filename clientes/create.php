<?php
// Inclui o arquivo de autenticação para garantir que só usuários logados cadastrem clientes
include __DIR__ . "/../auth/isAuth.php";
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../dao/cliente.php";

// Verifica se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Sanitização dos dados
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // 2. Tratamento específico do CPF (Remove pontos e traços)
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);

    // 3. Validações básicas
    if (empty($nome) || empty($cpf)) {
        header("Location: /oficina/clientes.php?error=" . urlencode("Nome e CPF são obrigatórios."));
        exit();
    }

    if (strlen($cpf) !== 11) {
        header("Location: /oficina/clientes.php?error=" . urlencode("O CPF deve conter exatamente 11 dígitos."));
        exit();
    }

    // 4. Verifica se o CPF já existe no banco para evitar erro de duplicidade
    if (findClienteByCpf($cpf)) {
        header("Location: /oficina/clientes.php?error=" . urlencode("Este CPF já está cadastrado no sistema."));
        exit();
    }

    // 5. Chamada do DAO para salvar
    try {
        $sucesso = saveCliente($nome, $cpf, $telefone, $email);

        if ($sucesso) {
            // Redireciona para a listagem com mensagem de sucesso
            header("Location: index.php?msg=" . urlencode("Cliente cadastrado com sucesso!"));
        } else {
            header("Location: /oficina/clientes.php?error=" . urlencode("Erro técnico ao salvar cliente."));
        }
    } catch (Exception $e) {
        header("Location: /oficina/clientes.php?error=" . urlencode("Erro inesperado: " . $e->getMessage()));
    }
    exit();
} else {
    // Se tentarem acessar o arquivo diretamente via URL (GET), manda de volta
    header("Location: /oficina/clientes.php");
    exit();
}