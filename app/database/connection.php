<?php


$connection = null;

try {
    $connection = mysqli_connect("localhost", "root", "", "sr");

} catch (\Throwable $th) {

    echo "404";
}

if (!$connection) {

    die("Connection failed: " . mysqli_connect_error());

}



$consulta = "SELECT * FROM users WHERE email='reparaangola@gmail.com'";

$resp = mysqli_query($connection, $consulta);


if ($resp->num_rows <= 0) {

    $nome = 'Repara Angola Admin';
    $email = 'reparaangola@gmail.com';
    $senha = '12345678';
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $localizacao = 'Luanda';
    $tipo_usuario = 'empresa';

    $stmt = $connection->prepare("INSERT INTO users (nome, email, senha, tipo_usuario, localizacao, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $nome, $email, $senha_hash, $tipo_usuario, $localizacao);

    if ($stmt->execute()) {
        echo "Usuário empresa criado com sucesso!";
    } else {
        echo "Erro ao criar: " . $stmt->error;
    }

}
