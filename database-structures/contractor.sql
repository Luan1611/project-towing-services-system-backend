CREATE TABLE PRESTADOR_SERVICO (
    cnpj CHAR(14) PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    CHECK (cpf REGEXP '^[0-9]{14}$')
    telefone VARCHAR(11)
    CHECK (telefone REGEXP '^[0-9]{10,11}$')
)