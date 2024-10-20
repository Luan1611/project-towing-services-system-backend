<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Authentication {

    public static function checkIfExists($emails) {
        try {
            $conexao = Conexao::getConexao();
            
            $sql = $conexao->prepare(
                "SELECT
                    EXISTS(
                    SELECT
                        email
                    FROM AUTH
                    WHERE email IN(:email)
                    )");

            $values["email"] = $emails;

            $sql->execute($values);

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }
    
    /*
    Retorna os dados cadastrais do usuário (client ou prestador de serviço), a partir
    do email e senha do mesmo
    */
    //TODO: string sql
    public static function getUserAuthenticationData($userData) {
        try {
            $conexao = Conexao::getConexao();

            $sql = $conexao->prepare(
            //TODO
            );

            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }


    /* 
        Retorna true se os dados forem criados com sucesso
    */
    //TODO: string SQL
    public static function createUserAccount($userData) {
        try {
            $conexao = Conexao::getConexao();

            $sql = $conexao->prepare(
            //TODO
            );

            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }
}