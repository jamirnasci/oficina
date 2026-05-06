<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Sistema de Oficina</title>
    <?php
    include __DIR__ ."/../auth/isAuth.php";
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
            <h2 class="h4 fw-bold">Gestão de Usuários</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCadastroCliente">
                <i class="fa-solid fa-plus me-2"></i>Novo Usuário
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
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nome</th>
                                <th>E-mail</th>
                                <th>Acesso</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                require_once __DIR__ .'/../dao/usuario.php';
                                $users = null;
                                if(isset($_GET['q']) && strlen($_GET['q']) > 0){
                                    $users = findByNome($_GET['q']);
                                }else{
                                    $users = findAll();
                                }
                                if(sizeof($users) <= 0){
                                    echo '<tr class="text-secondary" th:if="${customers.empty}">';
                                    echo '<td colspan="5" class="text-center">Nenhum cliente encontrado.</td>';
                                    echo '</tr>';
                                }
                            
                                foreach($users as $user){
                                    echo "<tr>";
                                    echo "<td>" . $user['nome'] . "</td>";
                                    echo "<td>" . $user['email'] . "</td>";
                                    echo '<td class="' . ($user['role'] == 'admin' ? 'text-primary' : 'text-secondary') . '">' .strtoupper($user['role']) . "</td>";
                                    echo '<td class="text-end"><a class="btn btn-primary" href="/oficina/usuarios/update_page.php?id=' . $user['id'] .'">Abrir</a></td>';
                                    echo "</tr>";
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
                    <h5 class="modal-title fw-bold" id="modalLabel">Cadastrar Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="create.php" method="POST">

                        <!-- Campo Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="nome" id="nome" class="form-control"
                                    placeholder="Ex: João Silva" required>
                            </div>
                        </div>

                        <!-- Campo E-mail -->
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail Corporativo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="joao@oficina.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Nível de Acesso</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill-up"></i></span>
                                <select name="role" id="" class="form-select">
                                    <option value="funcionario">Funcionário</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                        </div>

                        <!-- Campo Senha -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Mínimo 6 caracteres" required>
                            </div>
                        </div>

                        <!-- Confirmação de Senha -->
                        <div class="mb-4">
                            <label for="password_confirm" class="form-label">Confirmar Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                <input type="password" name="password_confirm" id="password_confirm"
                                    class="form-control" placeholder="Repita a senha" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Finalizar Cadastro
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="/oficina/public/js/usuarios.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>