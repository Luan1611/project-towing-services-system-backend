CREATE TABLE PRESTADOR_OFERTA_SERVICO (
    cnpj_prestador VARCHAR(14) NOT NULL,
    id_servico INT NOT NULL,
    quantidade INT NOT NULL,
    data_oferta_servico DATE NOT NULL,
    PRIMARY KEY (cnpj_prestador, id_servico),
    FOREIGN KEY (cnpj_prestador) REFERENCES PRESTADOR_SERVICO(cnpj),
    FOREIGN KEY (id_servico) REFERENCES SERVICOS(id)
)