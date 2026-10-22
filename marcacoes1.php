<?php
include "valida_session.php";

$login = $_SESSION["login"];

// Conexão com o banco de dados
$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);if (!$ligax) {
    die('<p>Erro na conexão: ' . mysqli_connect_error() . '</p>');
}

// Verificar se o utilizador existe
$consulta = "SELECT * FROM utilizadores WHERE login = ?";
$stmt = $ligax->prepare($consulta);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die('<p>Erro: Utilizador não encontrado.</p>');
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data_marcacao = $_POST['data_marcacao'] ?? 'root';
    $hora_marcacao = $_POST['hora_marcacao'] ?? 'root';

    // Validação dos campos
    if (empty($data_marcacao) || empty($hora_marcacao)) {
        $message = 'Atenção! Campos em falta. Volte atrás e tente de novo.';
        $status = 'erro';
    } else {
        // Verificar disponibilidade de vagas
        $consulta_vagas = "SELECT COUNT(*) as total FROM marcacoes WHERE data = ? AND hora = ?";
        $stmt = $ligax->prepare($consulta_vagas);
        $stmt->bind_param("ss", $data_marcacao, $hora_marcacao);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $nregistos = $row['total'];

        if ($nregistos >= 6) {
            $message = 'Indisponibilidade de vaga. Já existem 6 marcações para este horário.';
            $status = 'erro';
        } else {
            // Inserir nova marcação usando login como referência
            $inserir = "INSERT INTO marcacoes (login, data, hora) VALUES (?, ?, ?)";
            $stmt = $ligax->prepare($inserir);
            $stmt->bind_param("sss", $login, $data_marcacao, $hora_marcacao);
            $result1 = $stmt->execute();

            if ($result1) {
                $message = 'Marcação realizada com sucesso!';
                $status = 'sucesso';
            } else {
                $message = 'Erro ao inserir dados: ' . $ligax->error;
                $status = 'erro';
            }
        }
    }
} else {
    $message = 'Erro: O formulário não foi submetido corretamente.';
    $status = 'erro';
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Marcação</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Open Sans', sans-serif;
            background-color: #f1f1f1;
            color: #333;
            display: flex;
            flex-direction: column;
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.6);
            z-index: 10;
        }

        header h1 {
            font-family: 'Bebas Neue', cursive;
            font-size: 32px;
            color: #b19a4f;
            letter-spacing: 2px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 16px;
            border: 2px solid transparent;
            border-radius: 20px;
            transition: 0.3s ease;
        }

        nav ul li a:hover {
            border-color: #b19a4f;
            background-color: #b19a4f;
        }

        #main {
            margin-top: 120px;
            text-align: center;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding-bottom: 50px;
            flex-grow: 1;
        }

        h2 {
            font-family: 'Bebas Neue', cursive;
            font-size: 40px;
            color: #b19a4f;
            margin-bottom: 40px;
        }

        .alert {
            padding: 15px;
            margin: 20px;
            font-size: 18px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
        }

        .sucesso {
            background-color: #4CAF50;
            color: white;
        }

        .erro {
            background-color: #f44336;
            color: white;
        }

        .btn-voltar {
            display: inline-block;
            padding: 10px 20px;
            background-color: #b19a4f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .btn-voltar:hover {
            background-color: #8d7b47;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #000;
            font-size: 14px;
            color: #888;
            width: 100%;
        }

        @media (max-width: 768px) {
            header {
                padding: 15px 20px;
                flex-direction: column;
            }
            
            nav ul {
                margin-top: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            #main {
                margin-top: 160px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="pagina_principal_adm.php" style="color: inherit; text-decoration: none;">FIX YOU BODY</a></h1>
        <nav>
            <ul>
                <li><a href="cliente_chat.php">Chat</a></li>
                <li><a href="marcacoes.frm_cliente.html">Marcar</a></li>
                <li><a href="remover_marcacoes_listadas_cliente.php">Minhas Marcações</a></li>
                <li><a href="marcacoes_calendario.php">Calendário</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div id="main">
        <h2>Resultado da Marcação</h2>

        <div class="alert <?php echo $status; ?>">
            <?php echo $message; ?>
        </div>

        <a href="pagina_principal.php" class="btn-voltar">Voltar à Página Principal</a>
    </div>

    <footer>
        &copy; 2025 FIX YOU BODY. Todos os direitos reservados.
    </footer>
</body>
</html>
<?php
mysqli_close($ligax);
?>