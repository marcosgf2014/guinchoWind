<?php
require_once '../includes/db.php';
$conn = getDbConnection();
// Buscar clientes e veículos
$clientes = $conn->query('SELECT id, nome FROM clientes ORDER BY nome');
$veiculos = $conn->query('SELECT v.id, v.placa, v.modelo, c.nome as cliente_nome FROM veiculos v JOIN clientes c ON v.cliente_id = c.id ORDER BY v.id DESC');

// Carregar checklist para edição, se id informado
$edit = false;
$check = null;
$itens = [];
$fotos = [];
if (isset($_GET['id']) && intval($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('SELECT * FROM checklists WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows) {
        $edit = true;
        $check = $res->fetch_assoc();
        $itens = json_decode($check['itens'] ?? '{}', true);
        $fotos = json_decode($check['fotos'] ?? '[]', true);
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Checklist de Veículo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .tab-content { background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid #ddd; }
        .tab-pane label { font-weight: 500; }
        .check-btn-group .btn { min-width: 100px; }
        .img-preview { max-width: 120px; margin: 0 8px 8px 0; }
        .signature-box { border: 1px dashed #aaa; min-height: 120px; display: flex; align-items: center; justify-content: center; cursor: pointer; background: #fafafa; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2><?= $edit ? 'Editar' : 'Novo' ?> Checklist de Veículo</h2>
    <form method="post" action="checklist_save.php" enctype="multipart/form-data">
        <?php if ($edit): ?>
            <input type="hidden" name="id" value="<?= $check['id'] ?>">
        <?php endif; ?>
        <div class="row g-2 mb-3">
            <div class="col-md-5">
                <label>Cliente</label>
                <select name="cliente_id" class="form-control" required>
    <option value="">Selecione...</option>
    <?php while($c = $clientes->fetch_assoc()): ?>
        <option value="<?= $c['id'] ?>" <?= ($edit && $check['veiculo_id'] ? ($c['id'] == $conn->query("SELECT cliente_id FROM veiculos WHERE id=".$check['veiculo_id'])->fetch_assoc()['cliente_id'] ? 'selected' : '') : '') ?>><?= htmlspecialchars($c['nome']) ?></option>
    <?php endwhile; ?>
</select>
            </div>
            <div class="col-md-5">
                <label>Veículo</label>
                <select name="veiculo_id" class="form-control" required>
    <option value="">Selecione...</option>
    <?php while($v = $veiculos->fetch_assoc()): ?>
        <option value="<?= $v['id'] ?>" <?= ($edit && $check['veiculo_id'] == $v['id'] ? 'selected' : '') ?>><?= htmlspecialchars($v['modelo']) ?> - Placa: <?= htmlspecialchars($v['placa']) ?> (<?= htmlspecialchars($v['cliente_nome']) ?>)</option>
    <?php endwhile; ?>
</select>
            </div>
            <div class="col-md-2">
                <label>Entrada</label>
                <input type="text" class="form-control" value="<?= date('d/m/Y H:i') ?>" readonly>
            </div>
        </div>
        <ul class="nav nav-tabs mb-3" id="tabChecklist" role="tablist">
            <li class="nav-item"><button class="nav-link active" id="tab-geral" data-bs-toggle="tab" data-bs-target="#geral" type="button">Geral</button></li>
            <li class="nav-item"><button class="nav-link" id="tab-detalhes" data-bs-toggle="tab" data-bs-target="#detalhes" type="button">Detalhes</button></li>
            <li class="nav-item"><button class="nav-link" id="tab-fotos" data-bs-toggle="tab" data-bs-target="#fotos" type="button">Fotos</button></li>
            <li class="nav-item"><button class="nav-link" id="tab-assinaturas" data-bs-toggle="tab" data-bs-target="#assinaturas" type="button">Assinaturas</button></li>
        </ul>
        <div class="tab-content mb-3">
            <!-- Aba Geral -->
            <div class="tab-pane fade show active" id="geral">
                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label>Quilometragem</label>
                        <input type="number" name="quilometragem" class="form-control" placeholder="Ex: 50000" value="<?= $edit ? htmlspecialchars($check['quilometragem']) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label>Nível de Combustível</label>
                        <select name="combustivel" class="form-control">
    <option value="">Selecione...</option>
    <option value="1/4" <?= ($edit && $check['combustivel']=='1/4' ? 'selected' : '') ?>>1/4</option>
    <option value="1/2" <?= ($edit && $check['combustivel']=='1/2' ? 'selected' : '') ?>>1/2</option>
    <option value="3/4" <?= ($edit && $check['combustivel']=='3/4' ? 'selected' : '') ?>>3/4</option>
    <option value="Cheio" <?= ($edit && $check['combustivel']=='Cheio' ? 'selected' : '') ?>>Cheio</option>
</select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="danos_visiveis" id="danos_visiveis" <?= ($edit && $check['danos_visiveis'] ? 'checked' : '') ?>>
<label class="form-check-label" for="danos_visiveis">Veículo possui danos externos visíveis</label>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <label>Pertences deixados no veículo</label>
                    <textarea name="pertences" class="form-control" rows="2" placeholder="Liste os pertences deixados no veículo..."><?= $edit ? htmlspecialchars($check['pertences']) : '' ?></textarea>
                </div>
                <div class="mb-2">
                    <label>Observações Adicionais</label>
                    <textarea name="obs_gerais" class="form-control" rows="2" placeholder="Observações adicionais importantes..."><?= $edit ? htmlspecialchars($check['observacoes']) : '' ?></textarea>
                </div>
            </div>
            <!-- Aba Detalhes -->
            <div class="tab-pane fade" id="detalhes">
                <?php 
                $itens = [
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
                foreach ($itens as $grupo => $lista): ?>
                <div class="mb-3">
                    <h6><?= $grupo ?></h6>
                    <div class="row">
                        <?php foreach ($lista as $item): ?>
                        <div class="col-md-4 mb-2">
                            <label><?= $item ?></label>
                            <div class="btn-group check-btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="item_<?= md5($item) ?>" id="<?= md5($item) ?>_ok" value="Ok" autocomplete="off" <?= ($edit && (isset($itens['item_'.md5($item)]) && $itens['item_'.md5($item)]=='Ok') ? 'checked' : '') ?>>
<label class="btn btn-outline-success" for="<?= md5($item) ?>_ok">Ok</label>
<input type="radio" class="btn-check" name="item_<?= md5($item) ?>" id="<?= md5($item) ?>_danificado" value="Danificado" autocomplete="off" <?= ($edit && (isset($itens['item_'.md5($item)]) && $itens['item_'.md5($item)]=='Danificado') ? 'checked' : '') ?>>
<label class="btn btn-outline-danger" for="<?= md5($item) ?>_danificado">Danificado</label>
<input type="radio" class="btn-check" name="item_<?= md5($item) ?>" id="<?= md5($item) ?>_ausente" value="Ausente" autocomplete="off" <?= ($edit && (isset($itens['item_'.md5($item)]) && $itens['item_'.md5($item)]=='Ausente') ? 'checked' : '') ?>>
<label class="btn btn-outline-warning" for="<?= md5($item) ?>_ausente">Ausente</label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- Aba Fotos -->
            <div class="tab-pane fade" id="fotos">
                <label>Adicionar Fotos do Veículo</label>
                <input type="file" name="fotos[]" class="form-control mb-2" multiple accept="image/*">
<?php if ($edit && !empty($fotos)): ?>
    <div class="mb-2">Fotos já enviadas:</div>
    <div class="d-flex flex-wrap mb-2">
        <?php foreach ($fotos as $i => $foto): ?>
    <div class="me-2 mb-2 text-center" style="display:inline-block;">
        <img src="<?= str_replace('../', '', $foto) ?>" class="img-preview" alt="Foto do checklist"><br>
        <input type="checkbox" name="excluir_foto[]" value="<?= htmlspecialchars($foto) ?>" id="excluir_foto_<?= $i ?>">
        <label for="excluir_foto_<?= $i ?>" style="font-size:12px;">Excluir</label>
    </div>
<?php endforeach; ?>
    </div>
<?php endif; ?>
<div id="previewFotos" class="d-flex flex-wrap"></div>
            </div>
            <!-- Aba Assinaturas -->
            <div class="tab-pane fade" id="assinaturas">
                <div class="row">
                    <div class="col-md-6">
                        <label>Assinatura do Cliente</label>
                        <div class="signature-box" onclick="alert('Funcionalidade de assinatura em desenvolvimento!')">
                            Clique para adicionar assinatura do cliente
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Assinatura do Responsável</label>
                        <div class="signature-box" onclick="alert('Funcionalidade de assinatura em desenvolvimento!')">
                            Clique para adicionar assinatura do responsável
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3 text-end">
            <button type="submit" class="btn btn-success">Salvar Checklist</button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Preview de fotos
const inputFotos = document.querySelector('input[name="fotos[]"]');
if (inputFotos) {
    inputFotos.addEventListener('change', function(e) {
        const preview = document.getElementById('previewFotos');
        preview.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(evt) {
                let img = document.createElement('img');
                img.src = evt.target.result;
                img.className = 'img-preview';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
}
</script>
</body>
</html>
