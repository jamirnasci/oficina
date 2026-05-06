<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../dao/veiculo.php";
include __DIR__ . "/../auth/isAuth.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Captura e sanitização básica
    $cliente_id = filter_input(INPUT_POST, 'cliente_id', FILTER_SANITIZE_NUMBER_INT);
    $placa      = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $_POST['placa']));
    $marca      = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_SPECIAL_CHARS);
    $modelo     = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_SPECIAL_CHARS);
    $cor        = filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_SPECIAL_CHARS);
    $ano        = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);

    // Validação
    if (!$cliente_id || !$placa || !$marca || !$modelo) {
        header("Location: /oficina/veiculos?error=" . urlencode("Preencha todos os campos obrigatórios."));
        exit();
    }

    if (findVeiculoByPlaca($placa)) {
        header("Location: /oficina/veiculos?error=" . urlencode("Esta placa já está cadastrada no sistema."));
        exit();
    }

    // Salva no banco (Supondo a função saveVeiculo no seu DAO)
    $sucesso = saveVeiculo($cliente_id, $placa, $modelo, $marca, $ano, $cor);

    if ($sucesso) {
        header("Location: index.php?msg=" . urlencode("Veículo cadastrado com sucesso!"));
    } else {
        header("Location: /oficina/veiculos?error=" . urlencode("Erro ao salvar veículo no banco de dados."));
    }
    exit();
}