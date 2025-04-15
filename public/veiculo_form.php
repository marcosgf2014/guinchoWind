<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Buscar clientes para o dropdown
$clientes = $conn->query('SELECT id, nome, endereco FROM clientes ORDER BY nome');
// Array de endereços para uso no JS
$clientes_enderecos = [];
$clientes->data_seek(0);
while($c = $clientes->fetch_assoc()) {
    $clientes_enderecos[$c['id']] = $c['endereco'];
}
$clientes->data_seek(0); // Volta para o início para o while do HTML

// Se for edição, busca dados do veículo
$veiculo = [
    'cliente_id'=>'','placa'=>'','marca'=>'','modelo'=>'','ano'=>'','cor'=>'','status'=>'','valor_servico'=>'','data_entrada'=>'','data_saida'=>'','origem'=>'','destino'=>''
];
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
    <div class="row g-2">
        <div class="col-md-6 mb-2">
            <label>Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-control" required>
                <option value="">Selecione...</option>
                <?php while($c = $clientes->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id']==$veiculo['cliente_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nome']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3 mb-2">
            <label>Placa</label>
            <input type="text" name="placa" class="form-control" value="<?= htmlspecialchars($veiculo['placa']) ?>" required>
        </div>
        <div class="col-md-3 mb-2">
            <label>Marca</label>
            <input type="text" name="marca" class="form-control" value="<?= htmlspecialchars($veiculo['marca']) ?>">
        </div>
        <div class="col-md-4 mb-2">
            <label>Modelo</label>
            <input type="text" name="modelo" class="form-control" value="<?= htmlspecialchars($veiculo['modelo']) ?>">
        </div>
        <div class="col-md-2 mb-2">
            <label>Ano</label>
            <input type="number" name="ano" class="form-control" value="<?= htmlspecialchars($veiculo['ano']) ?>">
        </div>
        <div class="col-md-2 mb-2">
            <label>Cor</label>
            <input type="text" name="cor" class="form-control" value="<?= htmlspecialchars($veiculo['cor']) ?>">
        </div>
        <div class="col-md-4 mb-2">
    <label>Status</label>
    <select name="status" class="form-control" required>
        <option value="">Selecione...</option>
        <option value="Em Andamento" <?= $veiculo['status']==='Em Andamento' ? 'selected' : '' ?>>Em Andamento</option>
        <option value="No Pátio" <?= $veiculo['status']==='No Pátio' ? 'selected' : '' ?>>No Pátio</option>
        <option value="Entregue" <?= $veiculo['status']==='Entregue' ? 'selected' : '' ?>>Entregue</option>
    </select>
</div>
        <div class="col-md-4 mb-2">
            <label>Valor do Serviço</label>
            <input type="number" step="0.01" name="valor_servico" class="form-control" value="<?= htmlspecialchars($veiculo['valor_servico']) ?>">
        </div>
        <div class="col-md-4 mb-2">
            <label>Data de Entrada e Hora</label>
            <?php
    $dtEntrada = '';
    if ($veiculo['data_entrada']) {
        $dtEntrada = date('Y-m-d\TH:i', strtotime($veiculo['data_entrada']));
    } else {
        $tz = new DateTimeZone('America/Sao_Paulo');
        $agora = new DateTime('now', $tz);
        $dtEntrada = $agora->format('Y-m-d\TH:i');
    }
?>
<input type="datetime-local" name="data_entrada" class="form-control" value="<?= $dtEntrada ?>">
        </div>
        <div class="col-md-4 mb-2">
            <label>Data de Saída e Hora</label>
            <input type="datetime-local" name="data_saida" class="form-control" value="<?= $veiculo['data_saida'] ? date('Y-m-d\TH:i', strtotime($veiculo['data_saida'])) : '' ?>">
        </div>
        <div class="col-md-4 mb-2">
            <label>Origem</label>
            <input type="text" name="origem" class="form-control" value="<?= htmlspecialchars($veiculo['origem']) ?>">
        </div>
        <div class="col-md-4 mb-2">
            <label>Destino</label>
            <input type="text" name="destino" id="destino" class="form-control" value="<?= htmlspecialchars($veiculo['destino']) ?>">
        </div>
        <div class="col-md-12 mb-2">
            <label>Obs:</label>
            <textarea name="obs" class="form-control" rows="2"><?= htmlspecialchars($veiculo['obs'] ?? '') ?></textarea>
        </div>
    </div>
    <div class="mt-3">
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="veiculos.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
</div>
<script>
// Array de endereços dos clientes do PHP para JS
const clientesEnderecos = <?php echo json_encode($clientes_enderecos, JSON_UNESCAPED_UNICODE); ?>;

// Preencher destino ao selecionar cliente
const clienteSelect = document.getElementById('cliente_id');
const destinoInput = document.getElementById('destino');
if (clienteSelect && destinoInput) {
    clienteSelect.addEventListener('change', function() {
        const id = this.value;
        destinoInput.value = clientesEnderecos[id] || '';
    });
    // Se for novo cadastro, já preenche destino se cliente estiver selecionado
    if (!<?= $id ? 'true' : 'false' ?>) {
        const id = clienteSelect.value;
        if (id && clientesEnderecos[id]) destinoInput.value = clientesEnderecos[id];
    }
}
</script>
</body>
</html>
