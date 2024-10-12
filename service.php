<?php

require_once(__DIR__ . "/configs/utils.php");
require_once(__DIR__ . "/model/Exemplo.php");

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = handleJSONInput();

if(method("GET")) {
    try {
        // Validações
        if(valid($_GET)) {
            if(!Usuario::exist($_GET["id"])) {
                throw new Exception("Usuário não encontrado", 400);
            }

        }
        // Listar todos os serviços
        

        $lista = Usuario::listar();

        output(200, $lista);
    } catch (Exception $e) {
        //throw $th;
    }
}

if (method("POST")) {
    try {
        if(!$data) {
            throw new Exception("Nenhuma informação encontrada", 404);
        }
        if(!valid($data, ["codigo", "tipo", "preco"])) {
            throw new Exception("Codigo e/ou tipo e/ou preco não encontrados", 404);
        }
        if(count($data) != 3) {
            throw new Exception("Quantidade de parâmetros inválida", 400);
        }



        // Todas as checagens possíveis.
        // ...



        $res = Service::createService($data["codigo"], $data["tipo"], $data["preco"]);
        if(!$res) {
            throw new Exception("Não foi possível cadastrar o serviço", 500);
        }
        output(200, ["msg" => "Usuário criado com sucesso"]);
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



output(404, ["msg" => "Método não suportado no momento"]);