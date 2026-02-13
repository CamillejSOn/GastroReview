<?php
session_start();
require 'conexao.php';
require 'Header.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT nome, email, cidade, bio, created_at FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio_ajax'])) {
    $bio = trim($_POST['bio']);
    $update = $pdo->prepare("UPDATE usuarios SET bio = ? WHERE id = ?");
    $update->execute([$bio, $usuario_id]);
    echo nl2br(htmlspecialchars($bio));
    exit;
}

$stats = $pdo->prepare("
    SELECT 
        COUNT(*) total,
        ROUND(AVG(nota),1) media
    FROM reviews
    WHERE usuario_id = ?
");
$stats->execute([$usuario_id]);
$stats = $stats->fetch(PDO::FETCH_ASSOC);

$favoritos_stmt = $pdo->prepare("
    SELECT res.nome, res.localizacao 
    FROM favoritos f
    JOIN restaurantes res ON f.restaurante_id = res.id
    WHERE f.usuario_id = ?
");
$favoritos_stmt->execute([$usuario_id]);
$meus_favoritos = $favoritos_stmt->fetchAll(PDO::FETCH_ASSOC);

$ordem = $_GET['ordem'] ?? 'recentes';
$orderSql = match($ordem) {
    'melhor' => 'ORDER BY r.nota DESC',
    'pior' => 'ORDER BY r.nota ASC',
    default => 'ORDER BY r.created_at DESC'
};

$reviews = $pdo->prepare("
    SELECT res.nome as restaurante, r.comentario, r.nota, r.created_at
    FROM reviews r
    JOIN restaurantes res ON r.restaurante_id = res.id
    WHERE r.usuario_id = ?
    $orderSql
");
$reviews->execute([$usuario_id]);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil | GastroReview</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/perfil.css">
</head>

<body>
<div class="container">
    <section class="profile">
        <div class="avatar"><?= strtoupper($usuario['nome'][0]) ?></div>
        <div class="info">
            <h1><?= htmlspecialchars($usuario['nome']) ?></h1>
            <span><?= htmlspecialchars($usuario['email']) ?></span>
            <span><?= htmlspecialchars($usuario['cidade']) ?></span>
            <span>Membro desde <?= date('m/Y', strtotime($usuario['created_at'])) ?></span>

            <div class="stats">
                <div>
                    <strong><?= $stats['total'] ?></strong>
                    <span>Avaliações</span>
                </div>
                <div>
                    <strong><?= $stats['media'] ?? '—' ?></strong>
                    <span>Média</span>
                </div>
                <div>
                    <strong><?= $stats['total'] >= 10 ? 'Elite' : 'Crítico' ?></strong>
                    <span>Status</span>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <h2>Sobre mim</h2>
        <p id="bioText">
            <?= nl2br(htmlspecialchars($usuario['bio'] ?: 'Nenhuma bio adicionada.')) ?>
        </p>
        <button onclick="editarBio()">Editar bio</button>
        <form id="bioForm" style="display:none;">
            <textarea name="bio" maxlength="200"><?= htmlspecialchars($usuario['bio']) ?></textarea>
            <small><span id="count"></span>/200</small><br><br>
            <button type="submit">Salvar</button>
        </form>
    </section>

    <section class="card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Meus Favoritos ❤️</h2>
        <small class="text-muted"><?= count($meus_favoritos) ?> lugares salvos</small>
    </div>
    <div class="favoritos-container">
        <?php if (count($meus_favoritos) === 0): ?>
            <p class="text-muted italic">Você ainda não favoritou nenhum restaurante no GastroReview.</p>
        <?php else: ?>
            <div class="favoritos-grid">
                <?php foreach ($meus_favoritos as $fav): ?>
                    <div class="fav-badge">
                        <i class="fa-solid fa-heart text-danger me-2"></i>
                        <div class="fav-info">
                            <strong><?= htmlspecialchars($fav['nome']) ?></strong>
                            <small><?= htmlspecialchars($fav['categoria'] ?? 'Restaurante') ?></small>
                        </div>
                        
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px;" class="mb-4">
        <h2>Minhas avaliações</h2>
        <form method="GET">
            <select name="ordem" onchange="this.form.submit()">
                <option value="recentes">Mais recentes</option>
                <option value="melhor" <?= $ordem=='melhor'?'selected':'' ?>>Melhor nota</option>
                <option value="pior" <?= $ordem=='pior'?'selected':'' ?>>Pior nota</option>
            </select>
        </form>
    </div>

    <div class="reviews">
        <?php if ($reviews->rowCount() === 0): ?>
            <p>Nenhuma avaliação ainda.</p>
        <?php endif; ?>
    
        <?php while ($r = $reviews->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="review">
                <header>
                    <strong><?= htmlspecialchars($r['restaurante']) ?></strong>
                    <span class="badge"><?= $r['nota'] ?>/5</span>
                </header>        
                <small><?= date('d/m/Y', strtotime($r['created_at'])) ?></small>        
                <p><?= htmlspecialchars($r['comentario']) ?></p>        
                <div class="actions">
                    <a href="#">Editar</a>
                    <a href="#">Excluir</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
    const bioForm = document.getElementById('bioForm');
    const bioText = document.getElementById('bioText');
    const textarea = bioForm.querySelector('textarea');
    const count = document.getElementById('count');
    
    count.innerText = textarea.value.length;
    textarea.oninput = () => count.innerText = textarea.value.length;
    
    function editarBio(){
        bioForm.style.display = 'block';
    }
    
    bioForm.onsubmit = e => {
        e.preventDefault();
        fetch('', {
            method:'POST',
            body:new URLSearchParams({
                bio_ajax:1,
                bio: textarea.value
            })
        })
        .then(r=>r.text())
        .then(txt=>{
            bioText.innerHTML = txt;
            bioForm.style.display = 'none';
        });
    };
</script>         
</body>
</html>