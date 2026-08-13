<?php
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/csrf.php';
require_login();
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $msg = 'Token CSRF inválido. Tente novamente.';
    } else {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?: null;
    $telefone = trim($_POST['telefone'] ?? '');
    $profissao = trim($_POST['profissao'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $status = $_POST['status'] ?? 'avaliacao';

    if ($nome === '') {
        $msg = 'Informe o nome do paciente.';
    } else {
        // usa NULLIF para inserir NULL quando o campo vier vazio
        $stmt = $conexao->prepare("INSERT INTO pacientes (nome, cpf, data_nascimento, telefone, profissao, endereco, status) VALUES (?, ?, NULLIF(?,''), ?, ?, ?, ?)");
        $stmt->bind_param('sssssss', $nome, $cpf, $data_nascimento, $telefone, $profissao, $endereco, $status);
        if ($stmt->execute()) {
            header('Location: ficha.php?id=' . $stmt->insert_id);
            exit;
        }
        $msg = 'Erro ao cadastrar paciente: ' . $conexao->error;
    }
}
?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Novo paciente</title><link rel="stylesheet" href="css/style.css"></head><body>
<header class="topo"><h1>Novo paciente</h1></header>
<main class="container">
<a class="voltar" href="pacientes.php">← Voltar</a>
<?php if ($msg): ?><div class="alerta"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="post" class="formulario painel">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(get_csrf_token()) ?>">
<label>Nome completo<input type="text" name="nome" required></label>
<div class="grid2">
<label>CPF<input type="text" name="cpf"></label>
<label>Data de nascimento<input type="date" name="data_nascimento"></label>
</div>
<div class="grid2">
<label>Telefone<input type="text" name="telefone"></label>
<label>Profissão<input type="text" name="profissao"></label>
</div>
<label>Endereço<input type="text" name="endereco"></label>
<label>Status
<select name="status"><option value="avaliacao">Avaliação</option><option value="tratamento">Em tratamento</option><option value="reavaliacao">Reavaliação</option><option value="alta">Alta</option></select>
</label>
<button type="submit">Cadastrar paciente</button>
</form>
</main></body></html>
