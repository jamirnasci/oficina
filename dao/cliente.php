<?php

require_once __DIR__ . "/../config/db.php";

function findClienteByCpf($cpf)
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE cpf = :cpf");
    $stmt->bindParam(":cpf", $cpf, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findClienteById($id)
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function saveCliente($nome, $cpf, $telefone, $email): bool
{
    $conn = Database::getConnection();
    $sql = "INSERT INTO clientes (nome, cpf, telefone, email) VALUES (:nome, :cpf, :telefone, :email)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
    $stmt->bindParam(":cpf", $cpf, PDO::PARAM_STR);
    $stmt->bindParam(":telefone", $telefone, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);

    return $stmt->execute();
}

function findAllClientes()
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM clientes ORDER BY nome ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findClienteByTermo($termo)
{
    $conn = Database::getConnection();
    $sql = "SELECT * FROM clientes WHERE LOWER(nome) LIKE :termo OR cpf LIKE :termo";
    $stmt = $conn->prepare($sql);
    $busca = "%" . strtolower($termo) . "%";
    $stmt->bindParam(":termo", $busca, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateCliente($id, $nome, $cpf, $telefone, $email): bool
{
    try {
        $conn = Database::getConnection();
        $sql = "UPDATE clientes SET nome = :nome, cpf = :cpf, telefone = :telefone, email = :email WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        // Redireciona com erro caso haja falha (ex: CPF duplicado)
        header("Location: edit_cliente.php?id=$id&error=" . urlencode("Erro ao atualizar cliente."));
        exit();
    }
}

function deleteCliente($id): bool
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    return $stmt->execute();
}