<?php
session_start();

// Corrigir os caminhos com __DIR__
require_once __DIR__ . '/../../boostrap.php';
require_once __DIR__ . '/connection.php';

// Verifica se a conexão foi estabelecida
if (!isset($connection) || !$connection instanceof mysqli) {
    die("Erro de conexão com o banco de dados.");
}

$action = $_POST['action'] ?? '';

if ($action === 'register') {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';
    $localizacao = $_POST['localizacao'] ?? '';
    $tipo_usuario = 'cidadao';

    // === VALIDAÇÕES ===
    if (empty($nome) || strlen($nome) < 3) {
        header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("O nome deve ter pelo menos 3 caracteres."));
        exit;
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("Informe um e-mail válido."));
        exit;
    }

    $verifica_email = $connection->prepare("SELECT id FROM users WHERE email = ?");
    $verifica_email->bind_param("s", $email);
    $verifica_email->execute();
    $verifica_email->store_result();

    if ($verifica_email->num_rows > 0) {
        header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("Este e-mail já está cadastrado."));
        exit;
    }
    $verifica_email->close();

    if (empty($senha) || strlen($senha) < 6) {
        header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("A senha deve ter pelo menos 6 caracteres."));
        exit;
    }

    if ($senha !== $confirmar) {
        header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("As senhas não coincidem."));
        exit;
    }

    if (empty($localizacao)) {
        header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("Por favor, selecione uma localização."));
        exit;
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $connection->prepare("INSERT INTO users (nome, email, senha, tipo_usuario, localizacao, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $nome, $email, $senha_hash, $tipo_usuario, $localizacao);

    if ($stmt->execute()) {
        echo "<script>alert('Cadastro feito com sucesso!'); window.location.href = '/PROJECTO-RECICLA-FACIL/login';</script>";
        exit;
    } else {
        header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("Erro ao cadastrar: " . $stmt->error));
        exit;
    }

    $stmt->close();

} elseif ($action === 'login') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {
        header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("Preencha todos os campos."));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("E-mail inválido."));
        exit;
    }

    $stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nome'] = $usuario['nome'];
            $_SESSION['user_tipo'] = $usuario['tipo_usuario'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['user_local'] = $usuario['localizacao'];




            if ($usuario['tipo_usuario'] === 'empresa') {
                header("Location: /PROJECTO-RECICLA-FACIL/empresa/dashboard");
            } else {
                header("Location: /PROJECTO-RECICLA-FACIL/home");
            }
            exit;
        } else {
            header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("Senha incorreta."));
            exit;
        }
    } else {
        header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("Usuário não encontrado."));
        exit;
    }

    $stmt->close();

} else {
    header("Location: /PROJECTO-RECICLA-FACIL/login?erro=" . urlencode("Ação não reconhecida."));
    exit;
}

// Fecha conexão
if (isset($connection) && $connection instanceof mysqli) {
    $connection->close();
}
