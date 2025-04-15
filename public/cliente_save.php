<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nome = trim($_POST['nome']);
$cpf_cnpj = trim($_POST['cpf_cnpj']);
$telefone = trim($_POST['telefone']);
$email = trim($_POST['email']);
$endereco = trim($_POST['endereco']);
if ($id) {
    $stmt = $conn->prepare('UPDATE clientes SET nome=?, cpf_cnpj=?, telefone=?, email=?, endereco=? WHERE id=?');
    $stmt->bind_param('sssssi', $nome, $cpf_cnpj, $telefone, $email, $endereco, $id);
    $stmt->execute();
} else {
    $stmt = $conn->prepare('INSERT INTO clientes (nome, cpf_cnpj, telefone, email, endereco) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssss', $nome, $cpf_cnpj, $telefone, $email, $endereco);
    $stmt->execute();
}
header('Location: clientes.php');
exit;
