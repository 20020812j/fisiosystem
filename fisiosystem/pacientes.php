<?php
$
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/config/auth.php';
require_login();
$busca = trim($_GET['busca'] ?? '');
if ($busca !== '') {
    $stmt = $conexao->prepare("SELECT * FROM pacientes WHERE nome LIKE ? OR cpf LIKE ? ORDER BY nome");
    $like = "%$busca%";
    $stmt->bind_param('ss', $like, $like);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = $conexao->query("SELECT * FROM pacientes ORDER BY nome");
}
?>
<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Pacientes</title><link rel="stylesheet" href="css/style.css"></head><body>
<header class="topo"><h1>Pacientes</h1></header>
<nav class="menu"><a href="index.php">Dashboard</a><a href="kanban.php">Kanban</a><a class="botao" href="cadastrar_paciente.php">+ Novo paciente</a><a href="logout.php">Sair</a></nav>
<main class="container">
<form method="get" class="busca"><input type="text" name="busca" placeholder="Buscar por nome ou CPF" value="<?= htmlspecialchars($busca) ?>"><button>Buscar</button></form>
<div class="painel tabela-wrap"><table><thead><tr><th>Nome</th><th>Telefone</th><th>Status</th><th>Ações</th></tr></thead><tbody>
<?php while($p = $resultado->fetch_assoc()): ?>
<tr><td><?= htmlspecialchars($p['nome']) ?></td><td><?= htmlspecialchars($p['telefone']) ?></td><td><?= htmlspecialchars(ucfirst(str_replace('_',' ',$p['status']))) ?></td><td><a href="ficha.php?id=<?= $p['id'] ?>">Abrir ficha</a></td></tr>
<?php endwhile; ?>
</tbody></table></div>
</main></body></html>
