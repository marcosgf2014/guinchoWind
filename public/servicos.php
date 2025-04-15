<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$sql = 'SELECT * FROM servicos ORDER BY data_servico DESC, id DESC';
$servicos = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Serviços</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Serviços <a href="servico_form.php" class="btn btn-primary btn-sm">Novo Serviço</a></h2>
    <table class="table table-bordered table-striped">
        <thead><tr><th>Serviço</th><th>Data</th><th>Descrição</th><th>Valor</th><th>Tipo de Pagamento</th><th>Nota Fiscal</th><th>Ações</th></tr></thead>
        <tbody>
        <?php while($s = $servicos->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($s['servico']) ?></td>
            <td><?= htmlspecialchars(date('d/m/Y', strtotime($s['data_servico']))) ?></td>
            <td><?= htmlspecialchars($s['descricao']) ?></td>
            <td>R$ <?= number_format($s['valor'], 2, ',', '.') ?></td>
            <td><?= htmlspecialchars($s['tipo_pagamento']) ?></td>
            <td><?= htmlspecialchars($s['nota_fiscal']) ?></td>
            <td>
                <a href="servico_form.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="servico_delete.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary">Voltar ao Dashboard</a>
</div>
</body>
</html>
