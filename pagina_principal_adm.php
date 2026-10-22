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

mysqli_select_db($ligax, '4640454_fixyoubody');

$consulta = "SELECT * FROM utilizadores WHERE login='" . $login . "'";
$result = mysqli_query($ligax, $consulta);

$nregistos = mysqli_num_rows($result);
$registo = mysqli_fetch_assoc($result);

$sit_ut = $registo['sit_ut'];

if ($sit_ut != "ADM") exit();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>FIX YOU BODY</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            font-family: 'Open Sans', sans-serif;
            background-color: #f1f1f1;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            height: 80px;
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

        h2 {
            text-align: center;
            font-family: 'Bebas Neue', cursive;
            font-size: 40px;
            color: #b19a4f;
            margin-top: 120px;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            text-align: center;
        }

        .container img {
            width: 60%;
            max-width: 500px;
            border-radius: 10px;
        }

        .container p {
            font-size: 18px;
            color: #444;
            line-height: 1.7;
            margin-top: 20px;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #000;
            font-size: 14px;
            color: #888;
            margin-top: auto;
        }

        .no-style-link {
            color: inherit;
            text-decoration: none;
        }

        .no-style-link:hover {
            color: inherit;
            text-decoration: none;
        }

        /* Botão hamburger */
                .hamburger {
                    display: none;
                    flex-direction: column;
                    justify-content: space-around;
                    width: 30px;
                    height: 25px;
                    background: transparent;
                    border: none;
                    cursor: pointer;
                    z-index: 20;
                    position: absolute;
                    right: 10px; /* MAIS À DIREITA */
                    top: 25px;
                }

                .hamburger div {
                    width: 30px;
                    height: 3px;
                    background-color: #b19a4f; /* COR DOURADA */
                    border-radius: 2px;
                    transition: all 0.3s linear;
                }


        .hamburger.active div:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active div:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active div:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Responsividade */
        @media (max-width: 768px) {
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

            header {
                flex-direction: row;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<header>
    <h1><a href="pagina_principal_adm.php" class="no-style-link">FIX YOU BODY</a></h1>
    
    <button class="hamburger" id="hamburger" aria-label="Menu">
        <div></div>
        <div></div>
        <div></div>
    </button>

    <nav>
        <ul id="menu-principal">
            <li><a href="admin_chat.php">Chats</a></li>
            <li><a href="inserir_utilizadores_frm.htm">Adicionar utilizadores</a></li>
            <li><a href="remover_utilizadores_listados.php">Listagem utilizadores</a></li>
            <li><a href="marcacoes.frm.html">Adicionar Marcações</a></li>
            <li><a href="remover_marcacoes_listadas.php">Listagem Marcações</a></li>
            <li><a href="marcacoes_calendario_adm.php">Calendário Marcações</a></li>
            <li><a href="avaliacoes.html">Avaliações</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<h2>Área Administrativa</h2>

<div class="container">
    <img src="fotoestudio.jpg" alt="Foto Estúdio">
    <p>
        Fix You Body Private Studio é um espaço exclusivo e acolhedor, dedicado ao cuidado e bem-estar do seu corpo.
        Com uma abordagem personalizada, oferecemos treinos funcionais focando sempre nas suas necessidades e objetivos individuais.
        No nosso estúdio privado, cada cliente recebe atenção única, num ambiente tranquilo, garantindo uma experiência transformadora
        e resultados eficazes. Venha descobrir como podemos ajudar a melhorar sua qualidade de vida, restaurando equilíbrio, força e saúde.
    </p>
</div>



<script>
    const hamburger = document.getElementById('hamburger');
    const menu = document.getElementById('menu-principal');

    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        menu.classList.toggle('show');
    });
</script>

</body>
</html>
