<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir as dependências necessárias
$connection = null;

try {
    $connection = mysqli_connect("localhost", "root", "", "sr");
} catch (\Throwable $th) {

    echo "404";
}


$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('Usuário não autenticado');
}

$user_id = (string) $user_id;

// Buscar todos os materiais do usuário
function buscarHistoricoMateriais($connection, $user_id)
{
    $stmt = $connection->prepare("
    SELECT 
  *
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
function buscarMaterialPorId($connection, $material_id, $user_id)
{
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

    header("Location: historico");
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
function formatarTipoMaterial($tipo)
{
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
function formatarData($data)
{
    return date('d/m/Y H:i', strtotime($data));
}

include 'layouts/header.php'
?>

<main class="relative flex flex-col items-center w-full gap-4 min-h-screen bg-teal-800 overflow-hidden pb-10 px-2 md:px-0">

    <img src="public/image/Reciclagem-mecanica.jpg" alt="bg" class="fixed object-cover h-[50%] opacity-5 z-0">

    <section class="container flex flex-col gap-4 min-h-96 z-10 mt-20 rounded-2xl">

        <div class="flex items-center justify-between">
            <a href="perfil" class="btn secondary">
                <i class="ri-arrow-left-s-line"></i> Voltar
            </a>
            <h1 class="text-white text-2xl font-bold">
                <i class="ri-history-line"></i> Histórico de Materiais
            </h1>
        </div>

        <!-- Mensagens de feedback -->
        <?php if (isset($_SESSION['material_excluido'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                <i class="ri-check-line"></i> Material excluído com sucesso!
            </div>
            <?php unset($_SESSION['material_excluido']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['erro_exclusao'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <i class="ri-error-warning-line"></i> Erro ao excluir material!
            </div>
            <?php unset($_SESSION['erro_exclusao']); ?>
        <?php endif; ?>

        <div class="rounded-2xl overflow-hidden shadow-2xl">
            <?php if (empty($historico_materiais)): ?>
                <div class="bg-white text-gray-500 p-8 text-center">
                    <i class="ri-inbox-line text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Nenhum material cadastrado</h3>
                    <p>Você ainda não cadastrou nenhum material reciclável.</p>
                    <a href="reciclagem" class="inline-block mt-4 bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                        <i class="ri-add-line"></i> Cadastrar Material
                    </a>
                </div>
            <?php else: ?>
                <table class="bg-white text-gray-700 w-full">
                    <thead class="text-left bg-gradient-to-r from-teal-600 to-teal-700 text-white">
                        <tr class="h-12">
                            <th class="p-4 font-semibold">
                                <i class="ri-list-check-3"></i> Descrição
                            </th>
                            <th class="p-4 font-semibold">
                                <i class="ri-calendar-line"></i> Data
                            </th>
                            <th class="p-4 font-semibold">
                                <i class="ri-money-dollar-circle-line"></i> Valor
                            </th>
                            <th class="p-4 font-semibold text-center">
                                <i class="ri-settings-line"></i> Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historico_materiais as $material): ?>
                            <tr class="odd:bg-gray-50 even:bg-white hover:bg-teal-50 group border-b border-gray-100 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                                        <div>
                                            <div class="font-semibold text-gray-800">
                                                <?= formatarTipoMaterial($material['tipo_material']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= $material['quantidade_estimada_kg'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-gray-600">
                                    <?= formatarData($material['created_at']) ?>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="ri-coins-line mr-1"></i>
                                         AOA
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
    <button onclick="abrirModal(1)" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
         Detalhes
    </button>

                                        <form method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este material?')">
                                            <input type="hidden" name="material_id" value="<?= $material['id'] ?>">
                                            <button
                                                type="submit"
                                                name="excluir_material"
                                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors"
                                                title="Excluir">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Resumo -->
                <div class="bg-gradient-to-r from-teal-600 to-teal-700 text-white p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold"><?= count($historico_materiais) ?></div>
                                <div class="text-sm opacity-90">Materiais</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold">
                                    <?= number_format(array_sum(array_column($historico_materiais, 'preco_total')), 2, ',', '.') ?>
                                </div>
                                <div class="text-sm opacity-90">Total AOA</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <i class="ri-recycle-line text-4xl opacity-70"></i>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </section>

    <!-- Modal de Detalhes -->
    <div id="modalDetalhes" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-gradient-to-r from-teal-600 to-teal-700 text-white p-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">
                        <i class="ri-information-line"></i> Detalhes do Material
                    </h2>
                    <button onclick="fecharModal()" class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-lg">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
            </div>

            <div id="conteudoModal" class="p-6">
                <!-- Conteúdo será carregado via JavaScript -->
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600"></div>
                </div>
            </div>
        </div>
    </div>

<script>
    function abrirModal(materialId) {
        document.getElementById('modalDetalhes').classList.remove('hidden');
        document.getElementById('modalDetalhes').classList.add('flex');

        // Simulação de dados para demonstração (substitua pela sua chamada AJAX real)
        setTimeout(() => {
            const data = {
                tipo_material: 'plastico_kg',
                estado_conservacao: 'Bom',
                quantidade_estimada_kg: '5.5',
                unidade: 'kg',
                localizacao: 'Luanda, Angola',
                created_at: '2024-01-15 14:30:00',
                preco_total: '1500.00',
                descricao: 'Garrafas PET limpas e separadas',
                foto: 'https://via.placeholder.com/300x200?text=Foto+do+Material'
            };

            document.getElementById('conteudoModal').innerHTML = `
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4 text-lg border-b pb-2">
                            <i class="ri-information-line text-teal-600"></i> Informações Básicas
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Tipo de Material</label>
                                <div class="text-gray-800 font-semibold">${formatarTipoMaterial(data.tipo_material)}</div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Estado de Conservação</label>
                                <div class="text-gray-800">${data.estado_conservacao}</div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Quantidade</label>
                                <div class="text-gray-800">${data.quantidade_estimada_kg} ${data.unidade}</div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Localização</label>
                                <div class="text-gray-800">${data.localizacao}</div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Data de Cadastro</label>
                                <div class="text-gray-800">${formatarDataJS(data.created_at)}</div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Valor Estimado</label>
                                <div class="text-green-600 font-bold text-lg">
                                    <i class="ri-coins-line"></i> ${parseFloat(data.preco_total).toLocaleString('pt-AO', {minimumFractionDigits: 2, maximumFractionDigits: 2})} AOA
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-4 text-lg border-b pb-2">
                            <i class="ri-file-text-line text-teal-600"></i> Descrição e Imagem
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Descrição</label>
                                <div class="text-gray-800 bg-gray-50 p-3 rounded-lg">${data.descricao || 'Sem descrição'}</div>
                            </div>
                            ${data.foto ? `
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Foto</label>
                                    <div class="mt-2">
                                        <img src="${data.foto}" alt="Foto do material" class="w-full h-48 object-cover rounded-lg border">
                                    </div>
                                </div>
                            ` : `
                                <div class="text-center py-8 bg-gray-50 rounded-lg">
                                    <i class="ri-image-line text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-gray-500">Nenhuma foto disponível</p>
                                </div>
                            `}
                        </div>
                    </div>
                </div>
            `;
        }, 1000);
    }

    function fecharModal() {
        document.getElementById('modalDetalhes').classList.add('hidden');
        document.getElementById('modalDetalhes').classList.remove('flex');
    }

    function formatarTipoMaterial(tipo) {
        const tipos = {
            'papel': 'Papel',
            'papelao': 'Papelão',
            'plastico_kg': 'Plástico (KG)',
            'plastico_un': 'Plástico (Unidade)',
            'vidro': 'Vidro',
            'aluminio': 'Alumínio',
            'ferro': 'Ferro',
            'cobre': 'Cobre',
            'lata_kg': 'Lata (KG)',
            'lata_un': 'Lata (Unidade)',
            'eletronico_kg': 'Eletrônico (KG)',
            'eletronico_un': 'Eletrônico (Unidade)',
            'bateria_pilha': 'Bateria/Pilha',
            'oleo': 'Óleo',
            'tecido': 'Tecido',
        };
        return tipos[tipo] || tipo.charAt(0).toUpperCase() + tipo.slice(1);
    }

    function formatarDataJS(data) {
        return new Date(data).toLocaleString('pt-AO', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Fechar modal clicando fora dele
    document.getElementById('modalDetalhes').addEventListener('click', function(e) {
        if (e.target === this) {
            fecharModal();
        }
    });
</script>

<style>
    /* Animação do modal */
    #modalDetalhes {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Scrollbar personalizada */
    #modalDetalhes .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    #modalDetalhes .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    #modalDetalhes .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #14b8a6;
        border-radius: 3px;
    }

    #modalDetalhes .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #0f766e;
    }
</style>
</main>

</body>
</html>


