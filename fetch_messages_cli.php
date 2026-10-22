<?php
session_start();

// Verificar se o cliente está autenticado
if (!isset($_SESSION['cod_ut'])) {
    http_response_code(401);
    echo json_encode(["error" => "Usuário não autenticado"]);
    exit;
}

// Conectar ao banco de dados
$ligax = mysqli_connect(
    'localhost',
    'root',
    'root',
    '4640454_fixyoubody'
);

if (!$ligax) {    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

// O ID do cliente logado
$cliente_id = $_SESSION['cod_ut'];

// ID fixo do administrador
$admin_id = 3;

// Consulta para buscar mensagens entre o cliente logado e o administrador
$query = "
    SELECT m.*, us.login AS sender_login
    FROM messages m
    JOIN utilizadores us ON us.cod_ut = m.sender_id
    WHERE (m.sender_id = ? AND m.receiver_id = ?)
       OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.timestamp ASC
";

// Preparar a consulta
$stmt = $ligax->prepare($query);
if (!$stmt) {
    die("Erro ao preparar a consulta: " . $ligax->error);
}

// Vincular os parâmetros corretamente: cliente_id e admin_id
$stmt->bind_param("iiii", $cliente_id, $admin_id, $admin_id, $cliente_id);

// Executar a consulta
$stmt->execute();
$result = $stmt->get_result();

// Exibir as mensagens
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Formatar a data e hora
        $timestamp = date("d/m/Y H:i", strtotime($row['timestamp']));

        // Determinar a classe (direita ou esquerda) com base no sender_id
        $class = $row['sender_id'] == $cliente_id ? 'right' : 'left';

        // Exibir a mensagem com o timestamp
        echo "<div class='message $class'>
                <div class='message-content'>
                    <strong>" . htmlspecialchars($row['sender_login']) . ":</strong> " . htmlspecialchars($row['message']) . "
                </div>
                <div class='message-time'>$timestamp</div>
              </div>";
    }
} else {
    echo "Nenhuma mensagem encontrada.";
}

// Fechar tudo
$stmt->close();
mysqli_close($ligax);
?>
