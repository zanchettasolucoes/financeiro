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

    // Verifica se o ID do cliente foi passado pela URL
    if (!isset($_GET['id'])) {
        echo "ID do cliente não fornecido.";
        exit;
    }

    $cliente_id = $_GET['id'];

    // Busca os dados do cliente para exibição no formulário
    $stmt = $db->prepare("SELECT * FROM clientes WHERE id = :id");
    $stmt->bindParam(':id', $cliente_id);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        echo "Cliente não encontrado.";
        exit;
    }

    // Busca o usuário logado para obter informações de supervisor
    $stmt = $db->prepare("SELECT superior FROM usuarios WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $_SESSION['usuario']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Captura os dados atualizados do formulário
        $tipo = $_POST['tipo'];
        $cliente_nome = $_POST['cliente'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $cidade = $_POST['cidade'];
        $uf = $_POST['uf'];
        $observacao = $_POST['observacao'];
        $usuario = $_SESSION['usuario'];
        $superior = $user['superior'];
        $idusuarios = $cliente['idusuarios'];  // Mantém o idusuarios do cliente

        // Atualiza os dados do cliente no banco de dados
        $stmt = $db->prepare("UPDATE clientes SET tipo = :tipo, cliente = :cliente, telefone = :telefone, endereco = :endereco, 
                              numero = :numero, complemento = :complemento, cidade = :cidade, uf = :uf, observacao = :observacao, 
                              usuario = :usuario, superior = :superior WHERE id = :id");
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':cliente', $cliente_nome);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':uf', $uf);
        $stmt->bindParam(':observacao', $observacao);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':superior', $superior);
        $stmt->bindParam(':id', $cliente_id);
        $stmt->execute();

        echo "Cliente atualizado com sucesso!";
        header('Location: listar_clientes.php');
        exit;
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
    <title>Atualizar Cliente</title>
</head>
<body>
    <h2>Atualizar Cliente</h2>

    <form method="post">
        <label for="tipo">Tipo:</label>
        <input type="text" name="tipo" id="tipo" value="<?php echo htmlspecialchars($cliente['tipo']); ?>" required><br>

        <label for="cliente">Cliente:</label>
        <input type="text" name="cliente" id="cliente" value="<?php echo htmlspecialchars($cliente['cliente']); ?>" required><br>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>"><br>

        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" id="endereco" value="<?php echo htmlspecialchars($cliente['endereco']); ?>"><br>

        <label for="numero">Número:</label>
        <input type="text" name="numero" id="numero" value="<?php echo htmlspecialchars($cliente['numero']); ?>"><br>

        <label for="complemento">Complemento:</label>
        <input type="text" name="complemento" id="complemento" value="<?php echo htmlspecialchars($cliente['complemento']); ?>"><br>

        <label for="cidade">Cidade:</label>
        <input type="text" name="cidade" id="cidade" value="<?php echo htmlspecialchars($cliente['cidade']); ?>"><br>

        <label for="uf">UF:</label>
        <input type="text" name="uf" id="uf" value="<?php echo htmlspecialchars($cliente['uf']); ?>"><br>

        <label for="observacao">Observação:</label>
        <input type="text" name="observacao" id="observacao" value="<?php echo htmlspecialchars($cliente['observacao']); ?>"><br>

        <!-- Campos ocultos -->
        <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($_SESSION['usuario']); ?>">
        <input type="hidden" name="superior" value="<?php echo htmlspecialchars($user['superior']); ?>">
        <input type="hidden" name="idusuarios" value="<?php echo htmlspecialchars($cliente['idusuarios']); ?>">

        <button type="submit">Atualizar Cliente</button>
    </form>

    <p><a href="listar_clientes.php">Voltar para a Lista de Clientes</a></p>
</body>
</html>
