<?php
require_once __DIR__ . "/../dao/veiculo.php";
include __DIR__ . "/../auth/isAuth.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $cliente_id = filter_input(INPUT_POST, 'cliente_id', FILTER_SANITIZE_NUMBER_INT);
    $placa      = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $_POST['placa']));
    $marca      = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_SPECIAL_CHARS);
    $modelo     = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_SPECIAL_CHARS);
    $cor        = filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_SPECIAL_CHARS);
    $ano        = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);

    if (!$id || !$cliente_id || !$placa || !$marca || !$modelo) {
        header("Location: /oficina/veiculos?id=$id&error=" . urlencode("Campos obrigatórios não preenchidos."));
        exit();
    }

    // Tenta atualizar no banco via DAO
    $sucesso = updateVeiculo($id, $placa, $marca, $modelo, $ano, $cor);

    if ($sucesso) {
        header("Location: /oficina/veiculos?msg=" . urlencode("Veículo atualizado com sucesso!"));
    } else {
        header("Location: /oficina/veiculos?id=$id&error=" . urlencode("Erro ao atualizar dados do veículo."));
    }
    exit();
} else {
    header("Location: /oficina/veiculos.php");
    exit();
}