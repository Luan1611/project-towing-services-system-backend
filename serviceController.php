<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Service.php");

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

private function validateParameters($data, $arrayNamesAttributes, $inputsNumber) {
    if (!valid($data, $arrayNamesAttributes)) {
        throw new Exception("Parâmetro(s) incorreto(s)", 400);
    }
    if (count($data) != $inputsNumber) {
        throw new Exception("Quantidade de parâmetros inválida", 406);
    }
}

// Verifica se o código é composto por 2 caracteres,
// e se o mesmo não contém caracteres especiais
private function validateCode($code) {
    if (!preg_match("/^[a-zA-Z0-9]{2}$/", $code)) {
        throw new Exception("Código de serviço inválido", 406)
    }
}

// Verifica se o tipo é composto por no mínimo 1 e no máximo 50 caracteres
private function validateType($type) {
    $trimmedType = trim($type)
    if (!preg_match("/^.{1,50}$/", $trimmedType)) {
        throw new Exception("O tipo de serviço é inválido", 406)
    }
}

// questão: a variável $price armazena uma string ou já um número?

// Verifica se o preço é composto por, no máximo, 10 algarismos (sendo decimal ou inteiro),
// e se há apenas caracteres de 0 a 9 na string, aceitando um único ponto opcional
private function validatePrice($price) {
    if (preg_match('/^\d+(\.\d+)?$/', $valor) && strlen(str_replace('.', '', $valor)) <= 10) {
        throw new Exception("O preço do serviço é inválido", 406)
    }
}


// Quais verificações realizar para obter todos os servicos para o prestador de servicos?
// parte de autenticação?
if(method("GET")) {
    try {
        // Listar todos os serviços
        $servicesList = Service::getServices();

        if (!empty($servicesList)) {
            output(200, $servicesList);
        }

        output(204, "Não há serviços para serem exibidos");
        
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

if (method("POST")) {
    // Checa se o servidor receber algum dado JSON de entrada.
    if (!$data) {
        // Não recebeu, então recebe os dados via corpo normal do POST.
        $data = $_POST;
    }

    try {
        validateParameters($data, ["codigo", "tipo", "preco"], 3)

        validateCode($data["codigo"])
        validateType($data["codigo"])
        validatePreco($data["preco"])

        $result = Service::createService($data["codigo"], $data["tipo"], $data["preco"]);
        
        if(!$result) {
            throw new Exception("Não foi possível cadastrar o serviço", 500);
        }
        output(200, ["msg" => "Serviço criado com sucesso!"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

if (method("DELETE")) {

    if (!$data) {
        $data = $_POST;
    }
    
    try {


        output(200, $result);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}



output(404, ["msg" => "Método não suportado no momento"]);