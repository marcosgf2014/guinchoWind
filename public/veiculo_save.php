<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$cliente_id = (int)$_POST['cliente_id'];
$placa = trim($_POST['placa']);
$modelo = trim($_POST['modelo']);
$cor = trim($_POST['cor']);
$ano = isset($_POST['ano']) ? (int)$_POST['ano'] : null;
if ($id) {
    $stmt = $conn->prepare('UPDATE veiculos SET cliente_id=?, placa=?, modelo=?, cor=?, ano=? WHERE id=?');
    $stmt->bind_param('isssii', $cliente_id, $placa, $modelo, $cor, $ano, $id);
    $stmt->execute();
} else {
    $stmt = $conn->prepare('INSERT INTO veiculos (cliente_id, placa, modelo, cor, ano) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('isssi', $cliente_id, $placa, $modelo, $cor, $ano);
    $stmt->execute();
}
header('Location: veiculos.php');
exit;
