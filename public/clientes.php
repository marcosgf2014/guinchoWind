<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$clientes = $conn->query('SELECT * FROM clientes ORDER BY nome');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Clientes <a href="cliente_form.php" class="btn btn-primary btn-sm">Novo Cliente</a></h2>
    <table class="table table-bordered table-striped">
        <thead><tr><th>Nome</th><th>CPF/CNPJ</th><th>Telefone</th><th>Email</th><th>Endereço</th><th>Ações</th></tr></thead>
        <tbody>
        <?php while($c = $clientes->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($c['nome']) ?></td>
            <td><?= htmlspecialchars($c['cpf_cnpj']) ?></td>
            <td><?= htmlspecialchars($c['telefone']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td><?= htmlspecialchars($c['endereco']) ?></td>
            <td>
                <a href="cliente_form.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="cliente_delete.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary">Voltar ao Dashboard</a>
</div>
</body>
</html>
