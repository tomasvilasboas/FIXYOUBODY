<?php

// Configurações de conexão com o banco de dados
$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = '4640454_fixyoubody';

// Criação da conexão com o MySQL
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Define o charset para evitar problemas com caracteres especiais
$conn->set_charset("utf8");

?>