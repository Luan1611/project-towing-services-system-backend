<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Exemplo.php");

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

if (method("POST")) {
    if (!$data) {
        $data = $_POST;
    }

    try {
        
        //logica post aqui

        output(200, $resultado);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

if (method("DELETE")) {
    if (!$data) {
        $data = $_POST;
    }

    try {
        //logica delete aqui

        output(200, $resultado);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

if (method("GET")) {
    if (!$data) {
        $data = $_POST;
    }

    try {
        
       //logica get aqui

        output(200, $resultado);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}







output(404, ["msg" => "Método não suportado no momento"]);
