<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GastroReview | <?php echo 'Home'?? 'Avaliações'; ?></title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="custom-navbar">
<div class="nav-container">
<a href="tela-inicial.php" class="brand-logo">
    <i class="fa-solid fa-wine-glass"></i> GASTRO<span>REVIEW</span>
</a>

<ul class="nav-menu d-none d-md-flex">
    <li><a href="tela-inicial.php" class="nav-link-item <?php echo (strpos($_SERVER['PHP_SELF'], 'tela-inicial.php') !== false) ? 'active' : ''; ?>"><i class="fa-solid fa-house"></i> Home</a></li>
    <li><a href="Avaliacoes.php" class="nav-link-item <?php echo (strpos($_SERVER['PHP_SELF'], 'Avaliacoes.php') !== false) ? 'active' : ''; ?>"><i class="fa-solid fa-star"></i> Avaliações</a></li>
    <li><a href="#" class="nav-link-item"><i class="fa-solid fa-location-dot"></i> Locais</a></li>
    <li><a href="#" class="nav-link-item"><i class="fa-solid fa-circle-info"></i> Sobre</a></li>
</ul>

<div class="d-flex align-items-center gap-3 position-relative">
    <?php if (isset($_SESSION['usuario_id'])): ?>
    <div class="user-avatar rounded-circle bg-success text-white d-flex justify-content-center align-items-center" title="Meu Perfil" onclick="toggleMenu()" style="width: 40px; height: 40px; cursor: pointer; font-weight: bold; font-size: 18px;">
        <?php echo strtoupper(substr($_SESSION['usuario_nome'], 0, 1)); ?>
    </div>
    <div id="user-menu" class="user-menu position-absolute bg-light-green shadow rounded" style="display: none; right: 0; z-index: 1000;">
        <ul class="list-unstyled m-0 p-2">
            <li><a href="perfil.php" class="dropdown-item"><i class="fa-solid fa-user"></i>  Minha conta</a></li>
            <hr>
            <li><a href="login.php" class="dropdown-item"><i class="fa-solid fa-right-from-bracket"></i> Sair</a></li>
        </ul>
    </div>
    <?php else: ?>
    <a href="login.php" class="btn btn-success btn-sm">Login</a>
    <a href="cadastro.php" class="btn btn-outline-success btn-sm">Cadastro</a>
    <?php endif; ?>
</div>

<script>
    function toggleMenu() {
        var menu = document.getElementById('user-menu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        menu.style.minWidth = '130px';
        menu.style.padding = '10px';
        menu.style.top = '50px';
        menu.style.visibility = 'visible';
    }
</script>
</div>
</nav>