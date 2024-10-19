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
    if (!$data) {
        $data = $_POST;
    }

    try {
        validateParameters($data, ["cpf", "nome", "telefone", "email", "senha"], 5)
        validateName($data["nome"])
        validatePhoneNumber($data["telefone"])
        validateCPF($data["cpf"])

        
        if (Client::checkIfExists($data["cpf"])) {
            throw new Exception("O CPF já existe. Cadastro não realizado.", 404);
        }

        if (Authentication::checkIfExists($data["email"])) {
            throw new Exception("O e-mail já existe. Cadastro não realizado.", 404);
        }

        $result = Client::createAccount($data["email"], $data["senha"], $data["cpf"], $data["nome"], $data["telefone"]);

        if (!$result) {
            throw new Exception("Não foi possível realizar o cadastro", 500);
        }

        output(200, ["msg" => "Agendamento criado com sucesso!"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

if(method("PUT")) {

    if (!$data) {
        $data = $_POST;
    }

    try {
        validateParameters($_GET, ["cpf"], 1);
        validateParameters($data, ["nome", "telefone"], 2);
        validateName($data["nome"]);
        validatePhoneNumber($data["telefone"]);
        validateCPF($data["cpf"]);

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