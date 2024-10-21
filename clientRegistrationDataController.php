<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Client.php");
require_once(__DIR__ . "/model/Authentication.php");

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

// Verifica se o telefone tem ao menos 10 dígitos, e se é composto
// apenas por números
function validatePhoneNumber($phoneNumber) {
    if (!preg_match('/^[0-9]{10,}$/', $phoneNumber)) {
        throw new Exception("Telefone Inválido", 406);
    }
}

if(method("GET")) {
    if (!$data) {
        $data = $_GET;
    }

    try {
        validateParameters($data, ["cpf"], 1);
        validateCPF($data["cpf"]);
        
        $client = Client::getClientData($data["cpf"]);
        if ($client) {
            output(200, $client);
        }

        output(200, ["msg" => "Não há cliente com o cpf informado"]);

    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()] );
    }
}

if (method("POST")) {
    if (!$data) {
        $data = $_POST;
    }

    try {
        validateParameters($data, ["cpf", "nome", "telefone", "email", "senha"], 5);
        validateName($data["nome"]);
        validatePhoneNumber($data["telefone"]);
        validateCPF($data["cpf"]);

        if (Client::checkIfExists($data["cpf"])["cpf_exists"]) {
            throw new Exception("O CPF já existe. Cadastro não realizado.", 403);
        }

        if (Authentication::checkIfExists($data["email"])["client_email_exists"]) {
            throw new Exception("O e-mail já existe. Cadastro não realizado.", 403);
        }

        $result = Client::createAccount($data["email"], $data["senha"], $data["cpf"], $data["nome"], $data["telefone"]);

        if (!$result) {
            throw new Exception("Não foi possível realizar o cadastro", 500);
        }

        output(200, ["msg" => "Cliente criado com sucesso!"]);
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
        validateCPF($_GET["cpf"]);

        // Verifica se o cpf do cliente está armazenado na base de dados
        if(!Client::checkIfExists($_GET["cpf"])["cpf_exists"]) {
            throw new Exception("Usuário não encontrado", 404);
        }

        $res = Client::updateRegistrationData($_GET["cpf"], $data["nome"], $data["telefone"]);

        if(!$res) {
            throw new Exception("Nenhum dado do usuário foi modificado", 500);
        }

        output(200, ["msg" => "Dados cadastrais do usuário editados com sucesso"]);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

output(404, ["msg" => "Método não suportado no momento"]);