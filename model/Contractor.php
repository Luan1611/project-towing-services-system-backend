<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Contractor {

    //Obtém os dados que serão carregados na página inicial do prestador de serviços
    public static function getClientServicesSchedulings() {
        try {
            $conexao = Conexao::getConexao();

            $sql = $conexao->prepare(
                "SELECT c.nome, c.cpf, c.tel, s.tipo, ssc.data_realizacao_servico
                    FROM cliente 
                    INNER JOIN solicitacao_servico_cliente 
                    ON cliente.id = solicitacao_servico_cliente.id_cliente 
                    INNER JOIN servico 
                    ON solicitacao_servico_cliente.id_servico = servico.id");

            $sql->execute();

            return $sql->fetchAll();

        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    /*
    Deleta todas as solicitações de serviços de clientes em determinada data
    */
    public static function deleteClientsServicesSchedulingsByDate($date) {
        try {
            $conexao = Conexao::getConexao();

            $sql = $conexao->prepare(
                "UPDATE 
                    CLIENTE_SOLICITA_SERVICO 
                    SET ACTIVE = FALSE 
                WHERE data_realizacao_servico = :date"
            );
            
            $values["date"] = $date;

            $sql->execute($values);

            return $sql->rowCount();
            
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

}