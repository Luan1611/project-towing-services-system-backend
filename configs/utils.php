<?php

// Faz validações básicas de parâmetros
function validateParameters($data, $arrayNamesAttributes, $inputsNumber) {
    if (!valid($data, $arrayNamesAttributes)) {
        throw new Exception("Parâmetro(s) incorreto(s)", 400);
    }
    if (count($data) != $inputsNumber) {
        throw new Exception("Quantidade de parâmetros inválida", 406);
    }
}

//Verifica se a data é válida (formato e valores)
function validateDate($date) {
    // Regex para validar o formato YYYY-MM-DD
    $dateFormatRegex = '/^\d{4}-\d{2}-\d{2}$/';

    if (!preg_match($dateFormatRegex, $data)) {
        throw new Exception("Formato de Data inválido", 400)
    }

    // Fazendo Destructuring da data e armazenando em três variáveis.
    [$ano, $mes, $dia] = explode('-', $date)

    // Checando se a data é uma data válida
    if (!checkdate($mes, $dia, $ano)) {
        throw new Exception("Data inválida", 400)
    }
}

// Verifica se o CPF é composto de 11 "dígitos" (caracteres)
private function validateCPF($cpf) {
    if (!preg_match('/^[0-9]{11}$/', $cpf)) {
        throw new Exception("CPF Inválido", 422)
    }
}

// Exemplo: valid("POST", ["id", "nome", "ano"]);
function valid($metodo, $lista) {
    $obtidos = array_keys($metodo);
    $nao_encontrados = array_diff($lista, $obtidos);
    if (empty($nao_encontrados)) {
        foreach ($lista as $p) {
            if (empty(trim($metodo[$p]))) {
                return false;
            }
        }
        return true;
    }
    return false;
}

// Exemplo: method("PUT");
function method($metodo) {
    if (!strcasecmp($_SERVER['REQUEST_METHOD'], $metodo)) {
        return true;
    }
    return false;
}

// Exemplo: output(201, ["msg" => "Cadastrado com sucesso"]);
function output($codigo, $msg) {
    http_response_code($codigo);
    echo json_encode($msg);
    exit;
}

// Retorna os dados parseados (se houver JSON na entrada) ou false.
function handleJSONInput() {
    try {
        $json = file_get_contents('php://input');
        $json = json_decode($json, true);
        if ($json == null) {
            throw new Exception("JSON não enviado", 0);
        }
        return $json;
    } catch (Exception $e) {
        return false;
    }
}