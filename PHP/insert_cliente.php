<?php

require "conexao.php";
exigir_post();
exigir_login();

$nome     = campo('nome');
$cpf      = campo('cpf');
$email    = campo('email');
$telefone = campo('telefone');
$endereco = campo('endereco');

if ($nome === '') {
    voltar("Informe o nome do cliente.");
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    voltar("E-mail inválido.");
}

$stmt = $conn->prepare("INSERT INTO cliente (nome, cpf, email, telefone, endereco) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nome, $cpf, $email, $telefone, $endereco);
$stmt->execute();

ir_para("Cliente cadastrado com sucesso!", "../tela_cliente.html");
