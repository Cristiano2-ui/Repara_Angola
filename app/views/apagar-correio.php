<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "sr") or die("Erro ao conectar.");

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header("Location: login.php");
    exit;
}

$id = $_POST['id'] ?? null;
if ($id) {
    $stmt = $connection->prepare("DELETE FROM mensagens WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $id, $userId);
    $stmt->execute();
}

header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'correio.php'));
exit;
?>
