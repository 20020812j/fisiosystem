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
    <?php
    require_once __DIR__ . '/config/conexao.php';
    require_once __DIR__ . '/config/csrf.php';
    $paciente_id = (int)($_GET['paciente_id'] ?? $_POST['paciente_id'] ?? 0);
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
            die('Token CSRF inválido.');
        }
        $campos = ['queixa_principal','historia_doenca','antecedentes','medicamentos','diagnostico_medico','diagnostico_fisioterapeutico','inspecao','palpacao','amplitude_movimento','forca_muscular','objetivos','conduta'];
        foreach($campos as $c) $$c = trim($_POST[$c] ?? '');
        $dor_eva = (int)($_POST['dor_eva'] ?? 0);
        $data_avaliacao = $_POST['data_avaliacao'] ?: date('Y-m-d');
        $sql = "INSERT INTO avaliacoes (paciente_id,queixa_principal,historia_doenca,antecedentes,medicamentos,diagnostico_medico,diagnostico_fisioterapeutico,dor_eva,inspecao,palpacao,amplitude_movimento,forca_muscular,objetivos,conduta,data_avaliacao) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt=$conexao->prepare($sql);
        $stmt->bind_param('issssssisssssss',$paciente_id,$queixa_principal,$historia_doenca,$antecedentes,$medicamentos,$diagnostico_medico,$diagnostico_fisioterapeutico,$dor_eva,$inspecao,$palpacao,$amplitude_movimento,$forca_muscular,$objetivos,$conduta,$data_avaliacao);
        $stmt->execute();
        $conexao->query("UPDATE pacientes SET status='tratamento' WHERE id=$paciente_id AND status='avaliacao'");
        header("Location: ficha.php?id=$paciente_id"); exit;
    }
    ?>
    <!doctype html>
    <html lang="pt-br">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Avaliação</title>
    <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
    <header class="topo"><h1>Avaliação fisioterapêutica</h1></header>
    <main class="container">
    <a class="voltar" href="ficha.php?id=<?= $paciente_id ?>">← Voltar</a>
    <form method="post" class="formulario painel">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(get_csrf_token()) ?>">
    <input type="hidden" name="paciente_id" value="<?= $paciente_id ?>">
    <label>Data<input type="date" name="data_avaliacao" value="<?= date('Y-m-d') ?>"></label>
    <label>Queixa principal<textarea name="queixa_principal"></textarea></label>
    <label>História da doença atual<textarea name="historia_doenca"></textarea></label>
    <label>Antecedentes<textarea name="antecedentes"></textarea></label>
    <label>Medicamentos<textarea name="medicamentos"></textarea></label>
    <label>Diagnóstico médico<textarea name="diagnostico_medico"></textarea></label>
    <label>Diagnóstico fisioterapêutico<textarea name="diagnostico_fisioterapeutico"></textarea></label>
    <label>Dor EVA (0 a 10)<input type="number" name="dor_eva" min="0" max="10" value="0"></label>
    <label>Inspeção<textarea name="inspecao"></textarea></label>
    <label>Palpação<textarea name="palpacao"></textarea></label>
    <label>Amplitude de movimento<textarea name="amplitude_movimento"></textarea></label>
    <label>Força muscular<textarea name="forca_muscular"></textarea></label>
    <label>Objetivos<textarea name="objetivos"></textarea></label>
    <label>Conduta<textarea name="conduta"></textarea></label>
    <button type="submit">Salvar avaliação</button>
    </form>
    </main>
    </body>
    </html>
