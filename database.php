<?php
try {
    // Cria ou abre o banco de dados SQLite
    $db = new PDO('sqlite:dbase.db');

    // Define o modo de erro para exceções
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cria a tabela "usuarios"
    $db->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario TEXT UNIQUE NOT NULL,
        senha TEXT NOT NULL,
        nivel INTEGER NOT NULL,
        dtcadastro TEXT NOT NULL,
        superior TEXT
    )");

    // Insere o usuário "zanchetta"
    $senha = password_hash('717051', PASSWORD_DEFAULT); // Criptografa a senha
    $dtcadastro = date('Y-m-d H:i:s'); // Data de cadastro atual
    $db->exec("INSERT INTO usuarios (usuario, senha, nivel, dtcadastro, superior) 
               VALUES ('zanchetta', '$senha', 0, '$dtcadastro', 'zanchetta')");

    // Cria a tabela "clientes"
    $db->exec("CREATE TABLE IF NOT EXISTS clientes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        cpf TEXT NOT NULL,
        cliente TEXT NOT NULL,
        telefone1 TEXT NOT NULL,
        telefone2 TEXT,
        endereco TEXT,
        observacao TEXT,
        avalista TEXT,
        telefone_avalista TEXT,
        usuario TEXT,
        superior TEXT,
        idusuarios INTEGER,
        categoria TEXT,
        FOREIGN KEY (idusuarios) REFERENCES usuarios(id)
    )");

    // Cria a tabela "validade"
    $db->exec("CREATE TABLE IF NOT EXISTS validade (
        data TEXT NOT NULL
    )");

    // Cria a tabela "contratos"
    $db->exec("CREATE TABLE IF NOT EXISTS contratos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        dtinicio TEXT NOT NULL,
        dtfim TEXT NOT NULL,
        dtvenc TEXT NOT NULL,
        status TEXT NOT NULL,
        idcliente INTEGER,
        taxa REAL,
        tipo TEXT,
        valor REAL,
        FOREIGN KEY (idcliente) REFERENCES clientes(id)
    )");

    // Cria a tabela "movimentacoes"
    $db->exec("CREATE TABLE IF NOT EXISTS movimentacoes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        idcontrato INTEGER,
        idcliente INTEGER,
        dtinicio TEXT,
        dtfim TEXT,
        dtvenc TEXT,
        valorrecebido REAL,
        taxa REAL,
        tipo TEXT,
        saldomovimentacao REAL,
        areceber REAL,
        usuario TEXT,
        superior TEXT,
        valorcontrato REAL,
        ok INTEGER,
        copiado INTEGER,
        valordiaatraso REAL,
        totalatraso REAL,
        totalcomatraso REAL,
        domingos INTEGER,
        abatimento REAL,
        acrescimo REAL,
        diasemaberto INTEGER,
        pgtopendente INTEGER,
        deletar INTEGER,
        ativo INTEGER,
        reabertoatrasopgto INTEGER,
        online INTEGER,
        nome_cliente TEXT,
        FOREIGN KEY (idcontrato) REFERENCES contratos(id),
        FOREIGN KEY (idcliente) REFERENCES clientes(id)
    )");

    echo "Base de dados e tabelas criadas com sucesso!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
