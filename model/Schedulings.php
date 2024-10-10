<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Schedulings {
    public static function getSchedulings() {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT c.nome, s.tipo, ssc.data_realizacao_servico
                    FROM cliente 
                    INNER JOIN solicitacao_servico_cliente 
                    ON cliente.id = solicitacao_servico_cliente.id_cliente 
                    INNER JOIN servico 
                    ON solicitacao_servico_cliente.id_servico = servico.id ");
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }
}