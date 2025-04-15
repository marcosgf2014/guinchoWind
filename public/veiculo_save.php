<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$cliente_id = (int)$_POST['cliente_id'];
$placa = trim($_POST['placa']);
$marca = trim($_POST['marca']);
$modelo = trim($_POST['modelo']);
$ano = isset($_POST['ano']) ? (int)$_POST['ano'] : null;
$cor = trim($_POST['cor']);
$status = trim($_POST['status']);
$valor_servico = isset($_POST['valor_servico']) ? floatval($_POST['valor_servico']) : null;
$data_entrada = $_POST['data_entrada'] ? date('Y-m-d H:i:s', strtotime($_POST['data_entrada'])) : null;
$data_saida = $_POST['data_saida'] ? date('Y-m-d H:i:s', strtotime($_POST['data_saida'])) : null;
$origem = trim($_POST['origem']);
$destino = trim($_POST['destino']);
$obs = trim($_POST['obs'] ?? '');
if ($id) {
    $stmt = $conn->prepare('UPDATE veiculos SET cliente_id=?, placa=?, marca=?, modelo=?, ano=?, cor=?, status=?, valor_servico=?, data_entrada=?, data_saida=?, origem=?, destino=?, obs=? WHERE id=?');
    $stmt->bind_param('isssissdsssssi', $cliente_id, $placa, $marca, $modelo, $ano, $cor, $status, $valor_servico, $data_entrada, $data_saida, $origem, $destino, $obs, $id);
    $stmt->execute();
} else {
    $stmt = $conn->prepare('INSERT INTO veiculos (cliente_id, placa, marca, modelo, ano, cor, status, valor_servico, data_entrada, data_saida, origem, destino, obs) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('isssissdsssss', $cliente_id, $placa, $marca, $modelo, $ano, $cor, $status, $valor_servico, $data_entrada, $data_saida, $origem, $destino, $obs);
    $stmt->execute();
}
header('Location: veiculos.php');
exit;
