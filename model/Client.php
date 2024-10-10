<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Client {

    /*
    Obtém os dados dos agendamentos de determinado cliente.
    Tais dados serão carregados na página "Meus agendamentos" do cliente.
    */
    public static function getClientSchedulingsData($clientCPF) {
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
    Obtém os dados cadastrais do cliente
    */
    public static function getClientRegistrationData($clientCPF) {
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