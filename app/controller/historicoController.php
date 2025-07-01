<?php
session_start();
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../../boostrap.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('Usuário não autenticado');
}

$user_id = (string) $user_id;

// Buscar todos os materiais do usuário
function buscarHistoricoMateriais($connection, $user_id) {
    $stmt = $connection->prepare("
        SELECT 
            id,
            tipo_material,
            estado_conservacao,
            descricao,
            foto,
            localizacao,
            quantidade_estimada_kg,
            unidade,
            preco_total,
            created_at
        FROM residuos 
        WHERE user_id = ? 
        ORDER BY created_at DESC
    ");
    
    if (!$stmt) {
        die("Erro na preparação da query: " . $connection->error);
    }
    
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $materiais = [];
    
    while ($row = $result->fetch_assoc()) {
        $materiais[] = $row;
    }
    
    $stmt->close();
    return $materiais;
}

// Buscar material específico por ID (para o modal)
function buscarMaterialPorId($connection, $material_id, $user_id) {
    $stmt = $connection->prepare("
        SELECT * FROM residuos 
        WHERE id = ? AND user_id = ?
    ");
    
    if (!$stmt) {
        die("Erro na preparação da query: " . $connection->error);
    }
    
    $stmt->bind_param("is", $material_id, $user_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $material = $result->fetch_assoc();
    
    $stmt->close();
    return $material;
}

// Excluir material
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_material'])) {
    $material_id = $_POST['material_id'];
    
    // Primeiro buscar a foto para deletar o arquivo
    $material = buscarMaterialPorId($connection, $material_id, $user_id);
    
    if ($material) {
        // Deletar arquivo de foto se existir
        if ($material['foto'] && file_exists(__DIR__ . '/../../' . $material['foto'])) {
            unlink(__DIR__ . '/../../' . $material['foto']);
        }
        
        // Deletar registro do banco
        $stmt = $connection->prepare("DELETE FROM residuos WHERE id = ? AND user_id = ?");
        $stmt->bind_param("is", $material_id, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['material_excluido'] = true;
        } else {
            $_SESSION['erro_exclusao'] = true;
        }
        
        $stmt->close();
    }
    
    header("Location: ../views/historico.php");
    exit;
}

// Buscar dados para AJAX (modal)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax']) && isset($_GET['material_id'])) {
    $material_id = $_GET['material_id'];
    $material = buscarMaterialPorId($connection, $material_id, $user_id);
    
    header('Content-Type: application/json');
    echo json_encode($material);
    exit;
}

// Buscar todos os materiais para exibir na view
$historico_materiais = buscarHistoricoMateriais($connection, $user_id);

// Função para formatar o tipo de material
function formatarTipoMaterial($tipo) {
    $tipos = [
        'papel' => 'Papel',
        'papelao' => 'Papelão',
        'plastico_kg' => 'Plástico (KG)',
        'plastico_un' => 'Plástico (Unidade)',
        'vidro' => 'Vidro',
        'aluminio' => 'Alumínio',
        'ferro' => 'Ferro',
        'cobre' => 'Cobre',
        'lata_kg' => 'Lata (KG)',
        'lata_un' => 'Lata (Unidade)',
        'eletronico_kg' => 'Eletrônico (KG)',
        'eletronico_un' => 'Eletrônico (Unidade)',
        'bateria_pilha' => 'Bateria/Pilha',
        'oleo' => 'Óleo',
        'tecido' => 'Tecido',
    ];
    
    return $tipos[$tipo] ?? ucfirst($tipo);
}

// Função para formatar data
function formatarData($data) {
    return date('d/m/Y H:i', strtotime($data));
}

$connection->close();
?>