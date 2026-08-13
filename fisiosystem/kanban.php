<?php
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/csrf.php';
require_login();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        die('Token CSRF inválido.');
    }
    $id=(int)($_POST['id']??0);
    $status=$_POST['status']??'avaliacao';
    $permitidos=['avaliacao','tratamento','reavaliacao','alta'];
    if(in_array($status,$permitidos,true)) {
        $stmt=$conexao->prepare("UPDATE pacientes SET status=? WHERE id=?");
        $stmt->bind_param('si',$status,$id);
        $stmt->execute();
    }
    header('Location: kanban.php');
    exit;
}

$pacientes=$conexao->query("SELECT * FROM pacientes ORDER BY nome");
$grupos=['avaliacao'=>[],'tratamento'=>[],'reavaliacao'=>[],'alta'=>[]];
while($p=$pacientes->fetch_assoc()) $grupos[$p['status']][]=$p;
$titulos=['avaliacao'=>'Avaliação','tratamento'=>'Em tratamento','reavaliacao'=>'Reavaliação','alta'=>'Alta'];
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Kanban</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topo"><h1>Kanban de pacientes</h1></header>
<nav class="menu"><a href="index.php">Dashboard</a><a href="pacientes.php">Pacientes</a><a href="logout.php">Sair</a></nav>
<main class="container kanban">
<?php foreach($grupos as $status=>$lista): ?>
    <section class="coluna">
        <h2><?= $titulos[$status] ?></h2>
        <?php foreach($lista as $p): ?>
            <article class="kanban-card">
                <h3><?= htmlspecialchars($p['nome']) ?></h3>
                <p><?= htmlspecialchars($p['telefone']) ?></p>
                <a href="ficha.php?id=<?= $p['id'] ?>">Abrir ficha</a>
                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(get_csrf_token()) ?>">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <select name="status" onchange="this.form.submit()">
                        <option value="avaliacao" <?= $status==='avaliacao'?'selected':'' ?>>Avaliação</option>
                        <option value="tratamento" <?= $status==='tratamento'?'selected':'' ?>>Em tratamento</option>
                        <option value="reavaliacao" <?= $status==='reavaliacao'?'selected':'' ?>>Reavaliação</option>
                        <option value="alta" <?= $status==='alta'?'selected':'' ?>>Alta</option>
                    </select>
                </form>
            </article>
        <?php endforeach; ?>
    </section>
<?php endforeach; ?>
</main>
</body>
</html>
