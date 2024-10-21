CREATE TABLE CLIENTES (
    cpf CHAR(11) NOT NULL,
    PRIMARY KEY (cpf),
    nome VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    active BOOLEAN DEFAULT TRUE
    CHECK (cpf REGEXP '^[0-9]{11}$')
)