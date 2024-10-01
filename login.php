<?php
session_start();

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conecta ao banco de dados SQLite
    try {
        $db = new PDO('sqlite:dbase.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Obtém os valores do formulário
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];

        // Consulta o banco de dados para verificar o usuário
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe e a senha é válida
        if ($user && password_verify($senha, $user['senha'])) {
            // Login bem-sucedido
            $_SESSION['usuario'] = $user['usuario'];
            echo "<script>loginStatus('ok');</script>";
            header("Location: dashboard.php");
            exit();
        } else {
            // Login falhou
            echo "<script>loginStatus('invalid');</script>";
        }

    } catch (PDOException $e) {
        echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="login-container">
        <form method="POST" id="login-form">
            <h2>Login</h2>
            <div class="input-group">
                <label for="usuario">Usuário</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit">Entrar</button>
            <p id="login-message"></p>
        </form>
    </div>

</body>
</html>

