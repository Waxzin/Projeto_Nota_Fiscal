<?php

$host  = "localhost";
$user  = "root";
$pass  = "";         
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $banco);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    die("Erro ao conectar ao banco de dados.");
}



function voltar(string $msg): void {
    echo "<script>alert(" . json_encode($msg) . "); history.back();</script>";
    exit;
}

function ir_para(string $msg, string $url): void {
    echo "<script>alert(" . json_encode($msg) . "); window.location.href = " . json_encode($url) . ";</script>";
    exit;
}

function exigir_post(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../tela_login.html");
        exit;
    }
}


function exigir_login(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['id_usuario'])) {
        ir_para("Faça login para continuar.", "../tela_login.html");
    }
}

function campo(string $nome): string {
    return trim($_POST[$nome] ?? '');
}

function numero(string $valor): float {
    if (strpos($valor, ',') !== false) {
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
    }
    return (float) $valor;
}

set_exception_handler(function (Throwable $e) {
    error_log($e->getMessage());
    voltar("Erro ao salvar os dados. Tente novamente.");
});
