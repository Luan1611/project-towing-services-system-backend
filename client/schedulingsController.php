<?php

// Arquivos com funções úteis que vão ser usadas nesta rota.
require_once(__DIR__ . "/configs/utils.php");
// Arquivos com as entidades (models) que vão ser usadas nesta rota.
require_once(__DIR__ . "/model/Client.php");

// Bloco de código configurando o servidor. Remover os métodos que não forem suportados.
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Usado para receber os dados brutos do corpo da requisição.
// Caso não tenha sido enviado nada no formato JSON, retorna FALSE.
$data = handleJSONInput();

private function validateName($name) {
    $nameTrimmed = trim($name)
    $trimmedNameLength = strlen($nameTrimmed);
    $nameContainsNumericValues = preg_match('/[0-9]/', $name)
    $nameContainsSpecialCharacters = preg_match('/[,\;\[\]\(\)\{\}]/', $name)

    $isInvalidName =
        $trimmedNameLength === 0
        || $nameContainsNumericValues
        || $nameContainsSpecialCharacters;

    if ($isInvalidName) {
        throw new Exception("Nome inválido", 400)
    }
}

//$servicesId deve ser um array indexado
private function validateServicesIds($servicesIds) {
    $servicesIdsArrayLength = count(servicesIds)
    if (!$servicesIdsArrayLength) {
        throw new Exception("Ids dos serviços não encontrados", 400)
    }
    foreach ($servicesIds as $serviceId) {
		if (!is_int($serviceId)) {
            throw new Exception("Id(s) com formato inválido", 400)
        }
	}
}

private function validateSchedulingId($schedulingId){
    if (!preg_match('/^[0-9]$/', $schedulingId)) {
        throw new Exception("Id de agendamento Inválido", 422)
    }
}

if (method("POST")) {
    // Checa se o servidor receber algum dado JSON de entrada.
    if (!$data) {
        // Não recebeu, então recebe os dados via corpo normal do POST.
        $data = $_POST;
    }

    // 'services_ids' tem que ser um array (de números inteiros)
    try {
        validateParameters(
            $data,
            ["cpf", "services_ids", "data_solicitacao_servico", "data_realizacao_servico"],
            3
        )
        validateCPF($data["cpf"])
        validateDate($data["data_solicitacao_servico"])
        validateDate($data["data_realizacao_servico"])

        validateServicesIds("services_ids")

        //services_id tem que ser um array com os ids dos serviços solicitados
        $result = Client::createScheduling(
            $data["cpf"],
            $data["services_ids"],
            $data["data_solicitacao_servico"],
            $data["data_realizacao_servico"]
        );

        if (!$result) {
            throw new Exception("Não foi possível cadastrar o agendamento", 500);
        }

        output(200, $result);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

if(method("DELETE")) {
    // Checa se o servidor receber algum dado JSON de entrada.
    if (!$data) {
        $data = $_GET;
    }

    try {
        validateParameters($data, ["id"])
        validateSchedulingId($data, ["id"])

        $result = Client::deleteScheduling($data, ["id"])

        output(200, ["msg" => "Agendamento deletado com sucesso!"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// É comum colocar uma resposta de erro caso o método ou operação solicitada não for encontrada.
output(404, ["msg" => "Método não suportado no momento"]);