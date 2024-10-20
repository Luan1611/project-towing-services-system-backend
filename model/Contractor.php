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
                "SELECT C.nome, C.cpf, C.telefone, S.tipo, SSC.data_realizacao_servico
                    FROM CLIENTES C
                    INNER JOIN CLIENTE_SOLICITA_SERVICO SSC
                    ON C.cpf = SSC.cpf_cliente 
                    INNER JOIN SERVICOS S
                    ON SSC.id_SERVICO = S.id");

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