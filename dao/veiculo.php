<?php

require_once __DIR__ . "/../config/db.php";

/**
 * Busca um veículo pela placa (Única)
 */
function findVeiculoByPlaca($placa)
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM veiculos WHERE placa = :placa");
    $stmt->bindParam(":placa", $placa, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Busca todos os veículos de um cliente específico
 */
function findVeiculosByCliente($cliente_id)
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM veiculos WHERE cliente_id = :cliente_id ORDER BY created_at DESC");
    $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Busca um veículo pelo ID
 */
function findVeiculoById($id)
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM veiculos WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Salva um novo veículo
 */
function saveVeiculo($cliente_id, $placa, $modelo, $marca, $ano, $cor): bool
{
    $conn = Database::getConnection();
    $sql = "INSERT INTO veiculos (cliente_id, placa, modelo, marca, ano, cor) 
            VALUES (:cliente_id, :placa, :modelo, :marca, :ano, :cor)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
    $placeUpper = strtoupper($placa);
    $stmt->bindParam(":placa", $placeUpper, PDO::PARAM_STR); // Salva placa sempre em maiúsculo
    $stmt->bindParam(":modelo", $modelo, PDO::PARAM_STR);
    $stmt->bindParam(":cor", $cor, PDO::PARAM_STR);
    $stmt->bindParam(":marca", $marca, PDO::PARAM_STR);
    $stmt->bindParam(":ano", $ano, PDO::PARAM_INT);

    return $stmt->execute();
}

/**
 * Lista todos os veículos (com o nome do dono usando JOIN)
 */
function findAllVeiculos() {
    $conn = Database::getConnection();
    $sql = "SELECT v.*, c.nome as cliente_nome 
            FROM veiculos v 
            INNER JOIN clientes c ON v.cliente_id = c.id 
            ORDER BY v.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Atualiza os dados do veículo
 */
function updateVeiculo($id, $placa, $marca, $modelo, $ano, $cor): bool
{
    try {
        $conn = Database::getConnection();
        $sql = "UPDATE veiculos SET placa = :placa, modelo = :modelo, marca = :marca, ano = :ano, cor = :cor WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':placa', strtoupper($placa));
        $stmt->bindValue(':modelo', $modelo);
        $stmt->bindValue(':marca', $marca);
        $stmt->bindValue(':ano', $ano);
        $stmt->bindValue(':cor', $cor);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}

/**
 * Deleta um veículo
 */
function deleteVeiculo($id): bool
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("DELETE FROM veiculos WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}