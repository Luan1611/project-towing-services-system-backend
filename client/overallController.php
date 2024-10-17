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

// Verifica se o nome é um nome válido
private function validateName($name) {
    $nameTrimmed = trim($name)
    $trimmedNameLength = strlen(nameTrimmed);
    $nameContainsNumericValues = preg_match('/[0-9]/', $name)
    $nameContainsSpecialCharacters = preg_match('/[,\;\[\]\(\)\{\}]/', $name)

    if ($trimmedNameLength === 0 || $nameContainsNumericValues || $nameContainsSpecialCharacters) {
        throw new Exception("Nome inválido", 400)
    }
}

//Verifica se o telefone tem ao menos 10 dígitos, sem zeros à esquerda
private function validatePhoneNumber($phoneNumber) {
    if (!preg_match('/^[0-9]{10,}$/', $phoneNumber)) {
        throw new Exception("Telefone Inválido", 406)
    }
}

if (method("POST")) {
    // Checa se o servidor receber algum dado JSON de entrada.
    if (!$data) {
        // Não recebeu, então recebe os dados via corpo normal do POST.
        $data = $_POST;
    }

    try {
        validateParameters($data, ["cpf", "nome", "telefone"], 3)
        validateName($data["nome"])
        validatePhoneNumber($data["telefone"])
        validateCPF($data["cpf"])

        $result = Client::createScheduling($data["cpf"], $data["nome"], $data["telefone"]);
        // Você pode configurar para o método retornar false ou similar caso haja erro ou problema...
        if (!$result) {
            // Houve algum erro inesperado no servidor.
            throw new Exception("Não foi possível cadastrar o agendamento", 500);
        }
        // Deu tudo certo, retorna o resultado da operação. A mensagem e o código HTTP podem variar conforme a necessidade
        output(200, ["msg" => "Agendamento criado com sucesso!"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// Como ficaria depois, com o token?
if(method("PUT")) {

    if (!$data) {
        $data = $_GET;
    }

    try {
        // Faz validações básicas de parâmetros
        validateParameters($data, ["cpf", "nome", "telefone"], 3)

        // Verifica se o nome é válido
        validateName($data["nome"])

        // Verifica se o telefone tem ao menos 10 dígitos, sem zeros à esquerda
        validatePhoneNumber($data["telefone"])

        // Verifica se o CPF é composto de 11 "dígitos" (caracteres)
        validateCPF($data["cpf"])

        // Verifica se o cpf do cliente está armazenado na base de dados
        if(!Client::checkIfExists($data["cpf"])) {
            throw new Exception("Usuário não encontrado", 400);
        }

        $res = Client::updateRegistrationData($data["cpf"], $data["nome"], $data["telefone"]);
        if(!$res) {
            throw new Exception("Não foi possível editar o usuário", 500);
        }
        output(200, ["msg" => "Usuário editado com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// É comum colocar uma resposta de erro caso o método ou operação solicitada não for encontrada.
output(404, ["msg" => "Método não suportado no momento"]);