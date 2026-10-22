<?php
// fetch_messages_adm.php

session_start();
header('Content-Type: text/html; charset=utf-8');

if (!isset($_SESSION['cod_ut'])) {
    http_response_code(401);
    echo "Sessão inválida";
    exit;
}

$user_id = $_SESSION['cod_ut'];
$other_id = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);

if (!$other_id) {
    echo "Usuário inválido";
    exit;
}

$ligax = new mysqli(
    'localhost',
    'root',
    'root',
    '4640454_fixyoubody'
);if ($ligax->connect_errno) {
    echo "Erro na ligação ao banco";
    exit;
}
$ligax->set_charset("utf8");

$stmt = $ligax->prepare("
    SELECT id, sender_id, message, timestamp FROM messages
    WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
    ORDER BY timestamp ASC
");
$stmt->bind_param("iiii", $user_id, $other_id, $other_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $isSender = $row['sender_id'] == $user_id;
    $class = $isSender ? 'right' : 'left';
    $message = htmlspecialchars($row['message'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $timestamp = date("d/m/Y H:i", strtotime($row['timestamp']));
    $id = intval($row['id']);

    echo "<div class='message $class'>";
    echo "<div>$message</div>";
    echo "<div class='message-footer'>";
    echo "<span class='timestamp'>$timestamp</span>";
    if ($isSender) {
        echo "<button class='delete-btn' onclick='deleteMessage($id)' title='Apagar mensagem'>&times;</button>";
    }
    echo "</div></div>";
}

$stmt->close();
$ligax->close();
?>
