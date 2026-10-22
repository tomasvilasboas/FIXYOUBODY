<?php
include "valida_session.php";
$login = $_SESSION["login"];
$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);
if (!$ligax) {
    echo '<p> Falha na Ligação.';
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>FIX YOU BODY</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body, html {
            height: 30%;
            font-family: 'Open Sans', sans-serif;
            background-color: #f1f1f1;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 80vh;
        }

        body {
            display: flex;
            flex-direction: column;
            margin-top: 80px;
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
            letter-spacing: 2px;
        }

        /* Fixed link styling */
        header h1 a {
            color: #b19a4f !important;
            text-decoration: none !important;
        }

        header h1 a:visited {
            color: #b19a4f !important;
            text-decoration: none !important;
        }

        header h1 a:hover {
            color: #d4b956 !important;
            text-decoration: none !important;
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

        nav ul li a:hover,
        nav ul li a.active { /* Added active state for menu items */
            border-color: #b19a4f;
            background-color: #b19a4f;
        }

        #main {
            max-width: 1000px;
            margin: 50px auto 40px;
            padding: 20px;
        }

        h2 {
            text-align: center;
            font-family: 'Bebas Neue', cursive;
            font-size: 40px;
            color: #b19a4f;
        }

        .content-wrapper {
            text-align: center;
        }

        .content-wrapper img {
            width: 50%;
            border-radius: 10px;
            margin-top: 30px;
        }

        .content-wrapper p {
            font-size: 18px;
            color: #444;
            line-height: 1.8;
            margin-top: 30px;
            text-align: center;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #000;
            font-size: 14px;
            color: #888;
            margin-top: 420px;
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
            border-radius: 2px;
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

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            .content-wrapper img {
                width: 100%;
            }

            h2 {
                font-size: 30px;
            }

            .content-wrapper p {
                font-size: 16px;
                padding: 0 10px;
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

            .hamburger {
                display: flex;
            }

            nav ul li a.active { /* Active state for mobile */
                border-color: #b19a4f;
                background-color: #b19a4f;
            }
        }
    </style>
</head>
<body>

<header>
    <h1><a href="pagina_principal.php">FIX YOU BODY</a></h1>
    
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

<div id="main">
    <h2>CLIENTE</h2>

    <div class="content-wrapper">
        <img src="fotoestudio.jpg" alt="Foto Estúdio" />
        <br>
        <br>
        <p>
            Fix You Body Private Studio é um espaço exclusivo e acolhedor, dedicado ao cuidado e bem-estar do seu corpo.
            Com uma abordagem personalizada, oferecemos treinos funcionais focando sempre nas suas necessidades e objetivos
            individuais. No nosso estúdio privado, cada cliente recebe atenção única, num ambiente tranquilo, garantindo
            uma experiência transformadora e resultados eficazes. Venha descobrir como podemos ajudar a melhorar sua
            qualidade de vida, restaurando equilíbrio, força e saúde.
        </p>
    </div>
</div>

<script>
    // Menu hamburguer
    const hamburger = document.getElementById('hamburger');
    const menuPrincipal = document.getElementById('menu-principal');
    
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        menuPrincipal.classList.toggle('show');
    });

    // Set active menu item based on current page
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop();
        const menuItems = document.querySelectorAll('#menu-principal li a');
        
        menuItems.forEach(item => {
            if (item.getAttribute('href') === currentPage) {
                item.classList.add('active');
            }
        });
    });
</script>
</body>
</html>