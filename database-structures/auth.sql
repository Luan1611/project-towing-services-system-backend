CREATE TABLE AUTH (
    email VARCHAR(255) PRIMARY KEY,
    senha VARCHAR(255) NOT NULL,
    user_id VARCHAR(14) UNIQUE NOT NULL,
    classe_de_acesso INT NOT NULL
    CHECK (user_id REGEXP '^[0-9]{11,14}$')
)

/* user_id é o CPF do Cliente ou o CNPJ do prestador */