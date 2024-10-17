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

private function validateCode($code) {
    
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
        throw new Exception("Nenhuma informação encontrada", 404);
    }
    $data = $_POST;

    try {
        //logica delete aqui

        output(200, $resultado);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}



output(404, ["msg" => "Método não suportado no momento"]);