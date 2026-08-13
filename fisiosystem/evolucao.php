<?php
$
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/csrf.php';
require_login();
$paciente_id = (int)($_GET['paciente_id'] ?? $_POST['paciente_id'] ?? 0);
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        die('Token CSRF inválido.');
    }
    $data_sessao = $_POST['data_sessao'] ?: date('Y-m-d');
    $numero_sessao = (int)($_POST['numero_sessao'] ?? 1);
    $dor_antes = (int)($_POST['dor_antes'] ?? 0);
    $dor_depois = (int)($_POST['dor_depois'] ?? 0);
    $procedimentos = trim($_POST['procedimentos'] ?? '');
    $evolucao = trim($_POST['evolucao'] ?? '');
    $proxima_conduta = trim($_POST['proxima_conduta'] ?? '');
    $stmt=$conexao->prepare("INSERT INTO evolucoes (paciente_id,data_sessao,numero_sessao,dor_antes,dor_depois,procedimentos,evolucao,proxima_conduta) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param('isiiisss',$paciente_id,$data_sessao,$numero_sessao,$dor_antes,$dor_depois,$procedimentos,$evolucao,$proxima_conduta);
    $stmt->execute();
    header("Location: ficha.php?id=$paciente_id"); exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Evolução</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topo"><h1>Evolução fisioterapêutica</h1></header>
<main class="container">
<a class="voltar" href="ficha.php?id=<?= $paciente_id ?>">← Voltar</a>
<form method="post" class="formulario painel">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(get_csrf_token()) ?>">
<input type="hidden" name="paciente_id" value="<?= $paciente_id ?>">
<div class="grid2"><label>Data<input type="date" name="data_sessao" value="<?= date('Y-m-d') ?>"></label><label>Número da sessão<input type="number" name="numero_sessao" min="1" value="1"></label></div>
<div class="grid2"><label>Dor antes (0-10)<input type="number" name="dor_antes" min="0" max="10" value="0"></label><label>Dor depois (0-10)<input type="number" name="dor_depois" min="0" max="10" value="0"></label></div>
<label>Procedimentos realizados<textarea name="procedimentos"></textarea></label>
<label>Evolução<textarea name="evolucao" required></textarea></label>
<label>Conduta para próxima sessão<textarea name="proxima_conduta"></textarea></label>
<button type="submit">Salvar evolução</button>
</form>
</main>
</body>
</html>
