<?php
session_start();
require 'conexao.php';
include 'Header.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$termo = $_GET['search'] ?? '';
$cat_filtro = $_GET['cat'] ?? '';
$sql = "SELECT r.*, res.nome as restaurante_nome, res.localizacao as res_local, 
               res.categoria as res_cat, u.nome as autor, res.id as res_id,
               (SELECT COUNT(*) FROM favoritos f WHERE f.restaurante_id = res.id AND f.usuario_id = :user_id) as is_fav
        FROM reviews r 
        JOIN restaurantes res ON r.restaurante_id = res.id
        JOIN usuarios u ON r.usuario_id = u.id";

$params = [':user_id' => $usuario_id];

if (!empty($termo) || !empty($cat_filtro)) {
    $sql .= " WHERE 1=1";
    if (!empty($termo)) {
        $sql .= " AND (res.nome LIKE :t OR res.localizacao LIKE :t)";
        $params[':t'] = "%$termo%";
    }
    if (!empty($cat_filtro)) {
        $sql .= " AND res.categoria = :c";
        $params[':c'] = $cat_filtro;
    }
}

$stmt = $pdo->prepare($sql . " ORDER BY r.created_at DESC");
$stmt->execute($params);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>GastroReview | Explorar</title>
    <link rel="stylesheet" href="css/tela.css">
</head>
<body>

<section class="hero-section">
    <div class="container text-center">
        <h1 class="hero-title">Onde será sua próxima <span>refeição?</span></h1>
        <p>A maior comunidade de críticos gastronômicos da região.</p>
        <form method="GET" class="search-wrapper shadow-lg mx-auto">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" placeholder="Buscar restaurante ou bairro..." value="<?= htmlspecialchars($termo) ?>">
            <button type="submit">Explorar</button>
        </form>

        <div class="quick-tags mt-4">
            <a href="tela-inicial.php" class="badge-tag">Tudo</a>
            <a href="?cat=Pizzaria" class="badge-tag">🍕 Pizzas</a>
            <a href="?cat=Hamburgueria" class="badge-tag">🍔 Burgers</a>
            <a href="?cat=Japonês" class="badge-tag">🍣 Japonesa</a>
            <a href="?cat=Bar" class="badge-tag">🍺 Bares</a>
            <a href="?cat=Família" class="badge-tag">👨‍👩‍👧‍👦 Família</a>
            <a href="?cat=Sorveteria" class="badge-tag">🍦 Sorveterias</a>
        </div>
    </div>
</section>

<div class="container main-content">
    <div class="row">
        <aside class="col-lg-4 mb-4">
            <div class="sticky-sidebar">
                <div class="card-modern p-4 shadow-sm">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-camera text-success"></i> Nova Experiência</h5>
                    <form action="TelaInicio.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="text" name="restaurante" class="form-control-modern" placeholder="Nome do Local" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="localizacao" class="form-control-modern" placeholder="Bairro/Cidade" required>
                        </div>
                        <div class="mb-3">
                            <select name="categoria" class="form-select-modern" required>
                                <option value="Outros">Categoria...</option>
                                <option value="Pizzaria">Pizzaria</option>
                                <option value="Hamburgueria">Hamburgueria</option>
                                <option value="Sorveteria">Sorveteria</option>
                                <option value="Doceria">Doceria</option>
                                <option value="Cafeteria">Cafeteria</option>
                                <option value="Restaurante">Restaurante</option>
                                <option value="Bar">Bar</option>
                                <option value="Japonês">Japonês</option>
                                <option value="Família">Família</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <select name="nota" class="form-select-modern" required>
                                    <option value="5">⭐⭐⭐⭐⭐</option>
                                    <option value="4">⭐⭐⭐⭐</option>
                                    <option value="3">⭐⭐⭐</option>
                                    <option value="2">⭐⭐</option>
                                    <option value="1">⭐</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="btn-main w-100 text-center" style="cursor:pointer; font-size: 0.8rem;">
                                    <i class="fa-solid fa-image"></i> Foto
                                    <input type="file" name="foto" hidden accept="image/*">
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea name="comentario" class="form-control-modern" rows="3" placeholder="O que achou?" required></textarea>
                        </div>
                        <button type="submit" class="btn-main w-100">Publicar Agora</button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="col-lg-8">
            <h4 class="fw-bold mb-4">Feed <span>Gastronômico</span></h4>

            <?php if ($stmt->rowCount() > 0): ?>
               <?php while ($review = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <div class="card-review shadow-sm mb-4">
        
        <div class="review-header px-4 py-3 d-flex justify-content-between align-items-center">
            <div class="user-info">
                <div class="user-avatar-small"><?= strtoupper($review['restaurante_nome'][0]) ?></div>
                <div>
                    <h6 class="m-0 fw-bold"><?= htmlspecialchars($review['restaurante_nome']) ?> 
                        <span class="badge bg-light text-success ms-2" style="font-size: 0.6rem;"><?= $review['res_cat'] ?></span>
                    </h6>
                    <small class="text-muted"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($review['res_local']) ?></small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="favoritar.php?id=<?= $review['res_id'] ?>" class="text-danger" title="Favoritar">
                    <i class="fa-<?= $review['is_fav'] ? 'solid' : 'regular' ?> fa-heart"></i>
                </a>
                <div class="rating-badge"><?= $review['nota'] ?>.0</div>
            </div>
        </div>

        <?php if (!empty($review['imagem_caminho'])): ?>
            <div class="review-image">
                <img src="/GastroReview/uploads/<?= $review['imagem_caminho'] ?>" alt="Foto">
            </div>
        <?php endif; ?>
        
        <div class="review-body p-4">
            <p class="mb-0 text-dark">"<?= nl2br(htmlspecialchars($review['comentario'])) ?>"</p>
        </div>
        
        <div class="review-footer px-4 py-3 bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Publicado por <strong><?= htmlspecialchars($review['autor']) ?></strong></small>
                <small class="text-muted"><?= date('d/m/Y', strtotime($review['created_at'])) ?></small>
            </div>
        </div>
    </div>
<?php endwhile; ?>
            <?php else: ?>
                <div class="card-modern text-center py-5">
                    <p class="text-muted mb-0">Nenhuma experiência encontrada.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>