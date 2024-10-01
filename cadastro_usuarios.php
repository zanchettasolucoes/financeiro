<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

try {
    // Conecta ao banco de dados SQLite
    $db = new PDO('sqlite:dbase.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica o nível de acesso do usuário logado
    $stmt = $db->prepare("SELECT nivel FROM usuarios WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $_SESSION['usuario']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || ($user['nivel'] != 0 && $user['nivel'] != 1)) {
        echo "Você não tem permissão para acessar esta página.";
        exit;
    }

    // Inicializa variáveis
    $erro = '';
    $sucesso = '';

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = $_POST['usuario'];
        $nivel = $_POST['nivel'];
        $supervisor = $_POST['supervisor'];

        // Verifica se o usuário já existe
        $stmt = $db->prepare("SELECT usuario FROM usuarios WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $erro = "O nome de usuário já existe. Escolha outro nome.";
        } else {
            // Se o usuário não existir, permite o cadastro
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha
            $dtcadastro = date('Y-m-d H:i:s');

            // Insere o novo usuário no banco de dados
            $stmt = $db->prepare("INSERT INTO usuarios (usuario, senha, nivel, dtcadastro, superior) 
                                  VALUES (:usuario, :senha, :nivel, :dtcadastro, :supervisor)");
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':nivel', $nivel);
            $stmt->bindParam(':dtcadastro', $dtcadastro);
            $stmt->bindParam(':supervisor', $supervisor);
            $stmt->execute();

            $sucesso = "Usuário cadastrado com sucesso!";
        }
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Cadastro de Usuários</h2>

    <?php if ($erro): ?>
        <p style="color: red;"><?php echo $erro; ?></p>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <p style="color: green;"><?php echo $sucesso; ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" id="usuario" required><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required><br>

        <label for="nivel">Nível de Acesso:</label>
        <select name="nivel" id="nivel" required>
            <option value="99">Selecione:</option>
            <option value="1">Diretoria</option>
            <option value="2">Gerencia</option>
            <option value="3">Vendedor</option>
        </select><br>

        <label for="supervisor">Supervisor:</label>
        <input type="text" name="supervisor" id="supervisor" value="<?php echo htmlspecialchars($_SESSION['usuario']); ?>"><br>

        <button type="submit">Cadastrar</button>
    </form>

    <p><a href="dashboard.php">Voltar para o Dashboard</a></p>
    <script src="script.js"></script>
</body>
</html>
