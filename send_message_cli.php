<?php
// Conectar ao banco de dados
$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);// Verificar se a conexão foi bem-sucedida
if (!$ligax) {
    die("Erro na conexão com a base de dados: " . mysqli_connect_error());
}

// Iniciar a sessão para pegar o ID do usuário logado
session_start();
$sender_id = isset($_SESSION['cod_ut']) ? $_SESSION['cod_ut'] : null;
$message = isset($_POST['message']) ? trim($_POST['message']) : null;
$admin_id = 3; // ID fixo do administrador (Ana), caso você esteja sempre enviando para o admin

// Verifique se os dados necessários foram passados
if (is_null($sender_id) || empty($message)) {
    die("Faltam dados para enviar a mensagem.");
}

// Preparar a consulta SQL para inserir a mensagem
$query = "INSERT INTO messages (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, NOW())";
$stmt = $ligax->prepare($query);
$stmt->bind_param("iis", $sender_id, $admin_id, $message); // bind para sender_id, receiver_id e message

// Verificar se a execução foi bem-sucedida
if ($stmt->execute()) {
    echo "Mensagem enviada com sucesso!";
} else {
    echo "Erro ao enviar mensagem: " . $stmt->error;
}

// Fechar a consulta e a conexão
$stmt->close();
mysqli_close($ligax);
?>
