<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Service {

    /*
    Retorna o registro dos serviços ofertados pelo guincheiro (prestador de serviço)
    e as informações de cada serviço
    */ 
    public static function getServices() {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT css.data_realizacao_servico, s.id
                    FROM cliente_solicita_servico
                    INNER JOIN servicos 
                    ON cliente.cpf = cliente_solicita_servico.cpf_cliente");

            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }


    /* 
    Cria um novo serviço
    */
    public static function createService($codigo, $tipo, $preco) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT cpf, nome, telefone
                    FROM cliente WHERE cpf = ?");

                    
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

    /* 
    Atualiza um serviço
    */
    public static function updateService($serviceId) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT cpf, nome, telefone
                    FROM cliente WHERE cpf = ?");

                    
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }


    /* 
    Deleta um serviço
    */
    public static function deleteService($serviceId) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT cpf, nome, telefone
                    FROM cliente WHERE cpf = ?");

                    
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

}