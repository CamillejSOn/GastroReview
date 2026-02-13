<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_res = trim($_POST['restaurante']);
    $localizacao = trim($_POST['localizacao']);
    $categoria = $_POST['categoria']; 
    $nota = (int)$_POST['nota'];
    $comentario = trim($_POST['comentario']);
    $usuario_id = $_SESSION['usuario_id'];
    
    $imagem_nome = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $imagem_nome = md5(uniqid()) . "." . $extensao;
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $imagem_nome);
    }

    try {
        $pdo->beginTransaction();
        $stmt_res = $pdo->prepare("SELECT id FROM restaurantes WHERE nome = ?");
        $stmt_res->execute([$nome_res]);
        $res = $stmt_res->fetch();

        if ($res) {
            $restaurante_id = $res['id'];
        } else {
            $ins_res = $pdo->prepare("INSERT INTO restaurantes (nome, localizacao, categoria) VALUES (?, ?, ?)");
            $ins_res->execute([$nome_res, $localizacao, $categoria]);
            $restaurante_id = $pdo->lastInsertId();
        }
        $sql = "INSERT INTO reviews (usuario_id, restaurante_id, nota, comentario, imagem_caminho) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario_id, $restaurante_id, $nota, $comentario, $imagem_nome]);

        $pdo->commit();
        header("Location: tela-inicial.php?sucesso=1");
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro: " . $e->getMessage());
    }
}