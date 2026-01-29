<?php
require 'conexao.php';

if (!isset($_POST['restaurante'], $_POST['localizacao'], $_POST['nota'], $_POST['comentario'])) {
    die("Todos os campos são obrigatórios.");
}

$nome = $_POST['restaurante'];
$localizacao = $_POST['localizacao'];
$nota = $_POST['nota'];
$comentario = $_POST['comentario'];

$stmt = $pdo->prepare("INSERT INTO reviews (restaurante, localizacao, nota, comentario) VALUES (?, ?, ?, ?)");
$stmt->execute([$nome, $localizacao, $nota, $comentario]);

header("Location: index.php");
exit;
