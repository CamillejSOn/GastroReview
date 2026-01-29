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
    <style>
      :root {
    --primary: #1f8f4a;      
    --accent: #34a853;      
    --bg-light: #f6fdf9;    
    --card-bg: #ffffff;
    --text-main: #1f2933;
    --text-muted: #6b7280;
    --border: #e5f2eb;
}

body {
    background-color: var(--bg-light);
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-main);
    margin: 0;
}

/* ================= NAVBAR ================= */
.custom-navbar {
    background: #ffffff;
    border-bottom: 1px solid var(--border);
    padding: 14px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.brand-logo {
    text-decoration: none;
    font-weight: 800;
    font-size: 1.4rem;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.brand-logo span {
    color: var(--accent);
}

.nav-menu {
    list-style: none;
    display: flex;
    gap: 30px;
    margin: 0;
    padding: 0;
}

.nav-link-item {
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-main);
    display: flex;
    align-items: center;
    gap: 6px;
    transition: 0.2s;
}

.nav-link-item:hover,
.nav-link-item.active {
    color: var(--accent);
}

/* Busca navbar */
.nav-search {
    display: flex;
    align-items: center;
    background: #f0faf4;
    border-radius: 30px;
    padding: 6px 14px;
    width: 240px;
}

.nav-search input {
    border: none;
    background: transparent;
    outline: none;
    font-size: 0.85rem;
    margin-left: 8px;
    width: 100%;
}

/* Avatar */
.user-avatar {
    width: 38px;
    height: 38px;
    background: var(--accent);
    color: white;
    border-radius: 50%;
    display: grid;
    place-items: center;
    font-weight: 800;
    cursor: pointer;
}

/* ================= HERO ================= */
.hero-search {
    background: linear-gradient(180deg, #e9f8f0, #f6fdf9);
    padding: 80px 20px 60px;
    border-bottom: 1px solid var(--border);
}

.hero-container {
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
}

.hero-container h1 {
    font-size: 2.6rem;
    font-weight: 800;
    margin-bottom: 10px;
}

.hero-container h1 span {
    color: var(--accent);
}

.hero-container p {
    color: var(--text-muted);
    font-size: 1.05rem;
    margin-bottom: 35px;
}

/* Busca principal */
.hero-search-box {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 50px;
    padding: 12px 18px;
    gap: 12px;
    box-shadow: 0 15px 40px rgba(52, 168, 83, 0.15);
}

.hero-search-box input {
    border: none;
    outline: none;
    flex: 1;
    font-size: 1rem;
}

.hero-search-box button {
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 40px;
    padding: 12px 26px;
    font-weight: 700;
    transition: 0.3s;
}

.hero-search-box button:hover {
    background: #2c8e46;
}

/* ================= CARDS ================= */
.card-modern {
    background: var(--card-bg);
    border-radius: 18px;
    border: 1px solid var(--border);
    padding: 24px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.05);
    transition: 0.25s;
}

.card-modern:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

/* ================= REVIEW ================= */
.review-card {
    margin-bottom: 20px;
}

.review-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 8px;
}

/* Nota verde estilo TripAdvisor */
.rating-circle {
    width: 44px;
    height: 44px;
    background: var(--accent);
    color: white;
    border-radius: 10px;
    font-weight: 800;
    display: grid;
    place-items: center;
    font-size: 1rem;
}

.review-card p {
    margin-bottom: 6px;
}

.review-card .fa-map-pin {
    color: var(--accent);
}

/* ================= FORM ================= */
.form-control {
    border-radius: 12px;
    padding: 12px;
    border: 1px solid var(--border);
}

.form-control:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(52, 168, 83, 0.15);
}

/* Botão */
.btn-vinho {
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 14px;
    padding: 14px;
    font-weight: 800;
    transition: 0.3s;
}

.btn-vinho:hover {
    background: #2c8e46;
    color: white;
}

/* ================= TITULOS ================= */
h2 span {
    color: var(--accent);
}
    </style>

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
            <h2 class="fw-bold mb-4">Seu Feed <span>Gastronômico</span></h2>

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
                    Nenhuma avaliação ainda. Seja o primeiro a avaliar!
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
