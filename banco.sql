-- Tabelas criadas automaticamente pelo PHP no SQLite.
CREATE TABLE IF NOT EXISTS produtos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cod VARCHAR(30) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    validade DATE NOT NULL,
    quantidade INTEGER NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    preco_pacote DECIMAL(10,2) NOT NULL,
    imagem VARCHAR(255) NOT NULL DEFAULT ''
);

CREATE TABLE IF NOT EXISTS clientes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    telefone VARCHAR(25) NOT NULL
);

CREATE TABLE IF NOT EXISTS vendas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cliente_nome VARCHAR(100) NOT NULL,
    data_venda DATE NOT NULL,
    total DECIMAL(24,2) NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS itens_venda (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    venda_id INT NOT NULL,
    produto_nome VARCHAR(100) NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venda_id) REFERENCES vendas(id)
);

CREATE TABLE IF NOT EXISTS relatorios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo VARCHAR(100) NOT NULL,
    inicio DATE NOT NULL,
    fim DATE NOT NULL
);

CREATE INDEX IF NOT EXISTS vendas_data ON vendas (data_venda);
