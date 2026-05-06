<?php
require_once __DIR__ . '/../dao/cliente.php';
include __DIR__ . "/../auth/isAuth.php";

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$cliente = findClienteById($_GET['id']);

if (!$cliente) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - Sistema de Oficina</title>
    <?php
    require_once __DIR__ . "/../headers/libs.php";
    foreach ($libs as $lib) {
        echo $lib;
    }
    ?>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <?php include '../headers/sideBar.php' ?>

    <div class="container-fluid pt-4">
        <!-- Alerta de Erro -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= htmlspecialchars($_GET['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold">Editar Cliente</h2>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="">
            <div class="card-body p-0">
                <form action="update.php" method="POST">
                    <!-- ID oculto para o UPDATE -->
                    <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="nome" id="nome" class="form-control"
                                value="<?= htmlspecialchars($cliente['nome']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cpf" class="form-label">CPF</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text" name="cpf" id="cpf" class="form-control"
                                    value="<?= htmlspecialchars($cliente['cpf']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="telefone" class="form-label">Telefone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefone" id="telefone" class="form-control"
                                    value="<?= htmlspecialchars($cliente['telefone']) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">E-mail</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control"
                                value="<?= htmlspecialchars($cliente['email']) ?>">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Atualizar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>