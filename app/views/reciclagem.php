<?php
$mostrarModal = isset($_GET['sucesso']) && $_GET['sucesso'] == '1';
?>

<?php include 'layouts/header.php' ?>

<div class="flex flex-col justify-center items-center gap-4 py-20 px-4 w-full bg-teal-800 animate__animated animate__fadeIn">

  <div class="flex container">
    <a href="perfil" class="btn secondary mr-auto animate__animated animate__fadeInLeft">
      <i class="ri-arrow-left-s-line"></i> Voltar
    </a>
  </div>

  <form class="bg-white rounded-2xl shadow-lg p-10 max-w-3xl w-full animate__animated animate__zoomIn"
        method="post" action="materialController" enctype="multipart/form-data">
    <div class="text-center mb-8">
      <h2 class="text-2xl font-bold text-slate-800 animate__animated animate__pulse">Cadastro de Material</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">

      <!-- Tipo de Material -->
      <div class="flex flex-col relative">
        <label for="tipo" class="font-semibold text-slate-700 mb-1">Tipo de Material</label>
        <i class="ri-box-3-line absolute left-3 top-10 text-slate-400 text-xl"></i>
        <select id="tipo" name="tipo_material" required
                class="pl-10 p-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-600">
          <option value="">Selecione o tipo de material</option>
          <option value="papel">Papel (kg) - 100 KZ/kg</option>
          <option value="papelao">Papelão (kg) - 150 KZ/kg</option>
          <option value="plastico_kg">Plástico (kg) - 200 KZ/kg</option>
          <option value="vidro">Vidro (kg) - 50 KZ/kg</option>
          <option value="aluminio">Alumínio (kg) - 1200 KZ/kg</option>
          <option value="ferro">Ferro (kg) - 400 KZ/kg</option>
          <option value="cobre">Cobre (kg) - 2000 KZ/kg</option>
          <option value="lata_kg">Latas (kg) - 300 KZ/kg</option>
          <option value="eletronico_kg">Eletrônicos (kg) - 500 KZ/kg</option>
          <option value="eletronico_un">Eletrônicos (un) - 1000 KZ/un</option>
          <option value="bateria_pilha">Baterias e Pilhas (un) - 100 KZ/un</option>
          <option value="tecido">Tecidos (kg) - 200 KZ/kg</option>
        </select>
      </div>

      <!-- Estado -->
      <div class="flex flex-col relative">
        <label for="estado" class="font-semibold text-slate-700 mb-1">Estado</label>
        <i class="ri-shield-check-line absolute left-3 top-10 text-slate-400 text-xl"></i>
        <select id="estado" name="estado" required
                class="pl-10 p-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-600">
          <option value="">Selecione...</option>
          <option value="novo">Novo</option>
          <option value="bom">Bom</option>
          <option value="usado">Usado</option>
          <option value="danificado">Danificado</option>
        </select>
      </div>

      <!-- Descrição -->
      <div class="flex flex-col relative sm:col-span-2">
        <label for="descricao" class="font-semibold text-slate-700 mb-1">Descrição</label>
        <i class="ri-file-text-line absolute left-3 top-10 text-slate-400 text-xl"></i>
        <textarea id="descricao" name="descricao" placeholder="Descreva os detalhes do material..."
                  class="pl-10 p-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-600 min-h-[100px]"></textarea>
      </div>

      <!-- Foto -->
      <div class="flex flex-col relative">
        <label for="foto" class="font-semibold text-slate-700 mb-1">Foto</label>
        <i class="ri-image-line absolute left-3 top-10 text-slate-400 text-xl"></i>
        <input type="file" id="foto" name="foto" accept="image/*"
               class="pl-10 p-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-600">
      </div>
<!-- Localização -->
<div class="flex flex-col relative">
  <label for="localizacao" class="font-semibold text-slate-700 mb-1">Localização</label>
  <i class="ri-map-pin-line absolute left-3 top-10 text-slate-400 text-xl"></i>
  <select id="localizacao" name="localizacao"
          class="pl-10 p-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-600">
    <option value="">Selecione a localização</option>
    <option value="Bengo">Bengo</option>
    <option value="Benguela">Benguela</option>
    <option value="Bié">Bié</option>
    <option value="Cabinda">Cabinda</option>
    <option value="Cuando Cubango">Cuando Cubango</option>
    <option value="Cuanza Norte">Cuanza Norte</option>
    <option value="Cuanza Sul">Cuanza Sul</option>
    <option value="Cunene">Cunene</option>
    <option value="Huambo">Huambo</option>
    <option value="Huíla">Huíla</option>
    <option value="Luanda">Luanda</option>
    <option value="Lunda Norte">Lunda Norte</option>
    <option value="Lunda Sul">Lunda Sul</option>
    <option value="Malanje">Malanje</option>
    <option value="Moxico">Moxico</option>
    <option value="Namibe">Namibe</option>
    <option value="Uíge">Uíge</option>
    <option value="Zaire">Zaire</option>
  </select>
</div>


      <!-- Quantidade -->
      <div class="flex flex-col relative">
        <label for="quantidade" class="font-semibold text-slate-700 mb-1">Quantidade</label>
        <i class="ri-numbers-line absolute left-3 top-10 text-slate-400 text-xl"></i>
        <input type="number" id="quantidade" name="quantidade" min="1" placeholder="Ex: 15" required
               class="pl-10 p-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-600">
      </div>

      <!-- Unidade -->
      <div class="flex flex-col relative">
        <label for="unidade" class="font-semibold text-slate-700 mb-1">Unidade</label>
        <i class="ri-numbers-line absolute left-3 top-10 text-slate-400 text-xl"></i>
        <select id="unidade" name="unidade"
                class="pl-10 p-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-600">
          <option value="kilogramas">Kg</option>
          <option value="gramas">G</option>
          <option value="toneladas">T</option>
        </select>
      </div>

      <!-- Preço Unitário -->
      <div class="flex flex-col relative">
        <label for="preco_unitario" class="font-semibold text-slate-700 mb-1">Preço Unitário (KZ)</label>
        <i class="ri-money-dollar-circle-line absolute left-3 top-10 text-slate-400 text-xl"></i>
        <input type="text" id="preco_unitario" readonly placeholder="0 KZ"
               class="pl-10 p-3 rounded-lg border border-slate-300 bg-slate-100 focus:outline-none">
      </div>

      <!-- Valor Total -->
      <div class="flex flex-col relative">
        <label for="valor_total" class="font-semibold text-slate-700 mb-1">Valor Total (KZ)</label>
        <i class="ri-calculator-line absolute left-3 top-10 text-slate-400 text-xl"></i>
        <input type="text" id="valor_total" readonly placeholder="0 KZ"
               class="pl-10 p-3 rounded-lg border border-slate-300 bg-slate-100 focus:outline-none">
      </div>
    </div>

    <div class="flex justify-center">
      <button type="submit"
              class="bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl py-3 px-9 transition animate__animated animate__tada">
        Enviar Material
      </button>
    </div>
  </form>
</div>

<div id="modalConfirmacao" style="<?= $mostrarModal ? 'display:flex' : 'display:none' ?>" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
  <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full text-center animate__animated animate__zoomIn">
    <h2 class="text-2xl font-bold text-green-600 mb-4">✅ Material enviado!</h2>
    <p class="text-gray-700 mb-4">Seu material foi cadastrado com sucesso no sistema.</p>
    <button onclick="fecharModal()" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-6 rounded-xl">Fechar</button>
  </div>
</div>

<?php include 'layouts/footer.php' ?>

<!-- Scripts -->
<script>
  const precos = {
    papel: 100,
    papelao: 150,
    plastico_kg: 200,
    plastico_un: 20,
    vidro: 50,
    aluminio: 1200,
    ferro: 400,
    cobre: 2000,
    lata_kg: 300,
    lata_un: 15,
    eletronico_kg: 500,
    eletronico_un: 1000,
    bateria_pilha: 100,
    oleo: 250,
    tecido: 200
  };

  const tipoSelect = document.getElementById("tipo");
  const quantidadeInput = document.getElementById("quantidade");
  const precoInput = document.getElementById("preco_unitario");
  const totalInput = document.getElementById("valor_total");

  function atualizarPreco() {
    const tipo = tipoSelect.value;
    const quantidade = parseFloat(quantidadeInput.value) || 0;
    const precoUnitario = precos[tipo] || 0;
    const total = precoUnitario * quantidade;

    precoInput.value = precoUnitario + " KZ";
    totalInput.value = total + " KZ";
  }

  tipoSelect.addEventListener("change", atualizarPreco);
  quantidadeInput.addEventListener("input", atualizarPreco);

  function fecharModal() {
    document.getElementById('modalConfirmacao').style.display = 'none';
  }
</script>
<script>
  function fecharModal() {
    const modal = document.getElementById('modalConfirmacao');
    if (modal) modal.style.display = 'none';
  }
</script>
