<?php
require_once __DIR__ . '/../dao/veiculo.php';
require_once __DIR__ . '/../dao/cliente.php';
include __DIR__ . "/../auth/isAuth.php";

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$veiculoFound = findVeiculoById($_GET['id']);
$clientes = findAllClientes(); // Para permitir trocar o dono, se necessário

if (!$veiculoFound) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Veículo - Sistema de Oficina</title>
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

        .plate-badge {
            background-color: #333;
            color: #fff;
            padding: 5px 15px;
            border-radius: 5px;
            font-family: monospace;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php include '../headers/sideBar.php' ?>

    <div class="container-fluid pt-4">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon-fill me-2"></i>
                <?= htmlspecialchars($_GET['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold">Editar Veículo</h2>
            <span class="plate-badge"><?= strtoupper($veiculoFound['placa']) ?></span>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="update.php" method="POST">
                    <input type="hidden" name="id" value="<?= $veiculoFound['id'] ?>">

                    <div class="row g-3">
                        <!-- Proprietário -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Proprietário Atual</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <select name="cliente_id" class="form-select" required>
                                    <?php foreach ($clientes as $c): ?>
                                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $veiculoFound['cliente_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($c['nome']) ?> (<?= $c['cpf'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Placa e Marca -->
                        <div class="col-md-6 mb-3">
                            <label for="placa" class="form-label">Placa</label>
                            <input type="text" name="placa" id="placa" class="form-control text-uppercase"
                                value="<?= htmlspecialchars($veiculoFound['placa']) ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" name="marca" id="marca" class="form-control"
                                value="<?= htmlspecialchars($veiculoFound['marca']) ?>" required>
                        </div>

                        <!-- Modelo e Cor -->
                        <div class="col-md-4 mb-3">
                            <label for="modelo" class="form-label">Modelo</label>
                            <input type="text" name="modelo" id="modelo" class="form-control"
                                value="<?= htmlspecialchars($veiculoFound['modelo']) ?>" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="cor" class="form-label">Cor</label>
                            <input type="text" name="cor" id="cor" class="form-control"
                                value="<?= htmlspecialchars($veiculoFound['cor']) ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ano" class="form-label">Ano</label>
                            <input type="text" name="ano" id="ano" class="form-control"
                                value="<?= htmlspecialchars($veiculoFound['ano']) ?>">
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save me-2"></i>Salvar Alterações
                        </button>
                        <a href="index.php" class="btn btn-light">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>