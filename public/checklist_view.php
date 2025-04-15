<?php
require_once '../includes/db.php';
$conn = getDbConnection();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { echo 'Checklist não encontrado.'; exit; }
$check = $conn->prepare('SELECT ch.*, v.placa, v.modelo, c.nome as cliente_nome FROM checklists ch JOIN veiculos v ON ch.veiculo_id = v.id JOIN clientes c ON v.cliente_id = c.id WHERE ch.id=?');
$check->bind_param('i', $id);
$check->execute();
$res = $check->get_result();
if (!$res->num_rows) { echo 'Checklist não encontrado.'; exit; }
$data = $res->fetch_assoc();
$itens = json_decode($data['itens'] ?? '{}', true);
$fotos = json_decode($data['fotos'] ?? '[]', true);
?><!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Checklist</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .tab-content { background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid #ddd; }
        .tab-pane label { font-weight: 500; }
        .img-preview { max-width: 120px; margin: 0 8px 8px 0; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>Checklist de Veículo #<?= $data['id'] ?></h2>
    <div class="alert alert-info mb-3">
        <b><?= htmlspecialchars($data['modelo']) ?> - Placa: <?= htmlspecialchars($data['placa']) ?></b><br>
        Cliente: <?= htmlspecialchars($data['cliente_nome']) ?> <span class="ms-3">Entrada: <?= date('d/m/Y H:i', strtotime($data['criado_em'])) ?></span>
    </div>
    <ul class="nav nav-tabs mb-3" id="tabChecklist" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#geral" type="button">Geral</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#detalhes" type="button">Detalhes</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#fotos" type="button">Fotos</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#assinaturas" type="button">Assinaturas</button></li>
    </ul>
    <div class="tab-content mb-3">
        <!-- Aba Geral -->
        <div class="tab-pane fade show active" id="geral">
            <div class="row mb-2">
                <div class="col-md-4"><b>Quilometragem:</b> <?= htmlspecialchars($data['quilometragem']) ?></div>
                <div class="col-md-4"><b>Nível de Combustível:</b> <?= htmlspecialchars($data['combustivel']) ?></div>
                <div class="col-md-4"><b>Danos Visíveis:</b> <?= $data['danos_visiveis'] ? 'Sim' : 'Não' ?></div>
            </div>
            <div class="mb-2"><b>Pertences:</b><br><?= nl2br(htmlspecialchars($data['pertences'])) ?></div>
            <div class="mb-2"><b>Observações:</b><br><?= nl2br(htmlspecialchars($data['observacoes'])) ?></div>
        </div>
        <!-- Aba Detalhes -->
        <div class="tab-pane fade" id="detalhes">
            <?php 
            $itensLabel = [
                'Parte Externa' => [
                    'Para-choque Dianteiro', 'Para-choque Traseiro',
                    'Farol Dianteiro Esquerdo', 'Farol Dianteiro Direito',
                    'Lanterna Traseira Esquerda', 'Lanterna Traseira Direita',
                    'Retrovisores', 'Vidros', 'Limpadores'
                ],
                'Rodas e Pneus' => [
                    'Rodas', 'Pneus', 'Pneu Estepe'
                ],
                'Acessórios e Ferramentas' => [
                    'Chave de Rodas', 'Macaco'
                ]
            ];
            foreach ($itensLabel as $grupo => $lista): ?>
            <div class="mb-3">
                <h6><?= $grupo ?></h6>
                <div class="row">
                <?php foreach ($lista as $item): $key = 'item_' . md5($item); ?>
                    <div class="col-md-4 mb-2">
                        <b><?= $item ?>:</b> <span class="badge bg-secondary"><?= htmlspecialchars($itens[$key] ?? '-') ?></span>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- Aba Fotos -->
        <div class="tab-pane fade" id="fotos">
            <?php if ($fotos && is_array($fotos)): ?>
                <?php foreach ($fotos as $foto): ?>
                    <img src="<?= str_replace('../', '', $foto) ?>" class="img-preview" alt="Foto do veículo">
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma foto enviada.</p>
            <?php endif; ?>
        </div>
        <!-- Aba Assinaturas -->
        <div class="tab-pane fade" id="assinaturas">
            <div class="row">
                <div class="col-md-6">
                    <label>Assinatura do Cliente</label>
                    <div class="border p-3 bg-light">(em breve)</div>
                </div>
                <div class="col-md-6">
                    <label>Assinatura do Responsável</label>
                    <div class="border p-3 bg-light">(em breve)</div>
                </div>
            </div>
        </div>
    </div>
    <a href="checklist.php" class="btn btn-secondary">Voltar</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
