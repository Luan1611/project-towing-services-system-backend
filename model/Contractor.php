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

            /*
            Problema: como podem existir múltiplas datas de realização de serviços para um mesmo cliente, como organizar isso no front-end após os dados da consulta serem parseados para objeto JS? vai ficar uma bagunça
            */

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

    /*
    Deleta todas as solicitações de serviços de clientes em determinada data (seria necessário retornar algo para o front, em caso de sucesso?)
    Consultas mais complexas
    */
    public static function deleteClientsServicesSchedulingsByDate($clientsId,$date) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                //TODO

            );
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

}