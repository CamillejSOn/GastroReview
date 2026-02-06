<?php

$host = "localhost";
$banco = "gatroreview";
$usuario = "root";
$senha = "";

try{
    $pdo = new PDO(
    "mysql:host=$host;
    dbname=$banco;
    charset=utf8",
    $usuario,
    $senha
);
    echo "";
} catch(PDOException $e){
    echo "Erro: " . $e->getMessage();
}