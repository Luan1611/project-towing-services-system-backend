<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Administrator {

    //Retorna os dados que serão carregados na página inicial do admin
    public static function getClientServicesSchedulings() {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT c.nome, c.cpf, c.tel, s.tipo, ssc.data_realizacao_servico
                    FROM cliente 
                    INNER JOIN solicitacao_servico_cliente 
                    ON cliente.id = solicitacao_servico_cliente.id_cliente 
                    INNER JOIN servico 
                    ON solicitacao_servico_cliente.id_servico = servico.id ");
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
    Deleta todos as solicitações de serviços de clientes para determinada data (como retornaria todo mundo deletado para o front, para atualizar os dados?)
    */
    public static function deleteClientServicesSchedulings($clientNames, $date) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(


            );
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

    /*
    Retorna o registro dos serviços ofertados pelo guincheiro (administrador)
    e as informações de cada serviço
    */ 
    public static function getRegisteredServices() {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(


            );
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

}