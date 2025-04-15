<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    $stmt = $conn->prepare('DELETE FROM servicos WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
header('Location: servicos.php');
exit;
