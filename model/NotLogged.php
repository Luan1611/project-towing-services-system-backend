<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class NotLogged {
    
    /*
    Obtém os dados que serão carregados no site para os visitantes (não logados)
    */
    public static function getSchedulings() {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT c.nome, s.tipo, ssc.data_realizacao_servico
                    FROM cliente 
                    INNER JOIN cliente_solicita_servico 
                    ON cliente.cpf = cliente_solicita_servico.cpf_cliente 
                    INNER JOIN servico 
                    ON cliente_solicita_servico.id_servico = servico.id ");
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }
}