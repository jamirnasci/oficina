<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Oficina</title>
    <?php 
        require_once __DIR__."/headers/libs.php";
        foreach($libs as $lib){
            echo $lib;
        }
    ?>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            margin-top: 5%;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #212529;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            text-align: center;
            padding: 2rem;
        }
    </style>
</head>
<body>

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            
            <!-- Exibição de Mensagens de Erro (vindas do seu Controller) -->
            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <i class="bi bi-wrench-adjustable-circle fs-1"></i>
                    <h3 class="mt-2">Oficina Master</h3>
                    <p class="mb-0">Acesso ao Sistema</p>
                </div>
                <div class="card-body p-4">
                    <!-- O action deve apontar para o arquivo PHP que processa o login -->
                    <form action="./auth/login.php" method="POST">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="exemplo@oficina.com" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Sua senha" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                            </button>
                        </div>

                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <small class="text-muted">&copy; 2024 Gerenciador de Oficina v1.0</small>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <a href="#" class="text-decoration-none text-secondary small">Esqueceu a senha?</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>