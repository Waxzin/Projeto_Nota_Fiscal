<?php
require "conexao.php";
exigir_post();
exigir_login();

$id_cliente = (int) campo('id_cliente');
$data       = campo('data_emissao') ?: date('Y-m-d H:i:s');

if ($id_cliente <= 0) {
    voltar("Selecione o cliente da nota.");
}

$stmt = $conn->prepare("SELECT id FROM cliente WHERE id = ?");
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    voltar("Cliente não encontrado.");
}

$stmt = $conn->prepare("INSERT INTO nota (id_cliente, data_emissao) VALUES (?, ?)");
$stmt->bind_param("is", $id_cliente, $data);
$stmt->execute();
$id_nota = $conn->insert_id;

ir_para("Nota nº $id_nota criada! Agora adicione os itens.", "../tela_nota.html");
