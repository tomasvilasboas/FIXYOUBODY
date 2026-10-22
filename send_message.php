<?php
// send_message_cli.php
$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);if (!$ligax) {
    die("Erro na conexão com a base de dados: " . mysqli_connect_error());
}

$sender_id = isset($_POST['sender_id']) ? intval($_POST['sender_id']) : null;
$message = isset($_POST['message']) ? trim($_POST['message']) : null;
$admin_id = 3; // ID fixo do admin (Ana)

if (is_null($sender_id) || empty($message)) {
    die("Faltam dados para enviar a mensagem.");
}

$query = "INSERT INTO messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, NOW())";
$stmt = $ligax->prepare($query);
$stmt->bind_param("iis", $sender_id, $admin_id, $message);

if ($stmt->execute()) {
    echo "Mensagem enviada com sucesso!";
} else {
    echo "Erro ao enviar mensagem: " . $stmt->error;
}

$stmt->close();
mysqli_close($ligax);
?>
