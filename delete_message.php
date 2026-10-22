<?php
session_start();
include "valida_session.php";

$admin_id = $_SESSION['cod_ut']; // ID do admin autenticado

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $msg_id = intval($_POST['id']);

    $ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);    if (!$ligax) {
        http_response_code(500);
        echo "Erro na conexão";
        exit;
    }

    // Verifica se a mensagem foi enviada pelo admin
    $check = mysqli_query($ligax, "SELECT * FROM messages WHERE id = $msg_id AND sender_id = $admin_id");
    if (mysqli_num_rows($check) === 0) {
        http_response_code(403);
        echo "Você só pode apagar mensagens que enviou.";
        exit;
    }

    $query = "DELETE FROM messages WHERE id = $msg_id";
    if (mysqli_query($ligax, $query)) {
        echo "ok";
    } else {
        http_response_code(500);
        echo "Erro ao apagar mensagem.";
    }
}
?>
