<?php
require "conexao.php";
exigir_post();
exigir_login();

$nome      = campo('nome');
$descricao = campo('descricao');
$preco     = numero(campo('preco'));
$estoque   = (int) campo('estoque');

if ($nome === '') {
    voltar("Informe o nome do produto.");
}
if ($preco <= 0) {
    voltar("Informe um preço válido.");
}
if ($estoque < 0) {
    voltar("O estoque não pode ser negativo.");
}

$stmt = $conn->prepare("INSERT INTO produto (nome, descricao, preco, estoque) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssdi", $nome, $descricao, $preco, $estoque);
$stmt->execute();

ir_para("Produto cadastrado com sucesso!", "../tela_produto.html");
