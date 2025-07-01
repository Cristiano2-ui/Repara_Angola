<?php
var_dump($_POST);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão com MySQL
$connection = mysqli_connect("localhost", "root", "", "sr");

// Verifica se conectou
if (!$connection) {
    die("Erro na conexão com o banco de dados.");
}

// Recebendo dados do POST
$user_id = $_POST['user_id'] ?? null;
$residuo_id = $_POST['residuo_id'] ?? null;
$local_coleta = $_POST['local_coleta'] ?? null;
$data_coleta = $_POST['data_coleta'] ?? null;
$hora_coleta = $_POST['hora_coleta'] ?? null;
$mensagem = $_POST['mensagem'] ?? null;

// Validação simples
if (!$user_id || !$residuo_id || !$local_coleta || !$data_coleta || !$hora_coleta) {
    die("Dados incompletos.");
}

// Preparar e executar SQL
$stmt = mysqli_prepare($connection, "INSERT INTO mensagens (user_id, residuo_id, local_coleta, data_coleta, hora_coleta, mensagem, criado_em) VALUES (?, ?, ?, ?, ?, ?, NOW())");
mysqli_stmt_bind_param($stmt, "iissss", $user_id, $residuo_id, $local_coleta, $data_coleta, $hora_coleta, $mensagem);

if (!mysqli_stmt_execute($stmt)) {
    die("Erro ao salvar os dados: " . mysqli_error($connection));
}

// Redirecionar para a mesma página
header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/'));
exit;
?>
