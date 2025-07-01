<?php




// $absolute_path=str_replace('controller','database',__DIR__);
//  die($absolute_path);
// require_once $absolute_path.'/connection.php';


$connection = null;

try {
    $connection = mysqli_connect("localhost", "root", "", "sr");
} catch (\Throwable $th) {

    echo "404";
}

if (!$connection) {

    die("Connection failed: " . mysqli_connect_error());
}



// Verifica se é empresa logada
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'empresa') {
    header("Location: /PROJECTO-RECICLA-FACIL/login");
    exit;
}

// Função para buscar estatísticas gerais
function getStats($connection)
{
    $stats = [];

    // Total de usuários
    $result = $connection->query("SELECT COUNT(*) as total FROM users WHERE tipo_usuario = 'cidadao'");
    $stats['total_usuarios'] = $result->fetch_assoc()['total'];

    // Usuários ativos (que já enviaram resíduos)
    $result = $connection->query("SELECT COUNT(DISTINCT user_id) as ativos FROM residuos");
    $stats['usuarios_ativos'] = $result->fetch_assoc()['ativos'];

    // Total de resíduos coletados
    $result = $connection->query("SELECT COUNT(*) as total FROM residuos");
    $stats['residuos_coletados'] = $result->fetch_assoc()['total'];

    // Pontos de coleta (fictício por enquanto)
    $stats['pontos_coleta'] = 18;

    return $stats;
}

// Função para buscar materiais mais reciclados
function getMaterialStats($connection)
{
    $sql = "SELECT tipo_material, COUNT(*) as quantidade, 
            ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM residuos)), 0) as porcentagem
            FROM residuos 
            GROUP BY tipo_material 
            ORDER BY quantidade DESC 
            LIMIT 4";

    $result = $connection->query($sql);
    $materiais = [];

    while ($row = $result->fetch_assoc()) {
        $materiais[] = $row;
    }

    return $materiais;
}

// Função para buscar atividades recentes
function getRecentActivities($connection)
{
    $sql = "SELECT r.*, u.nome as usuario_nome, u.email as usuario_email 
            FROM residuos r 
            JOIN users u ON r.user_id = u.id 
            ORDER BY r.created_at DESC 
            LIMIT 10";

    $result = $connection->query($sql);
    $atividades = [];

    while ($row = $result->fetch_assoc()) {
        $atividades[] = $row;
    }

    return $atividades;
}

// Função para buscar todos os usuários
function getAllUsers($connection, $search = '')
{
    $sql = "SELECT * FROM users WHERE tipo_usuario = 'cidadao'";

    if (!empty($search)) {
        $sql .= " AND (nome LIKE '%$search%' OR email LIKE '%$search%')";
    }

    $sql .= " ORDER BY created_at DESC";

    $result = $connection->query($sql);
    $usuarios = [];

    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }

    return $usuarios;
}

// Função para buscar usuários ativos com estatísticas
function getActiveUsers($connection)
{
    $sql = "SELECT u.*, COUNT(r.id) as total_reciclagens,
            CASE 
                WHEN COUNT(r.id) >= 50 THEN 'Top Reciclador'
                WHEN COUNT(r.id) >= 20 THEN 'Ativo'
                ELSE 'Novo'
            END as status_reciclagem,
            ROUND((COUNT(r.id) * 100.0 / (SELECT MAX(total) FROM (SELECT COUNT(*) as total FROM residuos GROUP BY user_id) as sub)), 0) as impacto_percentual
            FROM users u 
            JOIN residuos r ON u.id = r.user_id 
            WHERE u.tipo_usuario = 'cidadao'
            GROUP BY u.id 
            ORDER BY total_reciclagens DESC
            LIMIT 6";

    $result = $connection->query($sql);
    $usuarios_ativos = [];

    while ($row = $result->fetch_assoc()) {
        $usuarios_ativos[] = $row;
    }

    return $usuarios_ativos;
}

// Função para buscar resíduos com filtros
function getResiduos($connection, $tipo_filter = '', $status_filter = '')
{
    $sql = "SELECT r.*, u.nome as usuario_nome, u.email as usuario_email, u.localizacao as usuario_localizacao
            FROM residuos r 
            JOIN users u ON r.user_id = u.id 
            WHERE 1=1";

    if (!empty($tipo_filter)) {
        $sql .= " AND r.tipo_material = '$tipo_filter'";
    }

    if (!empty($status_filter)) {
        $sql .= " AND r.status = '$status_filter'";
    }

    $sql .= " ORDER BY r.created_at DESC";

    $result = $connection->query($sql);
    $residuos = [];

    while ($row = $result->fetch_assoc()) {
        $residuos[] = $row;
    }

    return $residuos;
}

// Função para buscar dados de relatórios
function getReportData($connection)
{
    $data = [];

    // Resíduos por mês
    $sql = "SELECT 
                SUM(quantidade_estimada_kg) as total_kg,
                COUNT(*) as total_items
            FROM residuos 
            WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
            AND YEAR(created_at) = YEAR(CURRENT_DATE())";

    $result = $connection->query($sql);
    $mensal = $result->fetch_assoc();

    $data['total_kg_mes'] = $mensal['total_kg'] ?? 0;
    $data['co2_evitado'] = round($data['total_kg_mes'] * 0.535, 0); // Fator de conversão aproximado
    $data['arvores_salvas'] = round($data['total_kg_mes'] / 60, 0); // Aproximadamente 60kg = 1 árvore
    $data['economia_agua'] = round($data['total_kg_mes'] * 5.35, 0); // Fator de conversão aproximado

    // Impacto por província (baseado na localização dos usuários)
    $sql = "SELECT u.localizacao, COUNT(r.id) as total_residuos, SUM(r.quantidade_estimada_kg) as total_kg
            FROM residuos r 
            JOIN users u ON r.user_id = u.id 
            WHERE MONTH(r.created_at) = MONTH(CURRENT_DATE()) 
            AND YEAR(r.created_at) = YEAR(CURRENT_DATE())
            GROUP BY u.localizacao 
            ORDER BY total_kg DESC";

    $result = $connection->query($sql);
    $provincias = [];

    while ($row = $result->fetch_assoc()) {
        $provincias[] = $row;
    }

    $data['provincias'] = $provincias;

    return $data;
}

// Processar ações AJAX
if (isset($_GET['action'])) {
    header('Content-Type: application/json');

    switch ($_GET['action']) {
        case 'get_stats':
            echo json_encode(getStats($connection));
            break;

        case 'get_material_stats':
            echo json_encode(getMaterialStats($connection));
            break;

        case 'get_recent_activities':
            echo json_encode(getRecentActivities($connection));
            break;

        case 'get_users':
            $search = $_GET['search'] ?? '';
            echo json_encode(getAllUsers($connection, $search));
            break;

        case 'get_active_users':
            echo json_encode(getActiveUsers($connection));
            break;

        case 'get_residuos':
            $tipo = $_GET['tipo'] ?? '';
            $status = $_GET['status'] ?? '';
            echo json_encode(getResiduos($connection, $tipo, $status));
            break;

        case 'get_report_data':
            echo json_encode(getReportData($connection));
            break;

        case 'respond_residuo':
            if ($_POST) {
                $residuo_id = $_POST['residuo_id'];
                $local_coleta = $_POST['local_coleta'];
                $data_coleta = $_POST['data_coleta'];
                $hora_coleta = $_POST['hora_coleta'];
                $mensagem = $_POST['mensagem'];

                // Atualizar status do resíduo
                $stmt = $connection->prepare("UPDATE residuos SET 
                    status = 'agendado',
                    data_agendamento = ?,
                    local_coleta = ?,
                    observacoes_empresa = ?
                    WHERE id = ?");

                $data_hora = $data_coleta . ' ' . $hora_coleta;
                $stmt->bind_param("sssi", $data_hora, $local_coleta, $mensagem, $residuo_id);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Resposta enviada com sucesso']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Erro ao enviar resposta']);
                }
            }
            break;

        default:
            echo json_encode(['error' => 'Ação não encontrada']);
    }
    exit;
}

// Se não é uma requisição AJAX, carregar dados iniciais
$stats = getStats($connection);
$material_stats = getMaterialStats($connection);
$recent_activities = getRecentActivities($connection);
