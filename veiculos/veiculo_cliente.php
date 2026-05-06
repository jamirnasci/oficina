<?php
require_once __DIR__ . '/../dao/veiculo.php';
header('Content-Type: application/json');

$cliente_id = $_GET['id'] ?? null;

if ($cliente_id) {
    $veiculos = findVeiculosByCliente($cliente_id); 
    echo json_encode($veiculos);
} else {
    echo json_encode([]);
}