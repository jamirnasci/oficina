<?php
require_once __DIR__ . '/../dao/usuario.php';
include __DIR__ . "/../auth/isAuth.php";

if (!isset($_GET['id'])) {
    header('Location: /oficina/usuarios/index.php'); // Corrigido: Location e caminho
    exit();
}

$userFound = findById($_GET['id']);

if (!$userFound) {
    header('Location: /oficina/usuarios/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Sistema de Oficina</title>
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
            <h2 class="h4 fw-bold">Gestão de Usuários</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCadastroCliente">
                <i class="fa-solid fa-plus me-2"></i>Novo Usuário
            </button>
        </div>

        <div class="">
            <div class="card-body p-0">
                <form action="update.php" method="POST">
                    <!-- CRUCIAL: Campo oculto com o ID -->
                    <input type="hidden" name="id" value="<?= $userFound['id'] ?>">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input value="<?= htmlspecialchars($userFound['nome']) ?>" type="text" name="nome" id="nome"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail Corporativo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input value="<?= htmlspecialchars($userFound['email']) ?>" type="email" name="email"
                                id="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nível de Acesso</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill-up"></i></span>
                            <select name="role" class="form-select">
                                <option value="funcionario" <?= $userFound['role'] == 'funcionario' ? 'selected' : '' ?>>
                                    Funcionário</option>
                                <option value="admin" <?= $userFound['role'] == 'admin' ? 'selected' : '' ?>>Administrador
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha (Opcional)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="new_password" id="password" class="form-control"
                                placeholder="Deixe em branco para não alterar">
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

    <script src="/oficina/public/js/usuarios.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>