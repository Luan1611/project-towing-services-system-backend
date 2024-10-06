<?php

require_once(__DIR__ . "/../configs/Database.php");
// Caso seja necessário acessar alguma função global auxiliar.
require_once(__DIR__ . "/../configs/utils.php");

class Exemplo
{
    // Método usado como exemplo apenas.
    public static function add($nome, $cidade)
    {
        return ["id" => 1, "nome" => $nome, "cidade" => $cidade];
    }
}
