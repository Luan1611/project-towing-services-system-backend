<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Scheduling.php");

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

if(method("GET")) {
    if (!$data) {
        $data = $_GET;
    }

    try {
        $schedulingsList = Scheduling::getSchedulings();

        if (empty($schedulingsList)) {
            output(200, ["msg" => "Não há agendamentos para serem exibidos"]);
        }
        
        output(200, $schedulingsList);
    } catch (Exception $e) {
        throw new Exception("Não foi possível recuperar os dados dos agendamentos", 500);
    }
}

output(404, ["msg" => "Método não suportado no momento"]);