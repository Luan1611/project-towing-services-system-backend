<?php

// Arquivos com funções úteis que vão ser usadas nesta rota.
require_once(__DIR__ . "/configs/utils.php");
// Arquivos com as entidades (models) que vão ser usadas nesta rota.
require_once(__DIR__ . "/model/Contractor.php");

// Bloco de código configurando o servidor. Remover os métodos que não forem suportados.
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Usado para receber os dados brutos do corpo da requisição.
// Caso não tenha sido enviado nada no formato JSON, retorna FALSE.
$data = handleJSONInput();

// Verifica se os CPFs dos clientes são válidos
private function validateCPFs($cpfsArray) {
    foreach ($cpfsArray as $cpf) {
        if (!preg_match('/^[0-9]{11}$/', $cpf)) {
            throw new Exception("A lista de CPFs contém um ou mais CPFs inválidos", 422)
        }
    }
}

// Lista todos os agendamentos de serviços solicitados pelos clientes, por data
if(method("GET")) {
    if (!$data) {
        // Não recebeu, então recebe os dados via corpo normal do GET.
        $data = $_GET;
    }

    try {
        $list = Contractor::getClientServicesSchedulings();

        if (empty($list)) {
            output(204, ["msg" => "Não há agendamentos de clientes para serem exibidos."]);
        }

        output(200, $list);
    } catch (Exception $e) {
        throw new Exception("Não foi possível recuperar os dados dos agendamentos", 500)
    }
}

if(method("DELETE")) {
    // Checa se o servidor receber algum dado JSON de entrada.
    if (!$data) {
        $data = $_GET;
    }

    try {
        validateParameters($data, ["cpfs", "date"], 2)
        validateCPFs($data["cpfs"])
        validateDate($data["date"])

        output(204, ["msg" => "Agendamentos de clientes deletados com sucesso!"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// É comum colocar uma resposta de erro caso o método ou operação solicitada não for encontrada.
output(404, ["msg" => "Método não suportado no momento"]);