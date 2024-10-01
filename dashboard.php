<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <h1>Bem-vindo, <?php echo $_SESSION['usuario']; ?></h1>
        <nav>
            <ul>
                <li><a href="cadastro_usuarios.php">Cadastro de Usuários</a></li>
                <li><a href="cadastro_clientes.php">Cadastro de Clientes</a></li>
                <li><a href="contratos.php">Contratos</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>
