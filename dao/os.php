<?php

require_once __DIR__ . "/../config/db.php";

/**
 * Cria uma nova Ordem de Serviço
 */
function saveOS($cliente_id, $veiculo_id, $usuario_id, $descricao, $status = 'aberta', $data, $valor_total): bool
{
    $conn = Database::getConnection();
    $sql = "INSERT INTO ordens (cliente_id, veiculo_id, usuario_id, descricao, status, data, valor_total) 
            VALUES (:cliente_id, :veiculo_id, :usuario_id, :descricao, :status, :data, :valor_total)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
    $stmt->bindParam(":veiculo_id", $veiculo_id, PDO::PARAM_INT);
    $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(":descricao", $descricao, PDO::PARAM_STR);
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->bindParam(":data", $data, PDO::PARAM_STR);
    $stmt->bindParam(":valor_total", $valor_total, PDO::PARAM_STR);

    return $stmt->execute();
}

/**
 * Lista todas as OS com nomes de Clientes, Veículos (Placa) e Funcionários
 */
function findAllOS() {
    $conn = Database::getConnection();
    $sql = "SELECT os.*, c.nome as cliente_nome, v.modelo as veiculo_modelo, v.placa 
            FROM ordens os
            INNER JOIN clientes c ON os.cliente_id = c.id
            INNER JOIN veiculos v ON os.veiculo_id = v.id
            ORDER BY os.data DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Busca uma OS específica por ID com todos os detalhes
 */
function findOSById($id)
{
    $conn = Database::getConnection();
    $sql = "SELECT os.*, 
                   c.nome as cliente_nome, c.telefone as cliente_tel,
                   v.placa, v.modelo, v.marca,
                   u.nome as funcionario_nome
            FROM ordens os
            INNER JOIN clientes c ON os.cliente_id = c.id
            INNER JOIN veiculos v ON os.veiculo_id = v.id
            INNER JOIN usuarios u ON os.usuario_id = u.id
            WHERE os.id = :id";
            
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Atualiza o status e a descrição da OS
 */
function updateOS($id, $descricao, $status, $data, $valor_total): bool
{
    try {
        $conn = Database::getConnection();
        $sql = "UPDATE ordens 
                SET descricao = :descricao, status = :status, data = :data, valor_total = :valor_total 
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':data', $data);
        $stmt->bindValue(':valor_total', $valor_total);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Busca OS por status (ex: listar apenas as 'abertas')
 */
function findOSByStatus($status)
{
    $conn = Database::getConnection();
    $sql = "SELECT os.*, c.nome as cliente_nome, v.placa 
            FROM ordens os
            JOIN clientes c ON os.cliente_id = c.id
            JOIN veiculos v ON os.veiculo_id = v.id
            WHERE os.status = :status";
            
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Deleta uma OS (Cuidado: Geralmente é melhor apenas mudar o status para 'cancelada')
 */
function deleteOS($id): bool
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("DELETE FROM ordens WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}