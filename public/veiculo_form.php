<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Buscar clientes para o dropdown
$clientes = $conn->query('SELECT id, nome FROM clientes ORDER BY nome');

// Se for edição, busca dados do veículo
$veiculo = ['cliente_id'=>'','placa'=>'','modelo'=>'','cor'=>'','ano'=>''];
if ($id) {
    $stmt = $conn->prepare('SELECT * FROM veiculos WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows) $veiculo = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Editar' : 'Novo' ?> Veículo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2><?= $id ? 'Editar' : 'Novo' ?> Veículo</h2>
    <form method="post" action="veiculo_save.php">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="mb-3">
            <label>Cliente</label>
            <select name="cliente_id" class="form-control" required>
                <option value="">Selecione...</option>
                <?php while($c = $clientes->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id']==$veiculo['cliente_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nome']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Placa</label>
            <input type="text" name="placa" class="form-control" value="<?= htmlspecialchars($veiculo['placa']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Modelo</label>
            <input type="text" name="modelo" class="form-control" value="<?= htmlspecialchars($veiculo['modelo']) ?>">
        </div>
        <div class="mb-3">
            <label>Cor</label>
            <input type="text" name="cor" class="form-control" value="<?= htmlspecialchars($veiculo['cor']) ?>">
        </div>
        <div class="mb-3">
            <label>Ano</label>
            <input type="number" name="ano" class="form-control" value="<?= htmlspecialchars($veiculo['ano']) ?>">
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="veiculos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
