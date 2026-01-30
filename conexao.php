<?php

$host = "localhost";
$banco = "gastroreview";
$usuario = "root";
$senha = "";

try{
    $pdo = new PDO(
    "mysql:host=$host;
    dbname=$banco;
    chartset=utf8",
    $usuario,
    $senha
);
    echo "";
} catch(PDOException $e){
    echo "Erro: " . $e->getMessage();
}