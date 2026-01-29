<?php
session_start();
require 'conexao.php';
include 'Header.php';
$usuario_id = $_SESSION['usuario_id'] ?? null;
$stmt = $pdo->prepare("SELECT * FROM reviews WHERE usuario_id != ? ORDER BY id DESC");
$stmt->execute([$usuario_id]);
while ($review = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<div class="col-md-6 mb-4">
            <div class="card-modern p-4 h-100">
                <h5 class="fw-bold">' . htmlspecialchars($review['restaurante']) . '</h5>
                <p class="text-muted small mb-2">
                    <i class="fa-solid fa-map-pin"></i> ' . htmlspecialchars($review['localizacao']) . ' | 
                    <i class="fa-solid fa-star text-warning"></i> ' . htmlspecialchars($review['nota']) . '/5
                </p> 
                <p>' . nl2br(htmlspecialchars($review['comentario'])) . '</p>
            </div>
        </div>';
}
?>
