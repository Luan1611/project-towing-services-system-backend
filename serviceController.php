<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Service.php");

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

// Verifica se o código é composto por 2 caracteres,
// e se o mesmo não contém caracteres especiais
function validateCode($code) {
    if (!preg_match("/^[a-zA-Z0-9]{2}$/", $code)) {
        throw new Exception("Código de serviço inválido", 406);
    }
}

//Faz uma chamada para o Model verificar se já existe o código no BD
function checkIfCodeExists($code) {
    return Service::checkIfExists($code)["EXISTS"];
}

// Verifica se o tipo é composto por no mínimo 1 e no máximo 50 caracteres
function validateType($type) {
    $trimmedType = trim($type);
    if (!preg_match("/^.{1,50}$/", $trimmedType)) {
        throw new Exception("O tipo de serviço é inválido", 406);
    }
}

// Verifica se o preço é composto por, no máximo, 10 algarismos (sendo decimal ou inteiro),
// e se há apenas caracteres de 0 a 9 na string, aceitando um único ponto opcional
function validatePrice($price) {
    if (preg_match('/^\d+(\.\d+)?$/', $valor) && strlen(str_replace('.', '', $valor)) <= 10) {
        throw new Exception("O preço do serviço é inválido", 406);
    }
}

if(method("GET")) {
    if (!$data) {
        $data = $_GET;
    }

    try {
        // Lista todos os serviços
        $servicesList = Service::getServices();

        if (empty($servicesList)) {
            output(200, ["msg" => "Não há serviços para serem exibidos"]);
        }
        
        output(200, $servicesList);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

if (method("POST")) {
    if (!$data) {
        $data = $_POST;
    }

    try {
        validateParameters($data, ["codigo", "tipo", "preco"], 3);
        validateCode($data["codigo"]);
        validateType($data["codigo"]);
        validatePrice($data["preco"]);

        if (!empty(getService($data["codigo"]))) {
            $result = Service::setServiceAsActive($data["codigo"]);
        } else {
            $result = Service::createService($data["codigo"], $data["tipo"], $data["preco"]);
        }
        
        if(!$result) {
            throw new Exception("Não foi possível cadastrar o serviço", 500);
        }

        output(200, $result);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

if (method("DELETE")) {
    if (!$data) {
        $data = $_GET;
    }

    try {
        validateParameters($data, ["codigo"], 1);
        validateCode($data["codigo"]);

        if (!checkIfCodeExists($data["codigo"])) {
            throw new Exception("O serviço cuja deleção foi solicitada não existe no sistema", 404);
        }
        $result = Service::deleteService($data["codigo"]);

        if(!$result) {
            output(200, ["msg" => "Nenhum serviço foi deletado"]);
        }

        output(200, ["msg" => "Serviço deletado com sucesso!"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

output(404, ["msg" => "Método não suportado no momento"]);