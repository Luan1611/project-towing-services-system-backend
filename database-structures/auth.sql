CREATE TABLE AUTH (
    email VARCHAR(255) PRIMARY KEY,
    senha VARCHAR(255) NOT NULL,
    user_id INT UNIQUE NOT NULL,
    classe_de_acesso VARCHAR(255)
)