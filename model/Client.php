<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Client {

    /*
    Obtém os dados dos agendamentos de determinado cliente.
    Tais dados serão carregados na página "Meus Agendamentos" do cliente.
    */
    public static function getSchedulingsData($cpf) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT 
                    css.data_realizacao_servico, 
                    s.id, 
                    s.name,
                    s.valor
                FROM cliente_solicita_servico css
                INNER JOIN servicos 
                    ON s.id = css.id_servicos
                WHERE css.cpf_cliente = :cpf");

            $values['cpf'] = $cpf
            //TODO: reformular string de consulta
            $sql->execute($values);

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

    /*
    Obtém os dados cadastrais do cliente
    */
    public static function getRegistrationData($cpf) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT 
                    cpf, 
                    nome, 
                    telefone
                FROM cliente 
                    WHERE cpf = :cpf");

            $values['cpf'] = $cpf;
                    
            $sql->execute($values);

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

    /*
    Atualiza os dados cadastrais do cliente
    */
    public static function updateRegistrationData($cpf, $name, $phone) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "UPDATE CLIENTES SET
                    nome = :nome,
                    tel = :tel
                WHERE cpf = :cpf");

            $values['nome'] = $nome;
            $values['tel'] = $tel;
            $values['cpf'] = $cpf;

            //TODO: checar quantas tuplas foram afetadas e condicionar o return
            // ao resultado
                    
            $sql->execute($values);

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

    /* 
    Cria uma nova conta de cliente
    */
    public static function createAccount($email, $password, $cpf, $name, $phone) {
        try {
            $conexao = Conexao::getConexao();
            
            $conexao->beginTransaction();

            $sql = $conexao->prepare(
                "INSERT INTO AUTH (
                    email,
                    senha,
                    user_id,
                    classe_de_acesso
                ) VALUES (
                    :email,
                    :senha,
                    :userId,
                    :classeDeAcesso
                )"
            );
            
            $values['email'] = $email;  
            $values['senha'] = $password;  
            $values['userId'] = $cpf;  
            $values['classeDeAcesso'] = 1;  
                    
            $sql->execute($values);

            $sqlClient = $conexao->prepare(
                "INSERT INTO CLIENTES (
                    nome,
                    cpf,
                    tel
                ) VALUES (
                    :nome,
                    :cpf,
                    :tel
                )"
            );

            $valuesClient['nome'] = $email;  
            $valuesClient['tel'] = $password;  
            $valuesClient['cpf'] = $cpf;

            $sqlClient->execute($valuesClient);

            // Neste caso, é necessário verificar quantas tuplas foram afetadas antes
            // de qualquer return? Não, não vamos renderizar nada na tela.
            $conexao->commit();

            return TRUE;

        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

    /* 
    Cria um novo agendamento para o cliente
    */
    public static function createScheduling($cpf, $services_id, $data_solicitacao_servico, $data_realizacao_servico) {
        try {
            $conexao = Conexao::getConexao();
            
            $conexao->beginTransaction();

            $servicesIdLength = count($services_id);


            
            $sql = $conexao->prepare(
                "INSERT INTO CLIENTE_SOLICITA_SERVICO (
                    cpf_cliente,
                    id_servico,
                    data_solicitacao_servico,
                    data_realizacao_servico
                ) VALUES (
                    :clientCpf,
                    :serviceId,
                    :solicitationData,
                    :realizationData
                )"
            );

            $values['clientCpf'] = $cpf;  
            $values['serviceId'] = $services_id;  
            $values['solicitationData'] = $data_solicitacao_servico;  
            $values['realizationData'] = $data_realizacao_servico;  
                    
            $sql->execute($values);

            $lastId = $conexao->lastInsertId();

            $stmt = $conexao->prepare("SELECT * FROM CLIENTE_SOLICITA_SERVICO WHERE id = :id");
            $stmt->execute([':id' => $lastId]);

            $conexao->commit();

            return $stmt->fetchAll();

        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

    /* 
    Deleta um agendamento do cliente
    */
    public static function deleteScheduling($schedulingId) {
        try {
            $conexao = Conexao::getConexao();

            $sql = $conexao->prepare(
                "UPDATE CLIENTE_SOLICITA_SERVICO SET
                    active = FALSE
                WHERE id = :id"
            );

            $values['id'] = $schedulingId;
                    
            $sql->execute($values);

            return $sql->rowCount();

        } catch (Exception $e) {
            output(500, ["msg" => $e-getMessage()]);
        }
    }

    public static function checkIfExists($cpf) {
        try {
            $conexao = Conexao::getConexao();

            $sql = $conexao->prepare(
                "SELECT cpf FROM cliente WHERE cpf = :cpf");
            
            $values['cpf'] = $cpf;
            
            $sql->execute($values);

            return !empty($sql->fetchAll());

        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

}