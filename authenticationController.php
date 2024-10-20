<?php

// Arquivos com funções úteis que vão ser usadas nesta rota.
require_once(__DIR__ . "/configs/utils.php");
// Arquivos com as entidades (models) que vão ser usadas nesta rota.
require_once(__DIR__ . "/model/Authentication.php");

// Bloco de código configurando o servidor. Remover os métodos que não forem suportados.
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Usado para receber os dados brutos do corpo da requisição.
// Caso não tenha sido enviado nada no formato JSON, retorna FALSE.
$data = handleJSONInput();


if(method("GET")) {
    if (!$data) {
        // Não recebeu, então recebe os dados via corpo normal do GET.
        $data = $_GET;
    }

    try {
        //TODO
    } catch (Exception $e) {
        throw new Exception("Não foi possível recuperar os dados dos agendamentos", 500);
    }
}


if(method("DELETE")) {
    // Checa se o servidor receber algum dado JSON de entrada.
    if (!$data) {
        //TODO
    }

    try {
        //TODO
        //TODO: retornar objeto JSON?
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// É comum colocar uma resposta de erro caso o método ou operação solicitada não for encontrada.
output(404, ["msg" => "Método não suportado no momento"]);