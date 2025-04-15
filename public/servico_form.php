<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$servico = [
    'servico'=>'','data_servico'=>date('Y-m-d'),'descricao'=>'','valor'=>'','tipo_pagamento'=>'','nota_fiscal'=>''
];
if ($id) {
    $stmt = $conn->prepare('SELECT * FROM servicos WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows) $servico = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Editar' : 'Novo' ?> Serviço</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script>
    function buscarVeiculos(clienteId) {
        fetch('veiculos_cliente.php?cliente_id=' + clienteId)
        .then(resp => resp.json())
        .then(data => {
            let select = document.getElementById('veiculo_id');
            select.innerHTML = '<option value="">Selecione...</option>';
            data.forEach(function(v) {
                select.innerHTML += `<option value="${v.id}">${v.placa} - ${v.modelo}</option>`;
            });
        });
    }
    </script>
</head>
<body>
<div class="container mt-4">
    <h2><?= $id ? 'Editar' : 'Novo' ?> Serviço</h2>
    <form method="post" action="servico_save.php">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="mb-3">
            <label>Serviço</label>
            <input type="text" name="servico" class="form-control" value="<?= htmlspecialchars($servico['servico']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Data</label>
            <input type="date" name="data_servico" class="form-control" value="<?= htmlspecialchars($servico['data_servico']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Descrição</label>
            <textarea name="descricao" class="form-control" rows="2"><?= htmlspecialchars($servico['descricao']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Valor</label>
            <input type="number" step="0.01" name="valor" class="form-control" value="<?= htmlspecialchars($servico['valor']) ?>">
        </div>
        <div class="mb-3">
            <label>Tipo de Pagamento</label>
            <input type="text" name="tipo_pagamento" class="form-control" value="<?= htmlspecialchars($servico['tipo_pagamento']) ?>" placeholder="Dinheiro, Cartão, Pix, etc">
        </div>
        <div class="mb-3">
            <label>Nota Fiscal</label>
            <input type="text" name="nota_fiscal" class="form-control" value="<?= htmlspecialchars($servico['nota_fiscal']) ?>" placeholder="Número da NF ou Sim/Não">
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="servicos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
