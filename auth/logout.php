<?php
// 1. Inicia a sessão para ter acesso aos dados atuais
session_start();

// 2. Limpa todas as variáveis de sessão (limpa o array $_SESSION)
session_unset();

// 3. Destrói a sessão no servidor
session_destroy();

// 4. Opcional: Remove o cookie de sessão do navegador (mais seguro)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 5. Redireciona para o login com uma mensagem de sucesso
$msg = "Você saiu do sistema com segurança.";
header("Location: /oficina/login.php?msg=" . urlencode($msg));
exit();