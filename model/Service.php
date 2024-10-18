<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Service {

    /*
    Retorna as informações dos serviços ofertados pelo guincheiro (prestador de serviço)
    */ 
    public static function getServices() {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT
                    id,
                    codigo,
                    tipo, 
                    preco,
                    active
                    FROM SERVICOS");

            $sql->execute();
            
            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    /* 
    Cria um novo serviço
    */

    //TODO: se eu tenho um serviço armazenado cujo valor do ACTIVE seja false,
    // e tento criar um serviço novo idêntico, eu preciso apenas atualizar a
    // coluna ACTIVE do serviço inativado para true?
    public static function createService($codigo, $tipo, $preco) {
        try {
            $conexao = Conexao::getConexao();

            $conexao->beginTransaction();

            $sql = $conexao->prepare(
                "INSERT INTO SERVICOS(
                    codigo,
                    tipo,
                    preco,
                    created_at,
                    updated_at,
                    active
                    ) VALUES (
                    :codigo,
                    :tipo,
                    :preco,
                    NOW(),
                    NOW(), 
                    TRUE
                    )");

            $values['codigo'] = $codigo;
            $values['tipo'] = $tipo;
            $values['preco'] = $preco;
                    
            $sql->execute($values);

            $lastId = $conexao->lastInsertId();

            $stmt = $conexao->prepare("SELECT * FROM SERVICOS WHERE id = :id");
            $stmt->execute([':id' => $lastId]);

            $conexao->commit();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    /* 
    Atualiza um serviço
    */
    public static function updateService($serviceId) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "UPDATE SERVICOS SET
                    codigo = :codigo,
                    tipo = :tipo,
                    preco = :preco,
                    updated_at = NOW()
                WHERE id = :serviceId");

            $values['codigo'] = $codigo;
            $values['tipo'] = $tipo;
            $values['preco'] = $preco;
            $values['serviceId'] = $serviceId;

            $sql->execute($values);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    /* 
    Deleta um serviço
    */
    public static function deleteService($serviceId) {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "UPDATE SERVICOS SET
                    active = FALSE,
                    updated_at = NOW()
                WHERE id = :serviceId");

            $values['serviceId'] = $serviceId;

            $sql->execute();

            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

}