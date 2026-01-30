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
<link rel="stylesheet" href="css/perfil.css">
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
