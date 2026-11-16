<?php

require "conexao.php";
exigir_post();

$usuario  = campo('usuario');
$email    = campo('email');
$nome     = campo('nome') ?: $usuario;           
$senha    = $_POST['senha'] ?? '';
$confirma = $_POST['confirmar_senha'] ?? $senha; 

if ($usuario === '' || $email === '' || $senha === '') {
    voltar("Preencha todos os campos.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    voltar("E-mail inválido.");
}
if (strlen($senha) < 6) {
    voltar("A senha precisa ter pelo menos 6 caracteres.");
}
if ($senha !== $confirma) {
    voltar("As senhas não conferem.");
}


$stmt = $conn->prepare("SELECT id FROM usuario WHERE usuario = ? OR email = ?");
$stmt->bind_param("ss", $usuario, $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    voltar("Usuário ou e-mail já cadastrado.");
}


$hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO usuario (nome, usuario, email, senha) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $usuario, $email, $hash);
$stmt->execute();

ir_para("Conta criada com sucesso! Faça login.", "../tela_login.html");
