<?php
require_once __DIR__ . '/config/csrf.php';
require_once __DIR__ . '/config/auth.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $msg = 'Token CSRF inválido.';
    } else {
        $user = trim($_POST['username'] ?? '');
        $pass = $_POST['password'] ?? '';
        global $FISIO_USERS;
        if (isset($FISIO_USERS[$user]) && password_verify($pass, $FISIO_USERS[$user])) {
            login_user($user);
            header('Location: index.php');
            exit;
        }
        $msg = 'Usuário ou senha incorretos.';
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - FisioSystem</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<main class="container">
<h1>Entrar</h1>
<?php if ($msg): ?><div class="alerta"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="post" class="formulario painel" style="max-width:360px;">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(get_csrf_token()) ?>">
<label>Usuário<input type="text" name="username" required></label>
<label>Senha<input type="password" name="password" required></label>
<button type="submit">Entrar</button>
</form>
</main>
</body>
</html>
