<?php
require_once __DIR__ . "/../dao/os.php";
include __DIR__ . "/../auth/isAuth.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
    $valor_total = $_POST['valor_total'];
    $data = $_POST['data'];

    if (empty($id) || empty($status) || empty($descricao)) {
        header("Location: update_os.php?id=$id&error=" . urlencode("Todos os campos são obrigatórios."));
        exit();
    }

    // Usando a função updateOS que já criamos no seu DAO de OS
    if (updateOS($id, $descricao, $status, $data, $valor_total)) {
        header("Location: index.php?msg=" . urlencode("Ordem de Serviço atualizada!"));
    } else {
        header("Location: update_os.php?id=$id&error=" . urlencode("Erro ao atualizar a OS no banco."));
    }
    exit();
}