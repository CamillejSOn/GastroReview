<?php
$erro = '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login | GastroReview</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h1>GastroReview</h1>
        <p>Entre para avaliar restaurantes</p>

        <?php if ($erro): ?>
            <div class="erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>


        <form method="POST" action="Logins.php">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Senha</label>
            <input type="password" name="senha" required>

            <button type="submit">Entrar</button>
        </form>

        <div class="extras">
            <a href="#">Esqueci minha senha</a>
            <span>•</span>
            <a href="cadastro.php">Criar conta</a>
        </div>
    </div>
</div>

</body>
</html>
