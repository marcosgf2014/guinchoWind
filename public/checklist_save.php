<?php
require_once '../includes/db.php';
$conn = getDbConnection();

// Receber dados principais
$cliente_id = (int)$_POST['cliente_id'];
$veiculo_id = (int)$_POST['veiculo_id'];
$quilometragem = isset($_POST['quilometragem']) ? (int)$_POST['quilometragem'] : null;
$combustivel = $_POST['combustivel'] ?? '';
$danos_visiveis = isset($_POST['danos_visiveis']) ? 1 : 0;
$pertences = trim($_POST['pertences'] ?? '');
$obs_gerais = trim($_POST['obs_gerais'] ?? '');

// Itens detalhados (serializar para salvar)
$itens_detalhados = [];
foreach ($_POST as $key => $value) {
    if (strpos($key, 'item_') === 0) {
        $itens_detalhados[$key] = $value;
    }
}
$itens_json = json_encode($itens_detalhados, JSON_UNESCAPED_UNICODE);

// Fotos (simples: salva nomes dos arquivos, upload real pode ser melhorado depois)
$fotos = [];
if (!empty($_FILES['fotos']['name'][0])) {
    $uploadDir = '../uploads/checklist_fotos/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    foreach ($_FILES['fotos']['tmp_name'] as $i => $tmpName) {
        $name = basename($_FILES['fotos']['name'][$i]);
        $dest = $uploadDir . uniqid() . '_' . $name;
        if (move_uploaded_file($tmpName, $dest)) {
            $fotos[] = $dest;
        }
    }
}
$fotos_json = json_encode($fotos, JSON_UNESCAPED_UNICODE);

// Assinaturas (não implementado ainda)
$assinatura_cliente = '';
$assinatura_responsavel = '';

if (!empty($_POST['id'])) {
    // Atualizar checklist existente
    $id = (int)$_POST['id'];
    // Buscar fotos atuais (usando prepared statement para evitar erro de sintaxe e SQL injection)
    $fotos_atuais = [];
    $stmtFotos = $conn->prepare('SELECT fotos FROM checklists WHERE id=?');
    $stmtFotos->bind_param('i', $id);
    $stmtFotos->execute();
    $result = $stmtFotos->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        $fotos_atuais = json_decode($row['fotos'] ?? '[]', true);
    }
    $stmtFotos->close();
    // Excluir fotos marcadas
    $fotos_excluir = isset($_POST['excluir_foto']) ? $_POST['excluir_foto'] : [];
    if (!empty($fotos_excluir)) {
        foreach ($fotos_excluir as $f) {
            if (file_exists($f)) @unlink($f);
        }
        $fotos_atuais = array_values(array_diff($fotos_atuais, $fotos_excluir));
    }
    // Adicionar novas fotos
    if (!empty($fotos)) {
        $fotos_atuais = array_merge($fotos_atuais, $fotos);
    }
    $fotos_json = json_encode($fotos_atuais, JSON_UNESCAPED_UNICODE);
    $stmt = $conn->prepare('UPDATE checklists SET veiculo_id=?, quilometragem=?, combustivel=?, danos_visiveis=?, pertences=?, observacoes=?, itens=?, fotos=?, assinatura_cliente=?, assinatura_responsavel=? WHERE id=?');
    $stmt->bind_param('iisissssssi', $veiculo_id, $quilometragem, $combustivel, $danos_visiveis, $pertences, $obs_gerais, $itens_json, $fotos_json, $assinatura_cliente, $assinatura_responsavel, $id);
    $stmt->execute();
} else {
    // Inserir novo checklist
    $stmt = $conn->prepare('INSERT INTO checklists (servico_id, veiculo_id, quilometragem, combustivel, danos_visiveis, pertences, observacoes, itens, fotos, assinatura_cliente, assinatura_responsavel, criado_em) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
    $stmt->bind_param('iisissssss', $veiculo_id, $quilometragem, $combustivel, $danos_visiveis, $pertences, $obs_gerais, $itens_json, $fotos_json, $assinatura_cliente, $assinatura_responsavel);
    $stmt->execute();
}

header('Location: checklist.php');
exit;
