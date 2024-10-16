CREATE TABLE PRESTADOR_SERVICO (
    cnpj CHAR(14) PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    telefone VARCHAR(11)
    CHECK (cnpj REGEXP '^[0-9]{14}$' and telefone REGEXP '^[0-9]{10,11}$')
)