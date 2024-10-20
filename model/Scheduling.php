<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Scheduling {

    public static function checkIfExists($ids) {
        try {
            $conexao = Conexao::getConexao();

            $sql = $conexao->prepare(
                "SELECT
                    EXISTS(
                    SELECT
                        id
                    FROM CLIENTE_SOLICITA_SERVICO
                    WHERE id IN(:id)
                    )");

            $values["id"] = $ids;

            $sql->execute($values);
            
            return $sql->fetchAll();

        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }
    
    /*
    Obtém os dados que serão carregados no site para os visitantes (usuários não logados)
    */
    public static function getSchedulings() {
        try {
            $conexao = Conexao::getConexao();

            $sql = $conexao->prepare(
                "SELECT clientes.nome, servicos.tipo,  cliente_solicita_servico.data_realizacao_servico
                    FROM clientes 
                    INNER JOIN cliente_solicita_servico 
                    ON clientes.cpf = cliente_solicita_servico.cpf_cliente 
                    INNER JOIN servicos
                    ON cliente_solicita_servico.id_servico = servicos.id ");

            $sql->execute();

            return $sql->fetchAll();
            
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }
    
}