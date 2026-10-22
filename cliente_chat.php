<?php
//cliente_chat.php
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['is_admin'] = false;

$admin_id = 2;
$my_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Chat com o Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet" />
    <style>
        /* reset e estilos base */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html {
            font-family: 'Open Sans', sans-serif;
            background-color: #f1f1f1;
            color: #333;
            min-height: 100vh;
        }

        /* cabeçalho fixo */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.6);
            z-index: 100;
            height: 80px;
        }

        header h1 {
            font-family: 'Bebas Neue', cursive;
            font-size: 32px;
            color: #b19a4f;
            letter-spacing: 2px;
        }

        header h1 a {
            text-decoration: none;
            color: inherit;
        }

        nav {
            position: relative;
        }

        /* Menu hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 21px;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 20;
            position: absolute;
            right: 20px;
            top: 25px;
            padding: 0;
        }

        .hamburger div {
            width: 100%;
            height: 3px;
            background-color: #b19a4f;
            transition: all 0.3s ease;
        }

        .hamburger.active div:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active div:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active div:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
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
            transition: 0.3s ease;
        }

        nav ul li a:hover {
            border-color: #b19a4f;
            background-color: #b19a4f;
        }

        main {
            padding-top: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: calc(100vh - 180px);
        }

        h2 {
            margin-bottom: 20px;
            font-family: 'Bebas Neue', cursive;
            font-size: 40px;
            color: #b19a4f;
        }

        #chat-box {
            width: 90%;
            max-width: 1000px;
            height: 500px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
        }

        .message {
            margin-bottom: 10px;
            padding: 8px;
            background-color: #e9e9e9;
            border-radius: 5px;
            max-width: 70%;
            position: relative;
            clear: both;
        }

        .message.right {
            background-color: #b19a4f;
            color: white;
            float: right;
            text-align: right;
        }

        .message.left {
            background-color: #f0f0f0;
            color: #333;
            float: left;
            text-align: left;
        }

        .message-content {
            word-wrap: break-word;
        }

        .message-time {
            font-size: 12px;
            color: #000;
            text-align: right;
            margin-top: 5px;
        }

        .message-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
        }

        .delete-btn {
            background: transparent;
            border: none;
            color: #999;
            font-size: 14px;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .delete-btn:hover {
            background-color: rgba(0, 0, 0, 0.1);
            color: #c00;
        }

        .chat-input {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            width: 90%;
            max-width: 800px;
            margin-top: 20px;
        }

        .chat-input input[type="text"] {
            flex: 1;
            min-width: 60%;
            padding: 12px;
            height: 50px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .chat-input button {
            height: 50px;
            padding: 0 20px;
            background-color: #b19a4f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .chat-input button:hover {
            background-color: #9e883f;
        }

        footer {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            width: 100%;
            margin-top: 80px;
        }

        footer a {
            color: #b19a4f;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .no-style-link {
            color: inherit;
            text-decoration: none;
            cursor: pointer;
        }

        .no-style-link:hover,
        .no-style-link:visited,
        .no-style-link:active {
            color: inherit;
            text-decoration: none;
        }

        /* Responsividade menu */
        @media (max-width: 768px) {
            header {
                padding: 20px 20px;
            }

            /* Mostrar hamburger */
            .hamburger {
                display: flex;
            }

            nav ul {
                flex-direction: column;
                position: fixed;
                top: 80px;
                right: 0;
                background: rgba(0, 0, 0, 0.95);
                width: 220px;
                height: 100%;
                padding-top: 20px;
                transform: translateX(100%);
                transition: transform 0.3s ease-in-out;
            }

            nav ul.show {
                transform: translateX(0);
            }

            h2 {
                font-size: 30px;
            }
        }
    </style>
</head>
<body>
<header>
    <h1><a href="pagina_principal.php" class="no-style-link">FIX YOU BODY</a></h1>
    
    <button class="hamburger" id="hamburger" aria-label="Menu">
        <div></div>
        <div></div>
        <div></div>
    </button>

    <nav>
        <ul id="menu-principal">
            <li><a href="cliente_chat.php">Chat</a></li>
            <li><a href="marcacoes.frm_cliente.html">Marcar</a></li>
            <li><a href="remover_marcacoes_listadas_cliente.php">Minhas Marcações</a></li>
            <li><a href="marcacoes_calendario.php">Calendário</a></li>
            <li><a href="ver_avaliacoes_cliente.php">Avaliações</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Conversa com o Administrador</h2>
    <div id="chat-box"></div>

    <div class="chat-input">
        <input type="text" id="message" placeholder="Escreve a tua mensagem..." />
        <button onclick="sendMessage()">Enviar</button>
    </div>
</main>


<script>
    // Menu hamburguer
    const hamburger = document.getElementById('hamburger');
    const menuPrincipal = document.getElementById('menu-principal');
    
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        menuPrincipal.classList.toggle('show');
    });

    // Chat JS (tua lógica original)
    let my_id = <?= $my_id ?>;
    let admin_id = <?= $admin_id ?>;

    function fetchMessages() {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_messages_cli.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            document.getElementById("chat-box").innerHTML = this.responseText;
            document.getElementById("chat-box").scrollTop = document.getElementById("chat-box").scrollHeight;
        };
        xhr.send("sender_id=" + my_id + "&receiver_id=" + admin_id);
    }

    function sendMessage() {
        const msg = document.getElementById("message").value;
        if (msg.trim() === "root") return;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "send_message_cli.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            document.getElementById("message").value = 'root';
            fetchMessages();
        };
        xhr.send("sender_id=" + my_id + "&receiver_id=" + admin_id + "&message=" + encodeURIComponent(msg));
    }

    setInterval(fetchMessages, 2000);
    window.onload = fetchMessages;
</script>
</body>
</html>