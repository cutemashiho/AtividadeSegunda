<?php
function texto($dados, $campo)
{
    return isset($dados[$campo]) && is_string($dados[$campo]) ? trim($dados[$campo]) : '';
}

function inteiro($dados, $campo, $minimo = 1)
{
    return filter_var(texto($dados, $campo), FILTER_VALIDATE_INT, [
        'options' => ['min_range' => $minimo, 'max_range' => 2147483647]
    ]);
}

function campoValido($valor, $limite)
{
    return $valor !== '' && mb_strlen($valor, 'UTF-8') <= $limite;
}

function dataValida($data)
{
    $dia = DateTime::createFromFormat('!Y-m-d', $data);
    return $dia && $dia->format('Y-m-d') === $data && substr($data, 0, 4) >= '1000';
}

function voltar($tela, $mensagem = '', $erro = '', $parametros = [])
{
    $_SESSION['mensagem'] = $mensagem;
    $_SESSION['erro'] = $erro;
    header('Location: index.php?' . http_build_query(['tela' => $tela] + $parametros), true, 303);
    exit;
}
