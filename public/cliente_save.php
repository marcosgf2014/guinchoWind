<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nome = trim($_POST['nome']);
$telefone = trim($_POST['telefone']);
$email = trim($_POST['email']);
$endereco = trim($_POST['endereco']);
if ($id) {
    $stmt = $conn->prepare('UPDATE clientes SET nome=?, telefone=?, email=?, endereco=? WHERE id=?');
    $stmt->bind_param('ssssi', $nome, $telefone, $email, $endereco, $id);
    $stmt->execute();
} else {
    $stmt = $conn->prepare('INSERT INTO clientes (nome, telefone, email, endereco) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $nome, $telefone, $email, $endereco);
    $stmt->execute();
}
header('Location: clientes.php');
exit;
