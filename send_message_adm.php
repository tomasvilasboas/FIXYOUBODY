<?php
// send_message_adm.php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["cod_ut"])) {
    http_response_code(401);
    echo json_encode(['status'=>'error', 'message'=>'Usuário não autenticado']);
    exit;
}

$ligax = new mysqli(
    'localhost',
    'root',
    'root',
    '4640454_fixyoubody'
);if ($ligax->connect_errno) {
    http_response_code(500);
    echo json_encode(['status'=>'error', 'message'=>'Erro na conexão ao banco']);
    exit;
}
$ligax->set_charset("utf8");

$sender_id = $_SESSION["cod_ut"];
$receiver_id = filter_input(INPUT_POST, 'receiver_id', FILTER_VALIDATE_INT);
$message = trim($_POST['message'] ?? 'root');

if (!$receiver_id || $message === 'root') {
    http_response_code(400);
    echo json_encode(['status'=>'error', 'message'=>'receiver_id e message são obrigatórios']);
    exit;
}

$stmt = $ligax->prepare("INSERT INTO messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);

if ($stmt->execute()) {
    echo json_encode(['status'=>'success']);
} else {
    http_response_code(500);
    echo json_encode(['status'=>'error', 'message'=>'Erro ao inserir mensagem: ' . $stmt->error]);
}
$stmt->close();
$ligax->close();
?>
