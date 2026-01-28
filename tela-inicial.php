<?php
session_start();
require 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GastroReview | Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="custom-navbar">
        <div class="nav-container">
            <a href="#" class="brand-logo">
                <i class="fa-solid fa-wine-glass"></i> GASTRO<span>REVIEW</span>
            </a>

            <ul class="nav-menu d-none d-md-flex">
                <li><a href="#" class="nav-link-item active"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="#" class="nav-link-item"><i class="fa-solid fa-star"></i> Avaliações</a></li>
                <li><a href="#" class="nav-link-item"><i class="fa-solid fa-location-dot"></i> Locais</a></li>
                <li><a href="#" class="nav-link-item"><i class="fa-solid fa-circle-info"></i> Sobre</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <div class="nav-search d-none d-lg-flex">
                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    <input type="text" placeholder="Buscar sabor...">
                </div>
                <div class="user-avatar" title="Meu Perfil">C</div>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card-modern">
                    <h5 class="fw-bold mb-4">Nova Avaliação</h5>
                    <form action="processa.php" method="POST">
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
                        <button type="submit" class="btn btn-vinho w-100">Salvar Review</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-8">
                <h2 class="fw-bold mb-4">Seu Feed <span>Gastronômico</span></h2>
                <div class="row g-4">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM reviews ORDER BY id DESC");
                    if ($stmt->rowCount() > 0):
                        while ($review = $stmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <div class="col-md-12">
                            <div class="card-modern h-100 p-4">
                                <h5 class="fw-bold"><?= htmlspecialchars($review['restaurante']) ?></h5>
                                <span class="rating-badge mb-3 float-end">
                                    <?= htmlspecialchars($review['nota']) ?> <i class="fa-solid fa-star"></i>
                                </span>
                                <p class="text-muted small mb-2">
                                    <i class="fa-solid fa-map-pin"></i> <?= htmlspecialchars($review['localizacao']) ?>
                                </p>
                                <p><?= nl2br(htmlspecialchars($review['comentario'])) ?></p>
                            </div>
                        </div>
                    <?php
                        endwhile;
                    else:
                    ?>
                        <p class="text-muted">Nenhuma avaliação ainda. Seja o primeiro a avaliar!</p>
                    <?php
                    endif;
                    ?>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        select[name='nota'] {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }
    </script>
</body>
</html>
