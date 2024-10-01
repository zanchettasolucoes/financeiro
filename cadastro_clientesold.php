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

    // Busca o usuário logado para obter informações como o superior e o idusuarios
    $stmt = $db->prepare("SELECT id, superior FROM usuarios WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $_SESSION['usuario']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Erro: Usuário não encontrado.";
        exit;
    }

    // Inicializa variáveis
    $erro = '';
    $sucesso = '';

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Captura os dados do formulário
        $tipo = $_POST['tipo'];
        $cliente = $_POST['cliente'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $cidade = $_POST['cidade'];
        $uf = $_POST['uf'];
        $observacao = $_POST['observacao'];
        $usuario = $_SESSION['usuario'];  // O usuário logado
        $superior = $user['superior'];    // O superior do usuário logado
        $idusuarios = $user['id'];        // ID do usuário logado (chave estrangeira)

        // Insere o novo cliente na tabela
        $stmt = $db->prepare("INSERT INTO clientes (tipo, cliente, telefone, endereco, numero, complemento, cidade, uf, observacao, usuario, superior, idusuarios) 
                              VALUES (:tipo, :cliente, :telefone, :endereco, :numero, :complemento, :cidade, :uf, :observacao, :usuario, :superior, :idusuarios)");
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':cliente', $cliente);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':uf', $uf);
        $stmt->bindParam(':observacao', $observacao);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':superior', $superior);
        $stmt->bindParam(':idusuarios', $idusuarios);
        $stmt->execute();

        $sucesso = "Cliente cadastrado com sucesso!";
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
    <title>Cadastro de Clientes</title>
</head>
<body>
    <h2>Cadastro de Clientes</h2>

    <?php if ($erro): ?>
        <p style="color: red;"><?php echo $erro; ?></p>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <p style="color: green;"><?php echo $sucesso; ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="tipo">Tipo:</label>
        <input type="text" name="tipo" id="tipo" required><br>

        <label for="cliente">Cliente:</label>
        <input type="text" name="cliente" id="cliente" required><br>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone"><br>

        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" id="endereco"><br>

        <label for="numero">Número:</label>
        <input type="text" name="numero" id="numero"><br>

        <label for="complemento">Complemento:</label>
        <input type="text" name="complemento" id="complemento"><br>

        <label for="cidade">Cidade:</label>
        <input type="text" name="cidade" id="cidade"><br>

        <label for="uf">UF:</label>
        <input type="text" name="uf" id="uf"><br>

        <label for="observacao">Observação:</label>
        <input type="text" name="observacao" id="observacao"><br>

        <!-- Campos ocultos para usuário e superior -->
        <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>">
        <input type="hidden" name="superior" value="<?php echo htmlspecialchars($superior); ?>">
        <input type="hidden" name="idusuarios" value="<?php echo htmlspecialchars($idusuarios); ?>">

        <button type="submit">Cadastrar Cliente</button>
    </form>

    <p><a href="dashboard.php">Voltar para o Dashboard</a></p>
</body>
</html>
