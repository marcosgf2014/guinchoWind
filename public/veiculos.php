<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$query = 'SELECT v.*, c.nome AS cliente_nome FROM veiculos v JOIN clientes c ON v.cliente_id = c.id ORDER BY v.id DESC';
$veiculos = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Veículos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Veículos <a href="veiculo_form.php" class="btn btn-primary btn-sm">Novo Veículo</a></h2>
    <table class="table table-bordered table-striped">
        <thead><tr><th>Cliente</th><th>Placa</th><th>Marca</th><th>Modelo</th><th>Ano</th><th>Cor</th><th>Status</th><th>Valor do Serviço</th><th>Data Entrada</th><th>Data Saída</th><th>Origem</th><th>Destino</th><th>Ações</th></tr></thead>
        <tbody>
        <?php while($v = $veiculos->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($v['cliente_nome']) ?></td>
            <td><?= htmlspecialchars($v['placa']) ?></td>
            <td><?= htmlspecialchars($v['marca']) ?></td>
            <td><?= htmlspecialchars($v['modelo']) ?></td>
            <td><?= htmlspecialchars($v['ano']) ?></td>
            <td><?= htmlspecialchars($v['cor']) ?></td>
            <td><?= htmlspecialchars($v['status']) ?></td>
            <td>R$ <?= number_format($v['valor_servico'], 2, ',', '.') ?></td>
            <td><?= $v['data_entrada'] ? date('d/m/Y H:i', strtotime($v['data_entrada'])) : '' ?></td>
            <td><?= $v['data_saida'] ? date('d/m/Y H:i', strtotime($v['data_saida'])) : '' ?></td>
            <td><?= htmlspecialchars($v['origem']) ?></td>
            <td><?= htmlspecialchars($v['destino']) ?></td>
            <td>
                <a href="veiculo_form.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="veiculo_delete.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary">Voltar ao Dashboard</a>
</div>
</body>
</html>
