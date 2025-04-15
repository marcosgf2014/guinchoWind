<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cliente = ['nome'=>'','telefone'=>'','email'=>'','endereco'=>''];
if ($id) {
    $stmt = $conn->prepare('SELECT * FROM clientes WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows) $cliente = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Editar' : 'Novo' ?> Cliente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2><?= $id ? 'Editar' : 'Novo' ?> Cliente</h2>
    <form method="post" action="cliente_save.php">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($cliente['nome']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($cliente['telefone']) ?>">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($cliente['email']) ?>">
        </div>
        <div class="mb-3">
            <label>Endereço</label>
            <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($cliente['endereco']) ?>">
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="clientes.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
