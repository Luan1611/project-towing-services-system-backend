CREATE TABLE CLIENTE_SOLICITA_SERVICO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf_cliente VARCHAR(11) NOT NULL,
    id_servico INT NOT NULL,
    data_solicitacao_servico DATE NOT NULL,
    data_realizacao_servico DATE,
    FOREIGN KEY (cpf_cliente) REFERENCES CLIENTE(cpf),
    FOREIGN KEY (id_servico) REFERENCES SERVICOS(id)
)