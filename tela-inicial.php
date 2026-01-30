<?php
session_start();
require 'conexao.php';
include 'Header.php';
$termo = $_GET['search'] ?? '';
if (!empty($termo)) {
$search = $pdo->prepare("
    SELECT * 
    FROM reviews 
    WHERE restaurante LIKE :termo 
        OR localizacao LIKE :termo
");
$search->execute([
    ':termo' => '%' . $termo . '%'
]);
} else {
$search = $pdo->query("SELECT * FROM reviews");
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
<link rel="stylesheet" href="css/tela.css">
<head>
<section class="hero-search">
<div class="hero-container">
    <h1>Descubra os <span>melhores restaurantes</span></h1>
    <p>Avaliações reais de pessoas reais. Compartilhe sua experiência 🍽️</p>
<form method="GET" class="hero-search-box" action="tela-inicial.php">
    <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" name="search" placeholder="Busque por restaurante ou localização" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button type="submit">Buscar</button>
</form>
</div>
</section>

<div class="container mt-5">
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card-modern">
            <h5 class="fw-bold mb-4">Nova Avaliação</h5>

            <form action="TelaInicial.php" method="POST">
                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-2">Restaurante</label>
                    <input type="text" name="restaurante" class="form-control" placeholder="Ex: Cantina do Chef" required>
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-2">Localização</label>
                    <input type="text" name="localizacao" class="form-control" placeholder="Ex: Centro da cidade" required>
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-2">Sua Nota</label>
                    <select name="nota" class="form-control" required>
                        <option value="">Selecione uma nota</option>
                        <option value="5">Excelente</option>
                        <option value="4">Muito Bom</option>
                        <option value="3">Regular</option>
                        <option value="2">Ruim</option>
                        <option value="1">Péssimo</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="small fw-bold text-muted mb-2">Comentário</label>
                    <textarea name="comentario" class="form-control" rows="3" placeholder="O que achou da comida?" required></textarea>
                </div>

                <button type="submit" class="btn btn-vinho w-100">
                    Salvar Review
                </button>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <h2 class="fw-bold mb-4">Feed <span>Gastronômico</span></h2>

        <?php
        $stmt = $pdo->query("SELECT * FROM reviews ORDER BY id DESC");
        if ($stmt->rowCount() > 0):
            while ($review = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>

            <div class="card-modern review-card mb-4">
                <div class="review-header">
                    <div class="rating-stars me-1">
                        <?php
                            $nota = (int)$review['nota'];
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $nota) {
                                    echo '<i class="fa-solid fa-star" style="color: #ffbc12;"></i>';
                                } else {
                                    echo '<i class="fa-regular fa-star"></i>';
                                }
                            }
                        ?>
                    </div>

                    <h5 class="fw-bold mb-0">
                        <?= htmlspecialchars($review['restaurante']) ?>
                    </h5>
                </div>

                <p class="text-muted small mb-2">
                    <i class="fa-solid fa-map-pin"></i>
                    <?= htmlspecialchars($review['localizacao']) ?>
                </p>

                <p class="mb-0">
                    <?= nl2br(htmlspecialchars($review['comentario'])) ?>
                </p>
            </div>

        <?php
            endwhile;
        else:
        ?>
            <p class="text-muted">
                Nenhuma avaliação ainda. Seja o primeiro!
            </p>
        <?php endif; ?>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>