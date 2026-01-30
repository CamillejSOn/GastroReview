<?php
require 'conexao.php';

if (!isset($_POST['restaurante'], $_POST['localizacao'], $_POST['nota'], $_POST['comentario'], $_POST['bio'])) {
die("Todos os campos são obrigatórios.");
}

$nome = $_POST['restaurante'];
$localizacao = $_POST['localizacao'];
$nota = $_POST['nota'];
$comentario = $_POST['comentario'];
$bio = $_POST['bio'];

$stmt = $pdo->prepare("INSERT INTO reviews (restaurante, localizacao, nota, comentario, bio) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$nome, $localizacao, $nota, $comentario, $bio]);

header("Location: index.php");
exit;