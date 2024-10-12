<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Authentication {
    
    /*
    Retorna os dados cadastrais do usuário (client ou prestador de serviço), a partir
    do email e senha do mesmo
    */
    public static function getUserAuthenticationData($userData) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT cpf, nome, telefone
                    FROM auth WHERE cpf = ?");

                    
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }


    /* 
        Retorna true se os dados forem criados com sucesso
    */
    public static function createUserAccount($userData) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT cpf, nome, telefone
                    FROM auth WHERE cpf = ?");

                    
            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }
}