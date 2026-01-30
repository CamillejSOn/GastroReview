<?php
session_start();
require 'conexao.php';

$erro = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);

if (empty($email) || empty($senha)) {
    $erro = "Preencha todos os campos.";
} else {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $erro = "Usuário não encontrado.";
    } elseif (!password_verify($senha, $usuario['senha'])) {
        $erro = "Senha incorreta.";
    } else {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        header("Location: tela-inicial.php");
        exit;
    }
}
}
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
