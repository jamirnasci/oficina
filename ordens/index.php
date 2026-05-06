<?php
require_once __DIR__ . "/../dao/cliente.php";
$clientes = findAllClientes();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <?php
    include __DIR__ . "/../auth/isAuth.php";
    require_once __DIR__ . "/../headers/libs.php";
    foreach ($libs as $lib) {
        echo $lib;
    }
    ?>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .register-container {
            margin-top: 3%;
            margin-bottom: 3%;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #0d6efd;
            /* Azul para diferenciar do login */
            color: white;
            border-radius: 15px 15px 0 0 !important;
            text-align: center;
            padding: 1.5rem;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
    </style>
</head>

<body>
    <?php include '../headers/sideBar.php' ?>

    <div class="container-fluid pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold">Gestão de Ordens</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCadastroCliente">
                <i class="fa-solid fa-plus me-2"></i>Nova Ordem
            </button>
        </div>

        <!-- Filtros Rápidos -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="index.php" method="GET" class="row g-3">
                    <div class="col-md-8">
                        <input type="text" name="q" class="form-control"
                            placeholder="Buscar por cliente, placa ou número da OS..." value="<?= $_GET['q'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Todos os Status</option>
                            <option value="aberta">Aberta</option>
                            <option value="em_andamento">Em Andamento</option>
                            <option value="finalizada">Finalizada</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nº OS</th>
                                <th>Cliente / Veículo</th>
                                <th>Data Entrega</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once __DIR__ . '/../dao/os.php';
                            $ordens = findAllOS();

                            if (empty($ordens)) {
                                echo '<tr><td colspan="6" class="text-center py-4 text-muted">Nenhuma ordem de serviço encontrada.</td></tr>';
                            } else {
                                foreach ($ordens as $os) {
                                    // 1. Garantimos que temos um valor para o status, mesmo que venha nulo do banco
                                    $statusOriginal = $os['status'] ?? 'desconhecido';

                                    // 2. Criamos o mapeamento (Adicionei 'diagnostico' e 'reparo' caso você use)
                                    $cores = [
                                        'aberta'       => 'bg-info',
                                        'diagnostico'  => 'bg-primary',
                                        'em_andamento' => 'bg-warning text-dark',
                                        'reparo'       => 'bg-warning text-dark',
                                        'finalizada'   => 'bg-success',
                                        'cancelada'    => 'bg-danger',
                                        'desconhecido' => 'bg-secondary'
                                    ];

                                    // 3. Buscamos a cor (convertendo para minúsculo para evitar conflito de Case Sensitive)
                                    $statusClass = $cores[strtolower($statusOriginal)] ?? 'bg-secondary';

                                    echo "<tr>";
                                    echo "<td><span class='fw-bold text-primary'>#" . str_pad($os['id'], 5, '0', STR_PAD_LEFT) . "</span></td>";
                                    echo "<td>
                                            <div class='fw-bold'>" . htmlspecialchars($os['cliente_nome']) . "</div>
                                            <small class='text-muted'>" . htmlspecialchars($os['veiculo_modelo']) . " - " . strtoupper($os['placa']) . "</small>
                                        </td>";
                                    echo "<td>" . date('d/m/Y', strtotime($os['data'])) . "</td>";
                                    
                                    // 4. Exibição da Badge - Usamos o $statusOriginal tratado
                                    echo "<td><span class='badge $statusClass'>" . strtoupper(str_replace('_', ' ', $statusOriginal)) . "</span></td>";
                                    
                                    echo "<td class='fw-bold'>R$ " . number_format($os['valor_total'], 2, ',', '.') . "</td>";
                                    echo '<td class="text-end">
                                            <div class="btn-group">
                                                <a class="btn btn-sm btn-primary" href="update_page.php?id=' . $os['id'] . '"><i class="bi bi-pencil"></i></a>
                                            </div>
                                        </td>';
                                    echo "</tr>";
}
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCadastroCliente" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="modalLabel">Cadastrar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="create.php" method="POST">

                        <!-- PASSO 1: CLIENTE E VEÍCULO -->
                        <div class="form-section">
                            <h6 class="fw-bold mb-3"><span class="label-step">1. </span>Identificação</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Cliente</label>
                                    <select name="cliente_id" id="cliente_select" class="form-select" required
                                        onchange="buscarVeiculos(this.value)">
                                        <option value="">Selecione o cliente...</option>
                                        <?php foreach ($clientes as $c): ?>
                                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?>
                                                (<?= $c['cpf'] ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Veículo</label>
                                    <select name="veiculo_id" id="veiculo_select" class="form-select" required disabled>
                                        <option value="">Selecione o cliente primeiro...</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- PASSO 2: DETALHES DO SERVIÇO -->
                        <div class="form-section">
                            <h6 class="fw-bold mt-3"><span class="label-step">2. </span>Informações do Serviço</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Descrição do Problema / Relato do Cliente</label>
                                    <textarea name="descricao" class="form-control" rows="4"
                                        placeholder="Ex: Barulho na suspensão dianteira ao passar em buracos..."
                                        required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mecânico Responsável</label>
                                    <input type="text" class="form-control" value="<?= $_SESSION['username'] ?>"
                                        readonly>
                                    <input type="hidden" name="usuario_id" value="<?= $_SESSION['user_id'] ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status Inicial</label>
                                    <select name="status" class="form-select">
                                        <option value="aberta">Aberta (Entrada)</option>
                                        <option value="diagnostico">Em Diagnóstico</option>
                                        <option value="reparo">Reparo</option>
                                        <option value="finalizada">Finalizada</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Data de Entrega</label>
                                    <input type="date" class="form-control" name="data">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Valor Total (R$)</label>
                                    <input type="number" class="form-control" name="valor_total" step="0.5">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="index.php" class="btn btn-light px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-5">Gerar Ordem de Serviço</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (isset($_GET['msg'])) {
        echo '<script>alert("' . htmlspecialchars($_GET['msg']) . '")</script>';
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/oficina/public/js/ordens.js"></script>
</body>

</html>