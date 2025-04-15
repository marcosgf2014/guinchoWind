<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$servico = trim($_POST['servico']);
$data_servico = $_POST['data_servico'];
$descricao = trim($_POST['descricao']);
$valor = isset($_POST['valor']) ? floatval($_POST['valor']) : null;
$tipo_pagamento = trim($_POST['tipo_pagamento']);
$nota_fiscal = trim($_POST['nota_fiscal']);

if ($id) {
    $stmt = $conn->prepare('UPDATE servicos SET servico=?, data_servico=?, descricao=?, valor=?, tipo_pagamento=?, nota_fiscal=? WHERE id=?');
    $stmt->bind_param('ssssssi', $servico, $data_servico, $descricao, $valor, $tipo_pagamento, $nota_fiscal, $id);
    $stmt->execute();
} else {
    $stmt = $conn->prepare('INSERT INTO servicos (servico, data_servico, descricao, valor, tipo_pagamento, nota_fiscal) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssss', $servico, $data_servico, $descricao, $valor, $tipo_pagamento, $nota_fiscal);
    $stmt->execute();
}
header('Location: servicos.php');
exit;
