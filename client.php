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


// Verifica se o método é do tipo POST.
if (method("POST")) {
    // Checa se o servidor receber algum dado JSON de entrada.
    if (!$data) {
        // Não recebeu, então recebe os dados via corpo normal do POST.
        $data = $_POST;
    }

    try {
        if (!valid($data, ["cpf", "nome", "telefone"])) {
            throw new Exception("Parâmetros incorretos", 400);
        }
        if (count($data) != 3) {
            throw new Exception("Foram enviados dados desconhecidos", 400);
        }




        // Outra verificações que fossem desejadas....

        //Verificando se o telefone tem ao menos 10 "dígitos" (caracteres)
        if (!preg_match('/^[0-9]{10,}$/', $data["telefone"]))
            throw new Exception("CPF Inválido", 422)
        // Validando o CPF
        if (!preg_match('/^[0-9]{11}$/', $data["cpf"])) {
            throw new Exception("CPF Inválido", 422)
        }

        // Realiza a operação desejada
        $resultado = Exemplo::add($data["nome"], $data["cidade"]);
        // Você pode configurar para o método retornar false ou similar caso haja erro ou problema...
        if (!$resultado) {
            // Houve algum erro inesperado no servidor.
            throw new Exception("Erro de servidor", 500);
        }
        // Deu tudo certo, retorna o resultado da operação. A mensagem e o código HTTP podem variar conforme a necessidade
        output(200, $resultado);
    } catch (Exception $e) {
        output($e->getCode(), ["msg" => $e->getMessage()]);
    }
}

// É comum colocar uma resposta de erro caso o método ou operação solicitada não for encontrada.
output(404, ["msg" => "Método não suportado no momento"]);
