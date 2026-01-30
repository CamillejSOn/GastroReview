<?php
session_start();
require 'conexao.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$nome   = trim($_POST['nome']);
$email  = trim($_POST['email']);
$senha  = trim($_POST['senha']);
$cidade = trim($_POST['cidade']);

if (empty($nome) || empty($email) || empty($senha) || empty($cidade)) {
    $erro = "Todos os campos são obrigatórios.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erro = "Email inválido.";
} else {
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        "INSERT INTO usuarios (nome, email, senha, cidade) VALUES (?, ?, ?, ?)"
    );

    if ($stmt->execute([$nome, $email, $senhaHash, $cidade])) {
        $sucesso = "Cadastro realizado com sucesso!";
    } else {
        $erro = "Erro ao cadastrar.";
    }
}
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastro | GastroReview</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/auth.css">

</head>
<body>

<div class="login-container">
<div class="login-box">
    <h1>GastroReview</h1>
    <p class="subtitle">Crie sua conta para avaliar restaurantes</p>

    <?php if ($erro): ?>
        <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div class="sucesso"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Nome completo</label>
        <input name="nome" type="text" required>

        <label>Email</label>
        <input name="email" type="email" required>

        <label>Senha</label>
        <input name="senha" type="password" required>

        <label>Cidade</label>
        <input name="cidade" type="text" required>

        <button type="submit">Cadastrar</button>
    </form>

    <div class="extras">
        Já tem conta? <a href="login.php">Fazer login</a>
    </div>
</div>
</div>

</body>
</html>