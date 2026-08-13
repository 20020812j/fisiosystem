<?php
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/config/auth.php';
require_login();

$totalPacientes = 0;
$totalAvaliacoes = 0;
$totalEvolucoes = 0;

if ($r = $conexao->query("SELECT COUNT(*) total FROM pacientes")) { $totalPacientes = (int)$r->fetch_assoc()['total']; }
if ($r = $conexao->query("SELECT COUNT(*) total FROM avaliacoes")) { $totalAvaliacoes = (int)$r->fetch_assoc()['total']; }
if ($r = $conexao->query("SELECT COUNT(*) total FROM evolucoes")) { $totalEvolucoes = (int)$r->fetch_assoc()['total']; }
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>FisioSystem</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topo"><div><h1>FisioSystem</h1><p>Avaliação e evolução fisioterapêutica</p></div></header>
<nav class="menu">
<a href="index.php">Dashboard</a>
<a href="pacientes.php">Pacientes</a>
<a href="kanban.php">Kanban</a>
<a class="botao" href="cadastrar_paciente.php">+ Novo paciente</a>
<a href="logout.php">Sair</a>
</nav>
<main class="container">
<section class="cards">
<div class="card"><h3>Pacientes</h3><strong><?= $totalPacientes ?></strong></div>
<div class="card"><h3>Avaliações</h3><strong><?= $totalAvaliacoes ?></strong></div>
<div class="card"><h3>Evoluções</h3><strong><?= $totalEvolucoes ?></strong></div>
</section>
<section class="painel">
<h2>Bem-vindo</h2>
<p>Use o menu para cadastrar pacientes, preencher avaliações, registrar sessões e acompanhar o andamento pelo Kanban.</p>
</section>
</main>
</body>
</html>
