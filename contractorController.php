<?php

// Arquivos com funções úteis que vão ser usadas nesta rota.
require_once(__DIR__ . "/configs/utils.php");
// Arquivos com as entidades (models) que vão ser usadas nesta rota.
require_once(__DIR__ . "/model/Exemplo.php");

// Bloco de código configurando o servidor. Remover os métodos que não forem suportados.
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Usado para receber os dados brutos do corpo da requisição.
// Caso não tenha sido enviado nada no formato JSON, retorna FALSE.
$data = handleJSONInput();

private function validateParameters($data, $arrayNamesAttributes, $inputsNumber) {
    if (!valid($data, $arrayNamesAttributes)) {
        throw new Exception("Parâmetros incorretos", 400);
    }
    if (count($data) != $inputsNumber) {
        throw new Exception("Foram enviados dados desconhecidos", 400);
    }
}

private function validateCPFs($cpfsArray) {
    foreach ($cpfsArray as $cpf) {
        if (!preg_match('/^[0-9]{11}$/', $cpf)) {
            throw new Exception("A lista de CPFs contém um ou mais CPFs inválidos", 422)
        }
    }
}

private function validateDate($date) {
    // Regex para validar o formato YYYY-MM-DD
    $dateFormatRegex = '/^\d{4}-\d{2}-\d{2}$/';

    if (!preg_match($dateFormatRegex, $data)) {
        throw new Exception("Formato de Data inválido", 400)
    }

    // Fazendo destructuring da data e armazenando em variáveis.
    [$ano, $mes, $dia] = explode('-', $date)

    // Checando se a data é uma data válida
    if (!checkdate($mes, $dia, $ano)) {
        throw new Exception("Data inválida", 400)
    }
}

// Lista todos os agendamentos de serviços solicitados pelos clientes, por data
if(method("GET")) {
    try {
        
        $list = Contractor::getClientServicesSchedulings();

        if (empty($list)) {
            output(204, ["msg" => "Nenhum agendamento encontrado."]);
        }

        output(200, $list);
    } catch (Exception $e) {
        throw new Exception("Não foi possível recuperar os dados dos agendamentos", 500)
    }
}

if(method("DELETE")) {

    // Checa se o servidor receber algum dado JSON de entrada.
    if (!$data) {
        // Não recebeu, então recebe os dados via corpo normal do POST.
        $data = $_GET;
    }

    try {
        // Faz validações básicas de parâmetros
        validateParameters($data, ["cpfs", "date"], 2)

        // Verifica se os CPFs dos clientes são válidos
        validateCPFs($data["cpfs"])

        //Verifica se a data é válida
        validateDate($data["date"])

        //TODO: retornar objeto JSON
        output(200, ["msg" => "Agendamentos de clientes deletados com sucesso!"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}


// É comum colocar uma resposta de erro caso o método ou operação solicitada não for encontrada.
output(404, ["msg" => "Método não suportado no momento"]);
