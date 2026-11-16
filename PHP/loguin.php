<?php
require "conexao.php";
exigir_post();

$login  = campo('usuario');          
$senha  = $_POST['senha'] ?? '';
$manter = isset($_POST['manter']);   

if ($login === '' || $senha === '') {
    voltar("Informe usuário e senha.");
}

$stmt = $conn->prepare("SELECT id, nome, senha FROM usuario WHERE usuario = ? OR email = ? LIMIT 1");
$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || !password_verify($senha, $user['senha'])) {
    voltar("Usuário ou senha incorretos.");
}


if ($manter) {
    $segundos = 60 * 60 * 24 * 30;
    ini_set('session.gc_maxlifetime', (string) $segundos);
    session_set_cookie_params([
        'lifetime' => $segundos,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

session_start();
session_regenerate_id(true);
$_SESSION['id_usuario'] = $user['id'];
$_SESSION['nome']       = $user['nome'];

header("Location: ../tela_opcao.html");
exit;
