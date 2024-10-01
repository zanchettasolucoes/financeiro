<?php
// Conecta ao banco de dados SQLite
try {
    $db = new PDO('sqlite:dbase.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit();
}

$cpf = "";
$cpfExists = false;
$message = "";
$showForm = false;

// Verifica se o formulário de pesquisa foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cpf_search'])) {
    $cpf = $_POST['cpf'];

    // Consulta o CPF no banco de dados
    $stmt = $db->prepare("SELECT * FROM clientes WHERE cpf = :cpf LIMIT 1");
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();

    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        $cpfExists = true;
        $message = "O CPF consultado já foi registrado.";
    } else {
        $showForm = true; // Exibe o formulário
    }
}

// Verifica se o formulário de cadastro foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_form'])) {
    $cpf = $_POST['cpf'];
    $cliente = $_POST['cliente'];
    $telefone1 = $_POST['telefone1'];
    $telefone2 = $_POST['telefone2'];
    $endereco = $_POST['endereco'];
    $avalista = $_POST['avalista'];
    $telefone_avalista = $_POST['telefone_avalista'];
    $observacao = $_POST['observacao'];
    $idusuarios = $_POST['idusuarios'];
    $categoria = $_POST['categoria'];

    // Insere os dados no banco de dados
    $stmt = $db->prepare("INSERT INTO clientes (cpf, cliente, telefone1, telefone2, endereco, avalista, telefone_avalista, observacao, idusuarios, categoria) 
                          VALUES (:cpf, :cliente, :telefone1, :telefone2, :endereco, :avalista, :telefone_avalista, :observacao, :idusuarios, :categoria)");
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':cliente', $cliente);
    $stmt->bindParam(':telefone1', $telefone1);
    $stmt->bindParam(':telefone2', $telefone2);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':avalista', $avalista);
    $stmt->bindParam(':telefone_avalista', $telefone_avalista);
    $stmt->bindParam(':observacao', $observacao);
    $stmt->bindParam('categoria', $categoria);
    $stmt->bindParam(':idusuarios', $idusuarios);

    if ($stmt->execute()) {
        $message = "Cadastro realizado com sucesso!";
        $showForm = false; // Oculta o formulário após o cadastro
    } else {
        $message = "Erro ao cadastrar o cliente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de CPF</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php if (!$showForm): ?>
            <!-- Formulário de pesquisa de CPF -->
            <form method="POST" id="cpf-search-form">
                <h2>Consultar CPF</h2>
                <div class="input-group">
                    <label for="cpf">Informe o Nº</label>
                    <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>" required>
                </div>
                <button type="submit" name="cpf_search">Consultar</button>
                <p></p>
                <p id="message"><?php echo $message; ?></p>
            </form>
        <?php endif; ?>

        <?php if ($showForm): ?>
            <!-- Formulário de cadastro de cliente -->
            <form method="POST" id="client-form">
                <h2>Cadastro de Cliente</h2>
                <div class="input-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>" readonly>
                </div>
                <div class="input-group">
                    <label for="cliente">Cliente:</label>
                    <input type="text" id="cliente" name="cliente" required>
                </div>
                <div class="input-group">
                    <label for="telefone1">Telefone 1:</label>
                    <input type="text" id="telefone1" name="telefone1" required>
                </div>
                <div class="input-group">
                    <label for="telefone2">Telefone 2:</label>
                    <input type="text" id="telefone2" name="telefone2">
                </div>
                <div class="input-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>
                <div class="input-group">
                    <label for="avalista">Avalista:</label>
                    <input type="text" id="avalista" name="avalista">
                </div>
                <div class="input-group">
                    <label for="telefone_avalista">Telefone Avalista:</label>
                    <input type="text" id="telefone_avalista" name="telefone_avalista">
                </div>
                <div class="input-group">
                    <label for="observacao">Observação:</label>
                    <textarea id="observacao" name="observacao"></textarea>
                </div>
                <input type="hidden" name="categoria" value="SEM REFERENCIA" id="categoria">
                <input type="hidden" name="idusuarios" value="1"> <!-- Exemplo de campo oculto para idusuarios -->
                <button type="submit" name="submit_form">Cadastrar Cliente</button>
            </form>
        <?php endif; ?>
    </div>
    <script src="script.js"></script>
</body>
</html>
