<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Service {
    public static function checkIfExists($code) {
        try {
            $conexao = Conexao::getConexao();

            $sql = $conexao->prepare(
                "SELECT EXISTS(
                    SELECT
                        codigo
                    FROM SERVICOS
                    WHERE codigo IN(:code)
                    ) AS service_exists");

            $values["code"] = $code;

            $sql->execute($values);

            return $sql->fetch();

        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    //resolvida;
    public static function checkIfIdsExists($ids) {
        try {

            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare(
                "SELECT
                    EXISTS(
                    SELECT
                        id
                    FROM SERVICOS
                    WHERE id IN($placeholders)
                    ) AS services_ids_exists");

            $sql->execute($ids);

            //$sql->fetch() está retornando 0
            return $sql->fetch();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

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
    Retorna as informações dos serviços ofertados pelo guincheiro (prestador de serviço)
    */ 
    public static function getService($code) {
        try {
            $conexao = Conexao::getConexao();

            $sql = $conexao->prepare(
                "SELECT
                    codigo,
                    tipo, 
                    preco,
                    active
                FROM SERVICOS
                WHERE codigo = :codigo");

            $values['codigo'] = $code;

            $sql->execute($values);
            
            return $sql->fetchAll();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    /* 
    Cria um novo serviço
    */
    public static function createService($code, $type, $price) {
        try {
            $conexao = Conexao::getConexao();

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

            $values['codigo'] = $code;
            $values['tipo'] = $type;
            $values['preco'] = $price;
                    
            $sql->execute($values);

            $lastId = $conexao->lastInsertId();

            $stmt = $conexao->prepare("SELECT * FROM SERVICOS WHERE id = :id");

            $stmt->execute([':id' => $lastId]);

            return $stmt->fetch();
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
    public static function deleteService($code) {
        try {
            $conexao = Conexao::getConexao();

            $sql = $conexao->prepare(
                "UPDATE SERVICOS SET
                    active = FALSE,
                    updated_at = NOW()
                WHERE codigo = :serviceCode");

            $values['serviceCode'] = $code;

            $sql->execute($values);

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

    /* 
    Seta um serviço para ACTIVE
    */
    public static function setServiceAsActive($serviceCode) {

        try {
            $conexao = Conexao::getConexao();
            
            $sql = $conexao->prepare(
                "UPDATE SERVICOS SET
                    active = TRUE,
                    updated_at = NOW()
                WHERE codigo like '%:codigo%'");

            $values['codigo'] = $serviceCode;

            $sql->execute();

            return $sql->rowCount();
        } catch (Exception $e) {
            output(500, ["msg" => $e->getMessage()]);
        }
    }

}