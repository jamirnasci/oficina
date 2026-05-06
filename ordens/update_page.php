<?php
require_once __DIR__ . '/../dao/os.php';
include __DIR__ . "/../auth/isAuth.php";

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$os = findOSById($_GET['id']); // Certifique-se que o DAO traz nomes de cliente e veículo

if (!$os) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Atualizar OS #<?= str_pad($os['id'], 5, '0', STR_PAD_LEFT) ?></title>
    <?php
    require_once __DIR__ . "/../headers/libs.php";
    foreach ($libs as $lib) {
        echo $lib;
    }
    ?>
    <style>
        .status-header {
            border-left: 5px solid #0d6efd;
            padding-left: 15px;
        }

        .info-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-value {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?php include '../headers/sideBar.php' ?>

    <div class="container-fluid pt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="status-header">
                        <h2 class="h4 fw-bold mb-0">Ordem de Serviço <?= str_pad($os['id'], 5, '0', STR_PAD_LEFT) ?>
                        </h2>
                        <small class="text-muted">Aberta em:
                            <?= date('d/m/Y H:i', strtotime($os['created_at'])) ?></small>
                    </div>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Voltar</a>
                </div>

                <div class="row">
                    <!-- Resumo Fixo (Esquerda) -->
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3 border-bottom pb-2">Detalhes do Cadastro</h6>

                                <p class="info-label">Cliente</p>
                                <p class="info-value"><?= htmlspecialchars($os['cliente_nome']) ?></p>

                                <p class="info-label">Veículo</p>
                                <p class="info-value"><?= htmlspecialchars($os['marca'] . ' ' . $os['modelo']) ?></p>

                                <p class="info-label">Placa</p>
                                <p class="info-value"><span
                                        class="badge bg-dark font-monospace"><?= strtoupper($os['placa']) ?></span></p>

                                <p class="info-label">Técnico Responsável</p>
                                <p class="info-value"><i class="bi bi-person-gear"></i>
                                    <?= htmlspecialchars($os['funcionario_nome']) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário de Edição (Direita) -->
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <form action="update.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $os['id'] ?>">

                                    <div class="mb-4">
                                        <label for="status" class="form-label fw-bold">Status da Ordem</label>
                                        <select name="status" id="status"
                                            class="form-select form-select-lg border-primary">
                                            <option value="aberta" <?= $os['status'] == 'aberta' ? 'selected' : '' ?>>
                                                Aberta / Aguardando</option>
                                            <option value="diagnostico" <?= $os['status'] == 'diagnostico' ? 'selected' : '' ?>>Em Diagnóstico</option>
                                            <option value="reparo" <?= $os['status'] == 'reparo' ? 'selected' : '' ?>>Em
                                                Reparo</option>
                                            <option value="finalizada" <?= $os['status'] == 'finalizada' ? 'selected' : '' ?>>Finalizada / Entregue</option>
                                            <option value="cancelada" <?= $os['status'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label for="descricao" class="form-label fw-bold">Descrição dos Serviços e
                                            Diagnóstico</label>
                                        <textarea name="descricao" id="descricao" class="form-control" rows="8"
                                            required><?= htmlspecialchars($os['descricao']) ?></textarea>
                                        <div class="form-text">Relate aqui os defeitos encontrados e as peças
                                            substituídas.</div>
                                    </div>

                                    <div>
                                        <div class="col-md-6">
                                            <label class="form-label">Data de Entrega</label>
                                            <input type="date" class="form-control" name="data" value="<?php echo $os['data'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Valor Total (R$)</label>
                                            <input type="number" class="form-control" name="valor_total" step="0.5" value="<?php echo $os['valor_total'] ?>">
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="bi bi-save me-2"></i>Atualizar Ordem
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>