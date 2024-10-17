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

private function validateParameters($data, $arrayNamesAttributes, $inputsNumber) {
    if (!valid($data, $arrayNamesAttributes)) {
        throw new Exception("Parâmetros incorretos", 400);
    }
    if (count($data) != $inputsNumber) {
        throw new Exception("Foram enviados dados desconhecidos", 400);
    }
}

private function validateName($name) {
    $nameTrimmed = trim($name)
    $trimmedNameLength = strlen(nameTrimmed);
    $nameContainsNumericValues = preg_match('/[0-9]/', $name)
    $nameContainsSpecialCharacters = preg_match('/[,\;\[\]\(\)\{\}]/', $name)

    if ($trimmedNameLength === 0 || $nameContainsNumericValues || $nameContainsSpecialCharacters) {
        throw new Exception("Nome inválido", 400)
    }
}

private function validateCPF($cpf) {
    if (!preg_match('/^[0-9]{11}$/', $cpf)) {
        throw new Exception("CPF Inválido", 422)
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

        // Verifica se o CPF é composto de 11 "dígitos" (caracteres)
        validateCPF($data["cpf"])

        // Validação do formato das datas
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
            // Houve algum erro inesperado no servidor.
            throw new Exception("Erro de servidor", 500);
        }
        // Teremos que retornar os dados do novo agendamento em caso de sucesso?
        output(200, $result);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

if(method("DELETE")) {

    // Checa se o servidor receber algum dado JSON de entrada.
    if (!$data) {
        // Não recebeu, então recebe os dados via corpo normal do POST.
        $data = $_GET;
    }

    try {
        if(!$data) {
            throw new Exception("Nenhuma informação encontrada", 404);
        }
        if(!valid($data,["nome", "data_nascimento"])) {
            throw new Exception("Nome e/ou data_nascimento não encontrados", 404);
        }
        if(count($data) != 2) {
            throw new Exception("Foram enviados dados desconhecidos", 400);
        }

        output(200, ["msg" => "Usuário editado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// É comum colocar uma resposta de erro caso o método ou operação solicitada não for encontrada.
output(404, ["msg" => "Método não suportado no momento"]);