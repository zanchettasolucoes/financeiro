<?php
// Iniciar sessão para exibir mensagens de erro ou sucesso
session_start();

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simulação de validação simples (substituir por validação real)
    if ($username == 'admin' && $password == '123') {
        $_SESSION['message'] = 'Login bem-sucedido!';
        $_SESSION['status'] = 'success';
    } else {
        $_SESSION['message'] = 'Usuário ou senha incorretos!';
        $_SESSION['status'] = 'error';
    }

    // Redirecionar para a página para exibir a mensagem
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div id="message" class="<?php echo $_SESSION['status']; ?>">
                <?php echo $_SESSION['message']; ?>
            </div>
            <?php
            // Limpar a mensagem após exibição
            unset($_SESSION['message']);
            unset($_SESSION['status']);
            ?>
        <?php endif; ?>
        <form action="index.php" method="POST" id="loginForm">
            <div class="input-group">
                <label for="username">Usuário</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
