<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'fisiosystem';

// Conecta ao banco e trata erro de forma segura
$conexao = new mysqli($host, $usuario, $senha, $banco);
if ($conexao->connect_error) {
    error_log('DB connection error: ' . $conexao->connect_error);
    http_response_code(500);
    echo 'Erro na conexão com o banco de dados.';
    exit;
}
$conexao->set_charset('utf8mb4');
?>
