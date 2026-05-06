<?php
require_once __DIR__ . "/../dao/os.php";
include __DIR__ . "/../auth/isAuth.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST['cliente_id'];
    $veiculo_id = $_POST['veiculo_id'];
    $usuario_id = $_POST['usuario_id'];
    $descricao  = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
    $status     = $_POST['status'];
    $data     = $_POST['data'];
    $valor_total     = $_POST['valor_total'];

    if (saveOS($cliente_id, $veiculo_id, $usuario_id, $descricao, $status, $data, $valor_total)) {
        header("Location: index.php?msg=" . urlencode("OS gerada com sucesso!"));
    } else {
        header("Location: nova_os.php?error=" . urlencode("Erro ao gerar Ordem de Serviço."));
    }
    exit();
}