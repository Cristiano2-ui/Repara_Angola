
<?php
$connection = null;

try {
    $connection = mysqli_connect("localhost", "root", "", "sr");

} catch (\Throwable $th) {

     echo "404";
}
require_once realpath(__DIR__ . '/../../../boostrap.php');
$periodo = $_GET['periodo'] ?? 30;


// Verifica se é empresa logada
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'empresa') {
    header("Location: /PROJECTO-RECICLA-FACIL/login");
    exit;
}
?>

<!-- Dashboard HTML -->
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Repara Angola - Gestão de Reciclagem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'eco-green': '#22c55e',
                        'eco-dark': '#16a34a',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-xl border-r border-gray-200 fixed h-full z-10">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="bg-eco-green p-2 rounded-lg">
                        <i class="fas fa-recycle text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Repara Angola</h2>
                        <p class="text-sm text-gray-500">Dashboard Admin</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <a href="#" class="nav-item active" data-tab="overview">
                    <i class="fas fa-chart-pie"></i>
                    <span>Visão Geral</span>
                </a>
                <a href="#" class="nav-item" data-tab="users">
                    <i class="fas fa-users"></i>
                    <span>Usuários</span>
                </a>
                <a href="#" class="nav-item" data-tab="active-users">
                    <i class="fas fa-user-check"></i>
                    <span>Usuários Ativos</span>
                </a>
                <a href="#" class="nav-item" data-tab="collection-points">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Pontos de Coleta</span>
                </a>
                <a href="#" class="nav-item" data-tab="residues">
                    <i class="fas fa-trash-alt"></i>
                    <span>Resíduos Enviados</span>
                </a>
                <a href="#" class="nav-item" data-tab="reports">
                    <i class="fas fa-chart-bar"></i>
                    <span>Relatórios</span>
                </a>
            </nav>

            <div class="absolute bottom-4 left-4 right-4">
                <div class="bg-eco-green bg-opacity-10 rounded-lg p-4">
                    <p class="text-sm text-eco-dark font-medium">Repara Angola</p>
                    <p class="text-xs text-gray-600">Juntos por um futuro sustentável</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard de Reciclagem</h1>
                        <p class="text-gray-600">Gerencie todo o sistema de reciclagem de Angola</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-eco-green bg-opacity-10 px-4 py-2 rounded-full">
                            <span class="text-eco-dark font-medium text-sm">Online</span>
                        </div>
                        <div class="w-10 h-10 bg-eco-green rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="p-6">
                <!-- Overview Tab -->
                <div id="overview" class="tab-content">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Total Usuários</p>
                                    <p class="text-2xl font-bold text-gray-800">247</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <i class="fas fa-users text-blue-600"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Usuários Ativos</p>
                                    <p class="text-2xl font-bold text-gray-800">92</p>
                                </div>
                                <div class="bg-eco-green bg-opacity-20 p-3 rounded-lg">
                                    <i class="fas fa-user-check text-eco-green"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Resíduos Coletados</p>
                                    <p class="text-2xl font-bold text-gray-800">3,456</p>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-lg">
                                    <i class="fas fa-trash-alt text-yellow-600"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm">Pontos de Coleta</p>
                                    <p class="text-2xl font-bold text-gray-800">18</p>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-lg">
                                    <i class="fas fa-map-marker-alt text-purple-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Materiais Mais Reciclados</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Plástico</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-eco-green h-2 rounded-full" style="width: 85%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500">85%</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Papel</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-eco-green h-2 rounded-full" style="width: 72%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500">72%</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Vidro</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-eco-green h-2 rounded-full" style="width: 58%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500">58%</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Metal</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-eco-green h-2 rounded-full" style="width: 45%"></div>
                                        </div>
                                        <span class="text-sm text-gray-500">45%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Atividades Recentes</h3>
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-eco-green bg-opacity-20 p-2 rounded-full">
                                        <i class="fas fa-plus text-eco-green text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">Novo resíduo cadastrado</p>
                                        <p class="text-xs text-gray-500">João Silva - Plástico 5kg - Luanda</p>
                                        <p class="text-xs text-gray-400">2 minutos atrás</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="bg-blue-100 p-2 rounded-full">
                                        <i class="fas fa-user text-blue-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">Novo usuário registrado</p>
                                        <p class="text-xs text-gray-500">Maria Fernandes - Benguela</p>
                                        <p class="text-xs text-gray-400">15 minutos atrás</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="bg-yellow-100 p-2 rounded-full">
                                        <i class="fas fa-check text-yellow-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">Coleta realizada</p>
                                        <p class="text-xs text-gray-500">Ponto de Coleta Maianga - 15 itens</p>
                                        <p class="text-xs text-gray-400">1 hora atrás</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
// Buscar todos os usuários
$query = "SELECT id, nome, email, localizacao, created_at, tipo_usuario FROM users ORDER BY created_at DESC";
$result = $connection->query($query);
?>

<!-- Users Tab -->
<div id="users" class="tab-content hidden">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Todos os Usuários</h3>
                <div class="flex space-x-2">
                    <input type="text" placeholder="Buscar usuário..." class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-green">
                    <button class="bg-eco-green text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localização</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Registro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($usuario = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full bg-eco-green flex items-center justify-center text-white font-medium" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2322c55e'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E" alt="">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($usuario['nome']) ?></div>
                                            <div class="text-sm text-gray-500">ID: #<?= str_pad($usuario['id'], 3, '0', STR_PAD_LEFT) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($usuario['email']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($usuario['localizacao']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($usuario['created_at'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-eco-green bg-opacity-20 text-eco-dark">Ativo</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-eco-green hover:text-eco-dark mr-3">Ver</button>
                                    <button class="text-red-600 hover:text-red-900">Desativar</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Nenhum usuário encontrado</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
// Buscar usuários ativos que já enviaram materiais
$query = "SELECT 
    u.id, u.nome, u.email, u.localizacao, u.created_at,
    COUNT(r.id) as total_reciclagens,
    SUM(r.preco_total) as valor_total
FROM users u 
INNER JOIN residuos r ON u.id = r.user_id 
WHERE u.tipo_usuario = 'cidadao'
GROUP BY u.id, u.nome, u.email, u.localizacao, u.created_at
ORDER BY total_reciclagens DESC";

$result = $connection->query($query);
?>

<!-- Active Users Tab -->
<div id="active-users" class="tab-content hidden">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Usuários Ativos (Com Reciclagens)</h3>
            <p class="text-sm text-gray-600">Usuários que já enviaram resíduos para reciclagem</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php 
                $contador = 0;
                while ($usuario = $result->fetch_assoc()): 
                    $contador++;
                    $impacto = min(100, ($usuario['total_reciclagens'] * 1.5)); // Calcula impacto baseado nas reciclagens
                    $isTopUser = $contador === 1; // Primeiro usuário é o top
                ?>
                    <div class="<?= $isTopUser ? 'bg-gradient-to-r from-eco-green to-green-400 text-white' : 'bg-white border border-gray-200' ?> rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="<?= $isTopUser ? 'bg-white bg-opacity-20' : 'bg-eco-green bg-opacity-20' ?> p-3 rounded-lg">
                                <i class="fas fa-user-circle text-2xl <?= $isTopUser ? '' : 'text-eco-green' ?>"></i>
                            </div>
                            <span class="<?= $isTopUser ? 'bg-white bg-opacity-20' : 'bg-green-100 text-green-800' ?> px-3 py-1 rounded-full text-sm">
                                <?= $isTopUser ? 'Top Reciclador' : 'Ativo' ?>
                            </span>
                        </div>
                        <h4 class="text-lg font-semibold <?= $isTopUser ? '' : 'text-gray-800' ?>">
                            <?= htmlspecialchars($usuario['nome']) ?>
                        </h4>
                        <p class="text-sm <?= $isTopUser ? 'opacity-90' : 'text-gray-600' ?>">
                            <?= $usuario['total_reciclagens'] ?> reciclagens realizadas
                        </p>
                        <p class="text-sm <?= $isTopUser ? 'opacity-75' : 'text-gray-500' ?>">
                            <?= htmlspecialchars($usuario['localizacao']) ?> • Membro desde <?= date('M/Y', strtotime($usuario['created_at'])) ?>
                        </p>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm mb-1 <?= $isTopUser ? '' : 'text-gray-600' ?>">
                                <span>Impacto Ambiental</span>
                                <span><?= round($impacto) ?>%</span>
                            </div>
                            <div class="w-full <?= $isTopUser ? 'bg-white bg-opacity-20' : 'bg-gray-200' ?> rounded-full h-2">
                                <div class="<?= $isTopUser ? 'bg-white' : 'bg-eco-green' ?> h-2 rounded-full" style="width: <?= round($impacto) ?>%"></div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-recycle text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Nenhum usuário ativo encontrado</p>
                    <p class="text-sm text-gray-400">Usuários aparecerão aqui após enviarem materiais</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

                <!-- Collection Points Tab -->
                <div id="collection-points" class="tab-content hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800">Pontos de Coleta em Angola</h3>
                                    <button class="bg-eco-green text-white px-4 py-2 rounded-lg hover:bg-eco-dark transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Adicionar Ponto
                                    </button>
                                </div>
                                <div class="bg-gray-100 rounded-lg h-96 flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-map text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-600">Mapa interativo dos pontos de coleta</p>
                                        <p class="text-sm text-gray-500">Integração com Google Maps/OpenStreetMap</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                <h4 class="font-semibold text-gray-800 mb-4">Pontos Ativos</h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-800">Centro de Luanda</p>
                                            <p class="text-sm text-gray-600">Rua da Missão, Luanda</p>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-eco-green bg-opacity-20 text-eco-dark mt-1">Operacional</span>
                                        </div>
                                        <i class="fas fa-map-marker-alt text-eco-green"></i>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-800">Maianga</p>
                                            <p class="text-sm text-gray-600">Av. Deolinda Rodrigues</p>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-eco-green bg-opacity-20 text-eco-dark mt-1">Operacional</span>
                                        </div>
                                        <i class="fas fa-map-marker-alt text-eco-green"></i>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-800">Benguela Centro</p>
                                            <p class="text-sm text-gray-600">Rua Alvaro Ferreira</p>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 mt-1">Manutenção</span>
                                        </div>
                                        <i class="fas fa-map-marker-alt text-yellow-600"></i>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-800">Huambo</p>
                                            <p class="text-sm text-gray-600">Bairro Comercial</p>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-eco-green bg-opacity-20 text-eco-dark mt-1">Operacional</span>
                                        </div>
                                        <i class="fas fa-map-marker-alt text-eco-green"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                <h4 class="font-semibold text-gray-800 mb-4">Estatísticas dos Pontos</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total de Pontos</span>
                                        <span class="font-semibold text-gray-800">18</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Pontos Ativos</span>
                                        <span class="font-semibold text-eco-green">15</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Em Manutenção</span>
                                        <span class="font-semibold text-yellow-600">3</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Coletas Hoje</span>
                                        <span class="font-semibold text-blue-600">47</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


<?php
// Buscar todos os resíduos com dados do usuário
$query = "SELECT 
    r.id, r.tipo_material, r.estado_conservacao, r.descricao, r.foto, 
    r.localizacao, r.quantidade_estimada_kg, r.unidade, r.preco_total, r.created_at,
    u.id as usuario_id,
    u.nome as usuario_nome, u.email as usuario_email
FROM residuos r 
INNER JOIN users u ON r.user_id = u.id 
ORDER BY r.created_at DESC";



$result = $connection->query($query);

// Função para obter ícone baseado no tipo de material
function getIconByType($tipo) {
    $icons = [
        'papel' => 'fas fa-newspaper text-green-600',
        'papelao' => 'fas fa-newspaper text-green-600',
        'plastico_kg' => 'fas fa-recycle text-blue-600',
        'plastico_un' => 'fas fa-recycle text-blue-600',
        'vidro' => 'fas fa-wine-bottle text-purple-600',
        'aluminio' => 'fas fa-recycle text-gray-600',
        'ferro' => 'fas fa-recycle text-gray-600',
        'cobre' => 'fas fa-recycle text-orange-600',
        'lata_kg' => 'fas fa-recycle text-gray-600',
        'lata_un' => 'fas fa-recycle text-gray-600',
        'eletronico_kg' => 'fas fa-laptop text-red-600',
        'eletronico_un' => 'fas fa-laptop text-red-600',
        'bateria_pilha' => 'fas fa-battery-half text-yellow-600',
        'oleo' => 'fas fa-oil-can text-brown-600',
        'tecido' => 'fas fa-tshirt text-pink-600'
    ];
    return $icons[$tipo] ?? 'fas fa-recycle text-gray-600';
}

// Função para obter cor do fundo do ícone
function getBgColorByType($tipo) {
    $colors = [
        'papel' => 'bg-green-100',
        'papelao' => 'bg-green-100',
        'plastico_kg' => 'bg-blue-100',
        'plastico_un' => 'bg-blue-100',
        'vidro' => 'bg-purple-100',
        'aluminio' => 'bg-gray-100',
        'ferro' => 'bg-gray-100',
        'cobre' => 'bg-orange-100',
        'lata_kg' => 'bg-gray-100',
        'lata_un' => 'bg-gray-100',
        'eletronico_kg' => 'bg-red-100',
        'eletronico_un' => 'bg-red-100',
        'bateria_pilha' => 'bg-yellow-100',
        'oleo' => 'bg-yellow-100',
        'tecido' => 'bg-pink-100'
    ];
    return $colors[$tipo] ?? 'bg-gray-100';
}

// Função para formatar nome do material
function formatMaterialName($tipo) {
    $names = [
        'papel' => 'Papel',
        'papelao' => 'Papelão',
        'plastico_kg' => 'Plástico',
        'plastico_un' => 'Plástico',
        'vidro' => 'Vidro',
        'aluminio' => 'Alumínio',
        'ferro' => 'Ferro',
        'cobre' => 'Cobre',
        'lata_kg' => 'Latas',
        'lata_un' => 'Latas',
        'eletronico_kg' => 'Eletrônicos',
        'eletronico_un' => 'Eletrônicos',
        'bateria_pilha' => 'Baterias/Pilhas',
        'oleo' => 'Óleo',
        'tecido' => 'Tecido'
    ];
    return $names[$tipo] ?? ucfirst($tipo);
}
?>

<!-- Residues Tab -->
<div id="residues" class="tab-content hidden">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Resíduos Enviados</h3>
                    <p class="text-sm text-gray-600">Gerencie todos os resíduos cadastrados pelos usuários</p>
                </div>
                <div class="flex space-x-2">
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-green">
                        <option>Todos os tipos</option>
                        <option>Plástico</option>
                        <option>Papel</option>
                        <option>Vidro</option>
                        <option>Metal</option>
                    </select>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-green">
                        <option>Todos os status</option>
                        <option>Pendente</option>
                        <option>Agendado</option>
                        <option>Coletado</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resíduo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localização</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($residuo = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="<?= getBgColorByType($residuo['tipo_material']) ?> p-2 rounded-lg mr-3">
                                            <i class="<?= getIconByType($residuo['tipo_material']) ?>"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= formatMaterialName($residuo['tipo_material']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= htmlspecialchars($residuo['estado_conservacao']) ?> - 
                                                <?= htmlspecialchars(substr($residuo['descricao'], 0, 30)) ?><?= strlen($residuo['descricao']) > 30 ? '...' : '' ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($residuo['usuario_nome']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($residuo['usuario_email']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= $residuo['quantidade_estimada_kg'] ?> <?= $residuo['unidade'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($residuo['localizacao']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d/m/Y', strtotime($residuo['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pendente
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
<button
    type="button"
    class="bg-eco-green text-white px-3 py-1 rounded-lg text-xs hover:bg-eco-dark mr-2"
    onclick="openMessageModal(
        '<?= htmlspecialchars($residuo['usuario_nome'], ENT_QUOTES) ?>',
        '<?= formatMaterialName($residuo['tipo_material']) ?>',
        <?= (int)$residuo['id'] ?>,
        <?= (int)$residuo['usuario_id'] ?>
    )"
>
    <i class="fas fa-reply mr-1"></i>Responder
</button>

                                    <button class="text-blue-600 hover:text-blue-900 text-xs">Ver Detalhes</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-recycle text-4xl text-gray-300 mb-4"></i>
                                <p>Nenhum resíduo encontrado</p>
                                <p class="text-sm text-gray-400">Os resíduos enviados pelos usuários aparecerão aqui</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

  <!-- Message Modal -->
<div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
        <form action="/PROJECTO-RECICLA-FACIL/app/controller/mensagemController.php" method="POST">
           <input type="hidden" name="user_id" id="modalUserId">
            <input type="hidden" name="residuo_id" id="modalResiduoId">


            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Responder Solicitação</h3>
                    <button type="button" onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p id="modalSubtitle" class="text-sm text-gray-600 mt-1"></p>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Local de Coleta</label>
                   <select name="local_coleta" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-green">
    <option value="">Selecione o local de coleta</option>
    <option>Luanda - Centro: Rua da Missão</option>
    <option>Bengo - Caxito: Largo do Comércio</option>
    <option>Benguela - Centro: Rua Álvaro Ferreira</option>
    <option>Bié - Kuito: Praça da Independência</option>
    <option>Cabinda - Centro: Avenida 14 de Abril</option>
    <option>Cuando Cubango - Menongue: Bairro Popular</option>
    <option>Cuanza Norte - Ndalatando: Rua das Acácias</option>
    <option>Cuanza Sul - Sumbe: Avenida Principal</option>
    <option>Cunene - Ondjiva: Bairro da Saúde</option>
    <option>Huambo - Bairro Comercial</option>
    <option>Huíla - Lubango: Largo da Revolução</option>
    <option>Lunda Norte - Dundo: Rua dos Diamantes</option>
    <option>Lunda Sul - Saurimo: Avenida Marginal</option>
    <option>Malanje - Centro: Rua das Palmeiras</option>
    <option>Moxico - Luena: Bairro Militar</option>
    <option>Namibe - Moçâmedes: Rua do Porto</option>
    <option>Uíge - Centro: Avenida Samora Machel</option>
    <option>Zaire - M'Banza Congo: Rua do Governo</option>
    <option>Coleta domiciliar</option>
</select>

                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data</label>
                        <input type="date" name="data_coleta" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-green">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Horário</label>
                        <input type="time" name="hora_coleta" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-green">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mensagem Adicional</label>
                    <textarea name="mensagem" rows="3" placeholder="Instruções especiais ou informações adicionais..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-green"></textarea>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex justify-end space-x-3">
                <button type="button" onclick="closeMessageModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                    Cancelar
                </button>

<button type="submit" class="bg-eco-green text-white px-6 py-2 rounded-lg hover:bg-eco-dark transition-colors">
  Enviar Resposta
</button>
            </div>
        </form>
    </div>
</div>

    <?php
// Função para buscar dados dos relatórios
function obterDadosRelatorio($connection, $user_id = null, $dias = 30) {
    $whereClause = $user_id ? "WHERE user_id = ?" : "";
    $dateClause = "AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
    
    // Total de resíduos coletados
    $sql = "SELECT 
                SUM(quantidade_estimada_kg) as total_residuos,
                COUNT(*) as total_registros,
                SUM(preco_total) as valor_total
            FROM residuos 
            $whereClause 
            $dateClause";
    
    $stmt = $connection->prepare($sql);
    if ($user_id) {
        $stmt->bind_param("si", $user_id, $dias);
    } else {
        $stmt->bind_param("i", $dias);
    }
    $stmt->execute();
    $dados_gerais = $stmt->get_result()->fetch_assoc();
    
    // Dados por província/localização
    $sql = "SELECT 
                localizacao,
                SUM(quantidade_estimada_kg) as total_kg,
                COUNT(*) as total_registros
            FROM residuos 
            $whereClause 
            $dateClause
            GROUP BY localizacao 
            ORDER BY total_kg DESC";
    
    $stmt = $connection->prepare($sql);
    if ($user_id) {
        $stmt->bind_param("si", $user_id, $dias);
    } else {
        $stmt->bind_param("i", $dias);
    }
    $stmt->execute();
    $dados_provincia = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    return [
        'geral' => $dados_gerais,
        'provincia' => $dados_provincia
    ];
}

// Função para calcular impacto ambiental
function calcularImpactoAmbiental($total_kg) {
    return [
        'co2_evitado' => round($total_kg * 0.535, 0), // 0.535 kg CO2 por kg de resíduo
        'arvores_salvas' => round($total_kg * 0.0165, 0), // 0.0165 árvores por kg
        'agua_economizada' => round($total_kg * 5.35, 0) // 5.35 litros por kg
    ];
}

// Obter dados do relatório
$user_id = $_SESSION['user_id'] ?? null;
$dados = obterDadosRelatorio($connection, $user_id, 30);
$total_residuos = $dados['geral']['total_residuos'] ?? 0;
$impacto = calcularImpactoAmbiental($total_residuos);
?>

<!-- Reports Tab -->
<div id="reports" class="tab-content hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Relatório Mensal</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total de Resíduos Coletados</span>
                    <span class="text-2xl font-bold text-eco-green"><?php echo number_format($total_residuos, 0, ',', '.'); ?> kg</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">CO₂ Evitado</span>
                    <span class="text-xl font-bold text-blue-600"><?php echo number_format($impacto['co2_evitado'], 0, ',', '.'); ?> kg</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Árvores Salvas</span>
                    <span class="text-xl font-bold text-green-600"><?php echo $impacto['arvores_salvas']; ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Economia de Água</span>
                    <span class="text-xl font-bold text-cyan-600"><?php echo number_format($impacto['agua_economizada'], 0, ',', '.'); ?> L</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Impacto por Província</h3>
            <div class="space-y-3">
                <?php 
                $cores = ['eco-green', 'blue-500', 'purple-500', 'yellow-500', 'red-500'];
                $cor_index = 0;
                
                foreach ($dados['provincia'] as $provincia): 
                    $percentual = $total_residuos > 0 ? ($provincia['total_kg'] / $total_residuos) * 100 : 0;
                    $cor = $cores[$cor_index % count($cores)];
                    $cor_index++;
                ?>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 bg-<?php echo $cor; ?> rounded-full"></div>
                        <span class="text-gray-700"><?php echo htmlspecialchars($provincia['localizacao']); ?></span>
                    </div>
                    <div class="text-right">
                        <span class="font-semibold text-gray-800"><?php echo number_format($provincia['total_kg'], 0, ',', '.'); ?> kg</span>
                        <p class="text-xs text-gray-500"><?php echo number_format($percentual, 1); ?>% do total</p>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($dados['provincia'])): ?>
                <div class="text-center text-gray-500 py-4">
                    <p>Nenhum dado disponível para o período selecionado</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Exportar Relatórios</h3>
            <div class="flex space-x-2">
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-eco-green" onchange="atualizarRelatorio(this.value)">
                    <option value="30">Últimos 30 dias</option>
                    <option value="90">Últimos 90 dias</option>
                    <option value="365">Último ano</option>
                    <option value="custom">Personalizado</option>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button class="bg-eco-green text-white p-4 rounded-lg hover:bg-eco-dark transition-colors" onclick="exportarRelatorio('excel')">
                <i class="fas fa-file-excel text-xl mb-2"></i>
                <p class="font-medium">Excel</p>
                <p class="text-sm opacity-90">Relatório completo</p>
            </button>
            <button class="bg-red-500 text-white p-4 rounded-lg hover:bg-red-600 transition-colors" onclick="exportarRelatorio('pdf')">
                <i class="fas fa-file-pdf text-xl mb-2"></i>
                <p class="font-medium">PDF</p>
                <p class="text-sm opacity-90">Relatório visual</p>
            </button>
            <button class="bg-blue-500 text-white p-4 rounded-lg hover:bg-blue-600 transition-colors" onclick="exportarRelatorio('dashboard')">
                <i class="fas fa-chart-line text-xl mb-2"></i>
                <p class="font-medium">Dashboard</p>
                <p class="text-sm opacity-90">Relatório interativo</p>
            </button>
        </div>
    </div>
</div>

<script>
function atualizarRelatorio(periodo) {
    if (periodo === 'custom') {
        // Implementar seletor de data personalizado
        return;
    }
    
    // Recarregar página com novo período
    window.location.href = window.location.pathname + '?periodo=' + periodo;
}

function exportarRelatorio(tipo) {
    // Implementar exportação de relatórios
    const periodo = document.querySelector('select').value;
    window.open(`export_report.php?type=${tipo}&period=${periodo}`, '_blank');
}
</script>

 
    <style>
        .nav-item {
            display: flex;
            align-items: center;
            space-x: 0.75rem;
            padding: 0.75rem 1rem;
            color: #6b7280;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .nav-item:hover {
            background-color: #f3f4f6;
            color: #22c55e;
        }

        .nav-item.active {
            background-color: #dcfce7;
            color: #16a34a;
            font-weight: 500;
        }

        .nav-item i {
            width: 1.25rem;
            margin-right: 0.75rem;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>

    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nav-item');
            const tabContents = document.querySelectorAll('.tab-content');

            // Show overview tab by default
            document.getElementById('overview').classList.add('active');

            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all nav items and tab contents
                    navItems.forEach(nav => nav.classList.remove('active'));
                    tabContents.forEach(tab => tab.classList.remove('active'));
                    
                    // Add active class to clicked nav item
                    this.classList.add('active');
                    
                    // Show corresponding tab content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });

        // Modal functions
        function openMessageModal(userName, itemName) {
            document.getElementById('messageModal').classList.remove('hidden');
            document.getElementById('modalSubtitle').textContent = `Para: ${userName} - ${itemName}`;
        }

        function closeMessageModal() {
            document.getElementById('messageModal').classList.add('hidden');
        }

        function insertQuickReply(text) {
            const textarea = document.querySelector('#messages textarea');
            if (textarea) {
                textarea.value = text;
            }
        }

        // Close modal when clicking outside
        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMessageModal();
            }
        });
    </script>

    <script>
function openMessageModal(nomeUsuario, tipoMaterial, residuoId, userId) {
    console.log("Abrindo modal com:", nomeUsuario, tipoMaterial, residuoId, userId);

    if (!userId || !residuoId) {
        alert("Erro: dados incompletos.");
        return;
    }

    document.getElementById('modalSubtitle').textContent =
        `Responder a ${nomeUsuario} sobre o resíduo: ${tipoMaterial}`;
    document.getElementById('modalUserId').value = userId;
    document.getElementById('modalResiduoId').value = residuoId;

    document.getElementById('messageModal').classList.remove('hidden');
}

function closeMessageModal() {
    document.getElementById('messageModal').classList.add('hidden');
}
</script>

</body>
</html>