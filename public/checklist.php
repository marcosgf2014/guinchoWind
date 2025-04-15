<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$checklists = $conn->query('SELECT ch.id, v.placa, v.modelo, c.nome as cliente_nome, ch.criado_em FROM checklists ch JOIN veiculos v ON ch.veiculo_id = v.id JOIN clientes c ON v.cliente_id = c.id ORDER BY ch.criado_em DESC');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Checklists de Veículos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Checklists de Veículos <a href="checklist_form.php" class="btn btn-primary btn-sm">Novo Checklist</a></h2>
    <table class="table table-bordered table-striped">
        <thead><tr><th>ID</th><th>Cliente</th><th>Veículo</th><th>Placa</th><th>Data/Hora</th><th>Ações</th></tr></thead>
        <tbody>
        <?php while($ch = $checklists->fetch_assoc()): ?>
        <tr>
            <td><?= $ch['id'] ?></td>
            <td><?= htmlspecialchars($ch['cliente_nome']) ?></td>
            <td><?= htmlspecialchars($ch['modelo']) ?></td>
            <td><?= htmlspecialchars($ch['placa']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($ch['criado_em'])) ?></td>
            <td>
                <a href="checklist_view.php?id=<?= $ch['id'] ?>" class="btn btn-sm btn-info">Visualizar</a>
                <a href="checklist_form.php?id=<?= $ch['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="checklist_delete.php?id=<?= $ch['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este checklist?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary">Voltar ao Dashboard</a>
</div>
</body>
</html>
