<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$cliente_id = isset($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
$res = $conn->query("SELECT id, placa, modelo FROM veiculos WHERE cliente_id = $cliente_id ORDER BY placa");
$veiculos = [];
while($v = $res->fetch_assoc()) {
    $veiculos[] = $v;
}
header('Content-Type: application/json');
echo json_encode($veiculos);
