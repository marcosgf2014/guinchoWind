<?php
require_once '../includes/config.php';
?><!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guincho</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Guincho Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="clientes.php">Clientes</a></li>
        <li class="nav-item"><a class="nav-link" href="veiculos.php">Veículos</a></li>
        <li class="nav-item"><a class="nav-link" href="servicos.php">Serviços</a></li>
        <li class="nav-item"><a class="nav-link" href="checklist.php">Checklist</a></li>
        <li class="nav-item"><a class="nav-link" href="relatorios.php">Relatórios</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">
    <h1>Bem-vindo ao Dashboard da Empresa de Guincho</h1>
    <p>Gerencie clientes, veículos, serviços, checklists e gere relatórios em PDF.</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
