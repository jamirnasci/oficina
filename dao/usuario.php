<?php

require_once __DIR__ . "/../config/db.php";
function findOne($email)
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam("email", $email, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findById($id)
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->bindParam("id", $id, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function saveUser($nome, $email, $senha, $role): bool
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, role) VALUES (:nome, :email, :senha, :role)");
    $stmt->bindParam("nome", $nome, PDO::PARAM_STR);
    $stmt->bindParam("email", $email, PDO::PARAM_STR);
    $stmt->bindParam("senha", $senha, PDO::PARAM_STR);
    $stmt->bindParam("role", $role, PDO::PARAM_STR);

    return $stmt->execute();
}

function findAll()
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM usuarios");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findByNome($nome)
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE LOWER(nome) LIKE :nome");
    $nomeBusca = "%" . $nome . "%";
    $stmt->bindParam("nome", $nomeBusca, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function update($id, $nome, $email, $role, $new_password): bool
{
    // Lógica de Atualização
    try {
        $conn = Database::getConnection();

        if (!empty($new_password)) {
            // Atualiza TUDO, incluindo a senha
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nome = :nome, email = :email, role = :role, senha = :password WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':password', $hash);
        } else {
            // Atualiza apenas os dados básicos
            $sql = "UPDATE usuarios SET nome = :nome, email = :email, role = :role WHERE id = :id";
            $stmt = $conn->prepare($sql);
        }

        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    } catch (PDOException $e) {
        header("Location: edit.php?id=$id&error=" . urlencode("Erro no banco de dados."));
    }
    exit();
}