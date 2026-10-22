<?php
session_start();

if (!isset($_SESSION['cod_ut'])) {
    http_response_code(401);
    echo json_encode(["error" => "Usuário não autenticado"]);
    exit;
}

$conn = new mysqli(
    "localhost",
    "root",
    "root",
    "4640454_fixyoubody"
);if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Erro na conexão com o banco de dados: " . $conn->connect_error]);
    exit;
}

$admin_id = $_SESSION['cod_ut'];

$sql = "
SELECT u.cod_ut, u.login, 
       IFNULL(MAX(m.timestamp), 'Nunca') AS last_message
FROM utilizadores u
LEFT JOIN messages m ON 
    (u.cod_ut = m.sender_id AND m.receiver_id = ?) 
    OR (u.cod_ut = m.receiver_id AND m.sender_id = ?)
WHERE u.is_admin = 0
GROUP BY u.cod_ut, u.login
ORDER BY last_message DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $admin_id, $admin_id);
$stmt->execute();

$result = $stmt->get_result();
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

header('Content-Type: application/json');
echo json_encode($users);

$stmt->close();
$conn->close();
?>
