CREATE TABLE CLIENTE_SOLICITA_SERVICO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf_cliente CHAR(11) NOT NULL,
    id_servico BIGINT NOT NULL,
    data_solicitacao_servico DATE NOT NULL,
    data_realizacao_servico DATE,
    active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (cpf_cliente) REFERENCES CLIENTES(cpf),
    FOREIGN KEY (id_servico) REFERENCES SERVICOS(id)
)