<?php
$
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/config/auth.php';
require_login();
$id = (int)($_GET['id'] ?? 0);
$stmt = $conexao->prepare("SELECT * FROM pacientes WHERE id=?");
$stmt->bind_param('i',$id); $stmt->execute();
$paciente = $stmt->get_result()->fetch_assoc();
if (!$paciente) die('Paciente não encontrado.');

$avaliacoes = $conexao->query("SELECT * FROM avaliacoes WHERE paciente_id=$id ORDER BY data_avaliacao DESC");
$evolucoes = $conexao->query("SELECT * FROM evolucoes WHERE paciente_id=$id ORDER BY data_sessao DESC, id DESC");
?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Ficha</title><link rel="stylesheet" href="css/style.css"></head><body>
<header class="topo"><h1><?= htmlspecialchars($paciente['nome']) ?></h1><p>Ficha do paciente</p></header>
<main class="container">
<a class="voltar" href="pacientes.php">← Pacientes</a>
<div class="acoes"><a class="botao" href="avaliacao.php?paciente_id=<?= $id ?>">+ Avaliação</a><a class="botao secundario" href="evolucao.php?paciente_id=<?= $id ?>">+ Evolução</a></div>
<section class="painel"><h2>Dados pessoais</h2><div class="grid2"><p><b>CPF:</b> <?= htmlspecialchars($paciente['cpf']) ?></p><p><b>Telefone:</b> <?= htmlspecialchars($paciente['telefone']) ?></p><p><b>Nascimento:</b> <?= htmlspecialchars($paciente['data_nascimento']) ?></p><p><b>Profissão:</b> <?= htmlspecialchars($paciente['profissao']) ?></p></div><p><b>Endereço:</b> <?= htmlspecialchars($paciente['endereco']) ?></p><p><b>Status:</b> <?= htmlspecialchars($paciente['status']) ?></p></section>
<section class="painel"><h2>Avaliações</h2><?php if($avaliacoes->num_rows===0): ?><p>Nenhuma avaliação registrada.</p><?php endif; ?><?php while($a=$avaliacoes->fetch_assoc()): ?><article class="registro"><b><?= htmlspecialchars($a['data_avaliacao']) ?></b><p><b>Queixa:</b> <?= nl2br(htmlspecialchars($a['queixa_principal'])) ?></p><p><b>EVA:</b> <?= htmlspecialchars($a['dor_eva']) ?>/10</p><p><b>Diagnóstico fisioterapêutico:</b> <?= nl2br(htmlspecialchars($a['diagnostico_fisioterapeutico'])) ?></p></article><?php endwhile; ?></section>
<section class="painel"><h2>Evoluções</h2><?php if($evolucoes->num_rows===0): ?><p>Nenhuma evolução registrada.</p><?php endif; ?><?php while($e=$evolucoes->fetch_assoc()): ?><article class="registro"><b>Sessão <?= (int)$e['numero_sessao'] ?> — <?= htmlspecialchars($e['data_sessao']) ?></b><p><b>Dor:</b> <?= (int)$e['dor_antes'] ?>/10 → <?= (int)$e['dor_depois'] ?>/10</p><p><?= nl2br(htmlspecialchars($e['evolucao'])) ?></p></article><?php endwhile; ?></section>
</main></body></html>
