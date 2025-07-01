<?php

const PATH_VIEWS = '/app/views/';

function view(string $file_name)
{
    $full_path = __DIR__ . PATH_VIEWS . $file_name;

    if (file_exists($full_path)) {
        include_once $full_path;
    } else {
        die("A view '$file_name' não foi encontrada em: $full_path");
    }
}

// URL atual
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Corrigir base do projeto
$base = '/PROJECTO-RECICLA-FACIL';
if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
    if ($uri === '') $uri = '/';
}

// Definir as rotas
$routes = [
    '/' => 'inicio.php',
    '/login' => 'login.php',
    '/auth' => 'app/database/auth.php',
    '/home' => 'inicio.php',
    '/sobre' => 'sobre.php',
    '/perfil' => 'perfil.php',
    '/reciclagem' => 'reciclagem.php',
    '/admin' => 'admin/dashboard.php',
    '/empresa/dashboard' => 'empresa/dashboard.php',
    '/correio' => 'correio.php',
    '/sair' => '../controller/logout.php',
    '/historico' => '/historico.php',
    '/responder' => 'app/controller/responderController.php',
    '/materialController' => '../controller/materialController.php'


];

// Estado do usuário
$isLogged = isset($_SESSION['user_id']);
$userType = $_SESSION['user_tipo'] ?? null;

// Rotas públicas (sem login)
$publicRoutes = ['/', '/login', '/auth'];

// ⚠️ BLOQUEIOS E RESTRIÇÕES

// 1. Se não estiver logado e rota não for pública → redireciona
if (!$isLogged && !in_array($uri, $publicRoutes)) {
    header('Location: /PROJECTO-RECICLA-FACIL/');
    exit;
}

// 2. Se estiver logado, bloqueia acesso às páginas públicas (ex: '/', /login)
if ($isLogged && in_array($uri, ['/', '/login'])) {
    header('Location: /PROJECTO-RECICLA-FACIL/home');
    exit;
}

// 3. Se for usuário cidadao e tentar acessar qualquer rota da pasta empresa/
if ($isLogged && $userType === 'cidadao' && str_starts_with($uri, '/empresa')) {
    http_response_code(403);
    die("<h1>403 - Acesso proibido</h1><p>Você não tem permissão para acessar essa área.</p>");
}

// ✅ RENDERIZAÇÃO DA ROTA
if (array_key_exists($uri, $routes)) {
    $file = $routes[$uri];

    if (str_starts_with($file, 'app/database/')) {
        require_once __DIR__ . '/' . $file;
    } else {
        view($file);
    }
}
