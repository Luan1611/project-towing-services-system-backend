<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Client.php");
require_once(__DIR__ . "/model/Service.php");
require_once(__DIR__ . "/model/Scheduling.php");

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

function validateServicesIds($servicesIds) {
    $servicesIdsArrayLength = count($servicesIds);

    if (!$servicesIdsArrayLength) {
        throw new Exception("Ids dos serviços não encontrados", 400);
    }
    foreach ($servicesIds as $serviceId) {
		if (!is_int($serviceId) || $serviceId <= 0) {
            throw new Exception("Id(s) com formato inválido", 400);
        }
	}
}

function validateSchedulingId($schedulingId) {
    if (!preg_match('/[0-9]/', $schedulingId)) {
        throw new Exception("Id de agendamento Inválido", 422);
    }
}


//TODO: Método GET para carregar os agendamentos do cliente quando o mesmo ir
// para a sua página Meus Agendamentos (slide 7)


if (method("POST")) {
    if (!$data) {
        $data = $_POST;
    }

    try {
        validateParameters(
            $data,
            ["cpf", "services_ids", "data_solicitacao_servico", "data_realizacao_servico"],
            4
        );
        validateCPF($data["cpf"]);
        validateDate($data["data_solicitacao_servico"]);
        validateDate($data["data_realizacao_servico"]);

        validateServicesIds($data["services_ids"]);
        
        if (!Client::checkIfExists($data["cpf"])["cpf_exists"]) {
            throw new Exception("Agendamento não criado, pois o CPF solicitante para tal agendamento não existe", 422);
        }

        if (!Service::checkIfIdsExists($data["services_ids"])["services_ids_exists"]) {
            throw new Exception("Agendamento não criado, pois o(s) Id(s) do(s) serviço(s)informado(s) para tal agendamento não existe/existem)", 422);
        }

        $result = Client::createScheduling(
            $data["cpf"],
            $data["services_ids"],
            $data["data_solicitacao_servico"],
            $data["data_realizacao_servico"]
        );


        if (!$result) {
            throw new Exception("Agendamento não criado, em razão de algum erro do servidor. Entre em contato com suporte", 500);
        }

        // Como retornar os agendamentos criados? aqui estou retornando
        // a quantidade de tuplas afetadas apenas
        output(200, $result);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

if(method("DELETE")) {

   if ($data) {
        output(500, ["msg" => "Metodo DELETE não aceita dados contidos no corpo da requisição (body)"]);
    }

    $data = $_GET;

    try {
        validateParameters($data, ["id"], 1);
        validateSchedulingId($data["id"]);

        if (!Scheduling::checkIfExists($data["id"])["scheduling_exists"]) {
            throw new Exception("Agendamento não deletado, pois o Id do agendamento para tal deleção não existe)", 422);
        }

        $result = Client::deleteScheduling($data, ["id"]);

        output(200, ["msg" => "Agendamento deletado com sucesso!"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// É comum colocar uma resposta de erro caso o método ou operação solicitada não for encontrada.
output(404, ["msg" => "Método não suportado no momento"]);