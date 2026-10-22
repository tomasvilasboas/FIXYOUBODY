<?php
$link = mysqli_connect(
    'localhost',
    'root',
    'root',
    '4640454_fixyoubody'
);

if (!$link) {
    error_log('Erro de conexão MySQL: ' . mysqli_connect_error());
    die(json_encode([
        'status' => 'error',
        'message' => 'Erro de conexão com o banco de dados'
    ]));
}

mysqli_set_charset($link, 'utf8mb4');
?>