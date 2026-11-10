<?php
class Conexao
{
    private static $conexao;

    private function __construct()
    {
    }

    public static function abrir()
    {
        if (self::$conexao === null) {
            $pasta = dirname(__DIR__);
            $conexao = new PDO('sqlite:' . $pasta . '/pdv.sqlite', null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $conexao->exec('PRAGMA busy_timeout = 5000');
            $conexao->exec('PRAGMA foreign_keys = ON');
            $conexao->exec(file_get_contents($pasta . '/banco.sql'));
            self::$conexao = $conexao;
        }

        return self::$conexao;
    }
}
