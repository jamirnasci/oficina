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
            <h2 class="h4 fw-bold">Gestão de Clientes</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCadastroCliente">
                <i class="fa-solid fa-plus me-2"></i>Novo Cliente
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
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Telefone</th>
                                <th>E-mail</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once __DIR__ . '/../dao/cliente.php';

                            $clientes = null;

                            // Lógica de Busca: Se houver 'q' na URL, filtra, senão traz todos
                            if (isset($_GET['q']) && strlen($_GET['q']) > 0) {
                                // Supondo que você tenha essa função no seu DAO de clientes
                                $clientes = findClienteByTermo($_GET['q']);
                            } else {
                                $clientes = findAllClientes();
                            }

                            if (sizeof($clientes) <= 0) {
                                echo '<tr class="text-secondary">';
                                echo '<td colspan="5" class="text-center py-4">Nenhum cliente encontrado.</td>';
                                echo '</tr>';
                            } else {
                                foreach ($clientes as $cliente) {
                                    // Formatação simples de CPF para exibição (opcional)
                                    $cpf_formatado = substr($cliente['cpf'], 0, 3) . '.' .
                                        substr($cliente['cpf'], 3, 3) . '.' .
                                        substr($cliente['cpf'], 6, 3) . '-' .
                                        substr($cliente['cpf'], 9, 2);

                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($cliente['nome']) . "</td>";
                                    echo "<td>" . $cpf_formatado . "</td>";
                                    echo "<td>" . htmlspecialchars($cliente['telefone'] ?? '---') . "</td>";
                                    echo "<td><small class='text-muted'>" . htmlspecialchars($cliente['email'] ?? '---') . "</small></td>";
                                    echo '<td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="/oficina/clientes/update_page.php?id=' . $cliente['id'] . '">
                            <i class="bi bi-pencil me-1"></i>Editar
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
                            <!-- Nome Completo -->
                            <div class="col-md-12">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" name="nome" id="nome" class="form-control"
                                    placeholder="Ex: José da Silva" required>
                            </div>

                            <!-- CPF -->
                            <div class="col-md-6">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" name="cpf" id="cpf" class="form-control" placeholder="000.000.000-00"
                                    maxlength="14" required>
                                <div class="form-text">Apenas números.</div>
                            </div>

                            <!-- Telefone -->
                            <div class="col-md-6">
                                <label for="telefone" class="form-label">Telefone / WhatsApp</label>
                                <input type="text" name="telefone" id="telefone" class="form-control"
                                    placeholder="(00) 00000-0000">
                            </div>

                            <!-- E-mail -->
                            <div class="col-md-12 mb-4">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="cliente@email.com">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-4">
                            <button type="reset" class="btn btn-light px-4">Limpar</button>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-lg me-2"></i>Salvar Cliente
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/oficina/public/js/clientes.js"></script>
</body>

</html>