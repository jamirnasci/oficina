<?php
require_once __DIR__ ."/../dao/cliente.php";
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
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-octagon-fill me-2"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold">Gestão de Veículos</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCadastroCliente">
                <i class="fa-solid fa-plus me-2"></i>Novo Veículo
            </button>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0"
                                placeholder="Pesquisar por nome, CPF ou e-mail..." id="nomeBusca">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onClick="searchHandler()">Filtrar</button>
                    </div>
                </div>
            </div>
        </div>

       <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Placa</th>
                        <th>Veículo</th>
                        <th>Proprietário</th>
                        <th>Cor</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once __DIR__ . '/../dao/veiculo.php';
                    
                    $veiculos = null;

                    // Lógica de busca por Placa ou Modelo
                    if (isset($_GET['q']) && strlen($_GET['q']) > 0) {
                        $veiculos = findAllVeiculos();
                    } else {
                        $veiculos = findAllVeiculos();
                    }

                    if (empty($veiculos)) {
                        echo '<tr class="text-secondary">';
                        echo '<td colspan="5" class="text-center py-4">Nenhum veículo encontrado.</td>';
                        echo '</tr>';
                    } else {
                        foreach ($veiculos as $v) {
                            echo "<tr>";
                            // Placa em destaque (Badge)
                            echo "<td><span class='badge bg-dark font-monospace'>" . strtoupper($v['placa']) . "</span></td>";
                            
                            // Marca e Modelo
                            echo "<td>" . htmlspecialchars($v['marca']) . " " . htmlspecialchars($v['modelo']) . "</td>";
                            
                            // Nome do Cliente (Vindo do JOIN no DAO)
                            echo "<td><i class='bi bi-person me-1 text-muted'></i>" . htmlspecialchars($v['cliente_nome']) . "</td>";
                            
                            echo "<td>" . htmlspecialchars($v['cor'] ?? '---') . "</td>";
                            
                            echo '<td class="text-end">
                                    <a class="btn btn-sm btn-primary shadow-sm" href="/oficina/veiculos/update_page.php?id=' . $v['id'] . '">
                                        <i class="fa-solid fa-eye me-1"></i>Abrir
                                    </a>
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
                            
                            <div class="row g-3">
                                <!-- Seleção do Cliente -->
                                <div class="col-md-12 mb-3">
                                    <label for="cliente_id" class="form-label text-primary fw-bold">Proprietário</label>
                                    <select name="cliente_id" id="cliente_id" class="form-select select2" required>
                                        <option value="">Selecione o cliente...</option>
                                        <?php foreach($clientes as $c): ?>
                                            <option value="<?= $c['id'] ?>">
                                                <?= htmlspecialchars($c['nome']) ?> (<?= $c['cpf'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <hr class="my-4 text-muted">

                                <!-- Dados do Veículo -->
                                <div class="col-md-4">
                                    <label for="placa" class="form-label">Placa</label>
                                    <input type="text" name="placa" id="placa" class="form-control plate-input" 
                                           placeholder="ABC1D23" maxlength="7" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="marca" class="form-label">Marca</label>
                                    <input type="text" name="marca" id="marca" class="form-control" 
                                           placeholder="Ex: Toyota" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="modelo" class="form-label">Modelo</label>
                                    <input type="text" name="modelo" id="modelo" class="form-control" 
                                           placeholder="Ex: Corolla" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="cor" class="form-label">Cor</label>
                                    <input type="text" name="cor" id="cor" class="form-control" placeholder="Ex: Prata">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="ano" class="form-label">Ano</label>
                                    <input type="number" name="ano" id="ano" class="form-control" 
                                           placeholder="Ex: 2022" min="1900" max="<?= date('Y') + 1 ?>">
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-4">
                                <button type="reset" class="btn btn-light px-4">Limpar</button>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="bi bi-car-front-fill me-2"></i>Salvar Veículo
                                </button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <?php 
        if(isset($_GET['msg'])){
            echo '<script>alert("' . htmlspecialchars($_GET['msg']) . '")</script>';
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/oficina/public/js/clientes.js"></script>
</body>

</html>