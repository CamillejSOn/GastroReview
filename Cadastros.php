<?php
require 'conexao.php';

if (!isset($_POST['nome'], $_POST['senha'], $_POST['cidade'], $_POST['email'])) {
die("Todos os campos são obrigatórios.");
}

$nome = $_POST['nome'];
$senha = $_POST['senha'];
$cidade = $_POST['cidade'];
$email = $_POST['email'];

$stmt = $pdo->prepare("INSERT INTO usuarios (nome, senha, cidade, email) VALUES (?, ?, ?, ?)");
$stmt->execute([$nome, $senha, $cidade, $email]);

header("Location: cadastro.php");
exit;