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
    $stmt = $db->prepare("SELECT id, nivel FROM usuarios WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $_SESSION['usuario']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Erro: Usuário não encontrado.";
        exit;
    }

    // Se o nível for 0 ou 1, exibe todos os clientes, caso contrário, exibe apenas os do usuário da sessão
    if ($user['nivel'] == 0 || $user['nivel'] == 1) {
        // Seleciona todos os clientes
        $stmt = $db->query("SELECT * FROM clientes");
    } else {
        // Seleciona apenas os clientes do usuário logado
        $stmt = $db->prepare("SELECT * FROM clientes WHERE idusuarios = :idusuarios");
        $stmt->bindParam(':idusuarios', $user['id']);
        $stmt->execute();
    }

    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Clientes</title>
</head>
<body>
    <h2>Listar Clientes</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Telefone</th>
            <th>Cidade</th>
            <th>Ação</th>
        </tr>

        <?php if (count($clientes) > 0): ?>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['cliente']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['telefone1']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['endereco']); ?></td>
                    <td>
                        <a href="update_usuarios.php?id=<?php echo $cliente['id']; ?>">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Nenhum cliente encontrado.</td>
            </tr>
        <?php endif; ?>
    </table>

    <p><a href="dashboard.php">Voltar ao Dashboard</a></p>
</body>
</html>
