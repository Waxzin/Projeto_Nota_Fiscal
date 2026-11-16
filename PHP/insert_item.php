<?php
require "conexao.php";
exigir_post();
exigir_login();

$id_nota    = (int) campo('id_nota');
$id_produto = (int) campo('id_produto');
$quantidade = (int) campo('quantidade');

if ($id_nota <= 0 || $id_produto <= 0 || $quantidade <= 0) {
    voltar("Informe a nota, o produto e a quantidade.");
}


$stmt = $conn->prepare("SELECT id FROM nota WHERE id = ?");
$stmt->bind_param("i", $id_nota);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    voltar("Nota não encontrada.");
}

$stmt = $conn->prepare("SELECT preco FROM produto WHERE id = ?");
$stmt->bind_param("i", $id_produto);
$stmt->execute();
$produto = $stmt->get_result()->fetch_assoc();
if (!$produto) {
    voltar("Produto não encontrado.");
}
$preco = (float) $produto['preco'];


$conn->begin_transaction();

$stmt = $conn->prepare("UPDATE produto SET estoque = estoque - ? WHERE id = ? AND estoque >= ?");
$stmt->bind_param("iii", $quantidade, $id_produto, $quantidade);
$stmt->execute();
if ($stmt->affected_rows === 0) {
    $conn->rollback();
    voltar("Estoque insuficiente para este produto.");
}


$stmt = $conn->prepare("INSERT INTO item (id_nota, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiid", $id_nota, $id_produto, $quantidade, $preco);
$stmt->execute();


$stmt = $conn->prepare("UPDATE nota SET total = (SELECT SUM(quantidade * preco_unitario) FROM item WHERE id_nota = ?) WHERE id = ?");
$stmt->bind_param("ii", $id_nota, $id_nota);
$stmt->execute();

$conn->commit();

ir_para("Item adicionado à nota!", "../tela_nota.html");
