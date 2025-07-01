<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o BD
$connection = null;

try {
    $connection = mysqli_connect("localhost", "root", "", "sr");
} catch (\Throwable $th) {
    echo "404";
}

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Verificação de autenticação
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die('Usuário não autenticado');
}

$user_id = $user_id;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_material = $_POST['tipo_material'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $localizacao = $_POST['localizacao'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 0;

    // Upload da imagem
    $foto = '';
    // if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    //     $nome_arquivo = time() . '_' . basename($_FILES['foto']['name']);
    //     $destino_relativo = 'uploads/' . $nome_arquivo;
    //     $destino_absoluto = __DIR__ . '/../../' . $destino_relativo;

    //     if (!is_dir(__DIR__ . '/../../uploads')) {
    //         mkdir(__DIR__ . '/../../uploads', 0777, true);
    //     }

    //     if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino_absoluto)) {
    //         $foto = $destino_relativo;
    //     } else {
    //         die("Erro ao mover o arquivo.");
    //     }
    // } else {
    //     $foto = 'sem imagem';
    // }

    // INSERT sem as colunas 'unidade' e 'preco_total'
    $sql = "INSERT INTO residuos (
        user_id, 
        tipo_material, 
        estado_conservacao, 
        descricao, 
        foto, 
        localizacao, 
        quantidade_estimada_kg,
        created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        die("Erro na preparação da query: " . $connection->error);
    }

    $stmt->bind_param(
        "sssssss",
        $user_id,
        $tipo_material,
        $estado,
        $descricao,
        $foto,
        $localizacao,
        $quantidade
    );

    if ($stmt->execute()) {
        $_SESSION['material_cadastrado'] = true;
        $stmt->close();
        $connection->close();
        header("Location: reciclagem?sucess=1");
        exit;
    } else {
        die("Erro ao cadastrar: " . $stmt->error);
    }
}
