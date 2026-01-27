<?php

require 'conexao.php';

$sql = $pdo->query("SELECT * FROM reviews");
$reviews = $request->review;

include 'views/tela-inicial.php';
?>
