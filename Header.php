<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GastroReview | Explorar</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/tela.css">
</head>
<body>

<nav class="custom-navbar">
    <div class="nav-container">
        <a href="tela-inicial.php" class="brand-logo">
            <i class="fa-solid fa-utensils"></i> GASTRO<span>REVIEW</span>
        </a>

        <ul class="nav-menu d-none d-lg-flex">
            <li><a href="tela-inicial.php" class="nav-link-item <?= (basename($_SERVER['PHP_SELF']) == 'tela-inicial.php') ? 'active' : ''; ?>"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="Avaliacoes.php" class="nav-link-item"><i class="fa-solid fa-star"></i> Populares</a></li>
            <li><a href="#" class="nav-link-item"><i class="fa-solid fa-fire"></i> Em Alta</a></li>
        </ul>

        <div class="nav-actions">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <div class="user-profile-nav" onclick="toggleMenu()">
                    <div class="avatar-text">
                        <?= strtoupper(substr($_SESSION['usuario_nome'] ?? 'U', 0, 1)); ?>
                    </div>
                    <i class="fa-solid fa-chevron-down ms-2" style="font-size: 10px; opacity: 0.6"></i>
                    
                    <div id="user-menu" class="user-dropdown shadow">
                        <a href="perfil.php"><i class="fa-solid fa-circle-user"></i> Meu Perfil</a>
                        <a href="#"><i class="fa-solid fa-gear"></i> Configurações</a>
                        <hr>
                        <a href="login.php" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Sair</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-login">Entrar</a>
                <a href="cadastro.php" class="btn btn-signup">Criar Conta</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    function toggleMenu() {
        const menu = document.getElementById('user-menu');
        menu.classList.toggle('active');
    }
    window.onclick = function(event) {
        if (!event.target.closest('.user-profile-nav')) {
            document.getElementById('user-menu').classList.remove('active');
        }
    }
</script>