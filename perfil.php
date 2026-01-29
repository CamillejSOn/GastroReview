<?php
session_start();
require 'conexao.php';
include 'Header.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT nome, email, cidade, bio FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio']);

    $update = $pdo->prepare("UPDATE usuarios SET bio = ? WHERE id = ?");
    $update->execute([$bio, $usuario_id]);

    header("Location: perfil.php");
    exit;
}

$reviews = $pdo->prepare("
    SELECT restaurante, comentario, nota 
    FROM reviews 
    WHERE usuario_id = ?
");
$reviews->execute([$usuario_id]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil | GastroReview</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    body {
    font-family: 'Inter', 'Segoe UI', sans-serif;
    background: linear-gradient(180deg, #f4f6f8, #e9ecef);
    padding: 40px 20px;
    color: #333;
}

.container {
    max-width: 1000px;
    margin: auto;
}

.card {
    background: #fff;
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

h2 {
    margin-bottom: 15px;
    font-size: 22px;
    color: #2e7d32;
}

/* PERFIL */
.profile-card {
    display: flex;
    align-items: center;
    gap: 20px;
    background: #2e7d32;
    color: white;
    border-radius: 18px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 12px 30px rgba(46,125,50,0.4);
}

.avatar {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    font-size: 36px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-info h1 {
    margin: 0;
    font-size: 26px;
}

.profile-info p {
    opacity: 0.9;
}

/* BIO */
textarea {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: 1px solid #ddd;
    min-height: 100px;
    font-size: 15px;
}

button {
    margin-top: 12px;
    padding: 12px 18px;
    border: none;
    border-radius: 10px;
    background: #2e7d32;
    color: white;
    font-size: 15px;
    cursor: pointer;
    transition: 0.2s;
}

button:hover {
    background: #256428;
}

/* REVIEWS */
.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 20px;
}

.review-card {
    background: #fafafa;
    border-radius: 14px;
    padding: 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    transition: transform 0.2s;
}

.review-card:hover {
    transform: translateY(-4px);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.badge {
    background: #ff9800;
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 14px;
}

.empty {
    color: #777;
    font-style: italic;
}
</style>

</head>
<body>

<div class="container">

    <!-- PERFIL -->
    <div class="profile-card mt-4">
        <div class="avatar">
            <?= strtoupper(substr($usuario['nome'], 0, 1)) ?>
        </div>

        <div class="profile-info">
            <h1><?= htmlspecialchars($usuario['nome']) ?></h1>
            <p><?= htmlspecialchars($usuario['email']) ?> • <?= htmlspecialchars($usuario['cidade']) ?></p>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data">
    <img
        src="<?= !empty($usuario['foto']) 
            ? 'uploads/' . htmlspecialchars($usuario['foto']) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($usuario['nome']) ?>"
        width="120"
        height="120"
        style="border-radius:50%;object-fit:cover"
    >

    <br><br>

    <input type="file" name="foto" accept="image/*">
    <br><br>
    <button type="submit" name="salvar_foto">Salvar foto</button>
</form>


    <!-- BIO -->
    <div class="card">
        <h2>Sobre mim</h2>
        <form method="POST">
            <textarea name="bio" placeholder="Conte um pouco sobre você..."><?= htmlspecialchars($usuario['bio'] ?? '') ?></textarea>
            <button type="submit">Salvar alterações</button>
        </form>
    </div>

    <!-- REVIEWS -->
    <div class="card">
        <h2>Minhas avaliações</h2>

        <?php if ($reviews->rowCount() === 0): ?>
            <p class="empty">Você ainda não avaliou nenhum restaurante.</p>
        <?php else: ?>
            <div class="reviews-grid">
                <?php while ($r = $reviews->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <strong><?= htmlspecialchars($r['restaurante']) ?></strong>
                            <span class="badge-success text-white">⭐ <?= $r['nota'] ?>/5</span>
                        </div>
                        <p><?= htmlspecialchars($r['comentario']) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

</body>

</html>
