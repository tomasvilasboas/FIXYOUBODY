<?php
// fetch_messages_cli.php
$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);$sender_id = isset($_POST['sender_id']) ? intval($_POST['sender_id']) : null;
$admin_id = 3;  // ID fixo do admin (Ana)

if (is_null($sender_id)) {
    die("Faltam dados para buscar as mensagens.");
}

$query = "
SELECT m.*, u.login
FROM messages m
JOIN utilizadores u ON u.cod_ut = m.sender_id OR u.cod_ut = m.receiver_id
WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
ORDER BY m.timestamp ASC
";

$stmt = $ligax->prepare($query);
$stmt->bind_param("iiii", $sender_id, $admin_id, $admin_id, $sender_id);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $class = $row['sender_id'] == $sender_id ? 'me' : 'other';
        echo "<div class='message $class'><strong>{$row['login']}:</strong> {$row['message']}</div>";
    }
} else {
    echo "Nenhuma mensagem encontrada.";
}

$stmt->close();
mysqli_close($ligax);
?>
