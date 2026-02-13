<?php
session_start();
require 'conexao.php';

if (isset($_GET['id']) && isset($_SESSION['usuario_id'])) {
    $res_id = $_GET['id'];
    $user_id = $_SESSION['usuario_id'];
    $check = $pdo->prepare("SELECT * FROM favoritos WHERE usuario_id = ? AND restaurante_id = ?");
    $check->execute([$user_id, $res_id]);

    if ($check->rowCount() > 0) {
        $sql = "DELETE FROM favoritos WHERE usuario_id = ? AND restaurante_id = ?";
    } else {
        $sql = "INSERT INTO favoritos (usuario_id, restaurante_id) VALUES (?, ?)";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $res_id]);
}
header("Location: " . $_SERVER['HTTP_REFERER']);