<?php
// marcacoes_calendario_adm.php
include "valida_session.php";
$login = $_SESSION["login"];

$ligax = mysqli_connect(
    'localhost',
    'root',
    'root',
    '4640454_fixyoubody'
);if(!$ligax) {
  echo '<p> Falha na Ligação.';
  exit;
}

$consulta = "SELECT * FROM utilizadores WHERE login='".$login."'";
$result = mysqli_query($ligax, $consulta);
$registo = mysqli_fetch_assoc($result);

if($registo['sit_ut'] != "ADM") exit();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Calendário de Marcações</title>

  <!-- FullCalendar -->
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet" />

  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Open Sans', sans-serif;
      background-color: #f1f1f1;
      color: #333;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
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

    /* Hamburger menu styles */
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

    /* Hamburger animation */
    .hamburger.active div:nth-child(1) {
      transform: rotate(45deg) translate(5px, 5px);
    }
    .hamburger.active div:nth-child(2) {
      opacity: 0;
    }
    .hamburger.active div:nth-child(3) {
      transform: rotate(-45deg) translate(5px, -5px);
    }

    #main {
      margin-top: 120px;
      padding: 30px;
      flex-grow: 1;
    }

    h2 {
      font-family: 'Bebas Neue', cursive;
      font-size: 40px;
      color: #b19a4f;
      margin-bottom: 20px;
      text-align: center;
    }

    #calendar {
      max-width: 900px;
      margin: auto;
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    footer {
      text-align: center;
      padding: 20px;
      background-color: #000;
      font-size: 14px;
      color: #888;
      width: 100%;
      flex-shrink: 0;
    }

    .no-style-link {
      color: inherit;
      text-decoration: none;
    }

    /* Popup styles */
    #popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
      z-index: 10000;
    }

    #popup > div {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      text-align: center;
      min-width: 300px;
      position: relative;
    }

    #popupText {
      font-size: 18px;
      color: #333;
    }

    #popup button {
      margin-top: 20px;
      padding: 10px 20px;
      background: #b19a4f;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
      nav ul {
        flex-direction: column;
        position: fixed;
        top: 80px;
        right: 0;
        background: rgba(0, 0, 0, 0.95);
        width: 220px;
        height: calc(100vh - 80px);
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

      #main {
        padding: 15px;
        margin-top: 80px;
      }

      #calendar {
        padding: 10px;
      }

      h2 {
        font-size: 32px;
      }
    }

    @media (max-width: 480px) {
      header {
        padding: 15px 20px;
      }

      #calendar {
        font-size: 14px;
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
            <li><a href="marcacoes_frm.php">Adicionar Marcações</a></li>
            <li><a href="remover_marcacoes_listadas.php">Listagem Marcações</a></li>
            <li><a href="marcacoes_calendario_adm.php">Calendário Marcações</a></li>
            <li><a href="avaliacoes.html">Avaliações</a></li>
            <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
</header>
<br>
<div id="main">
  <h2>Calendário de Marcações</h2>
  <div id="calendar"></div>
</div>

<!-- Popup Modal -->
<div id="popup" style="display:none;">
  <div>
    <p id="popupText"></p>
    <button onclick="fecharPopup()">Fechar</button>
  </div>
</div>


<script>
  // Popup functions
  function fecharPopup() {
    document.getElementById("popup").style.display = "none";
  }

  function abrirPopup(texto) {
    document.getElementById("popupText").textContent = texto;
    document.getElementById("popup").style.display = "flex";
  }

  // Hamburger menu toggle
  const hamburger = document.getElementById('hamburger');
  const menu = document.getElementById('menu-principal');

  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    menu.classList.toggle('show');
  });

  // Initialize calendar
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next',
        center: 'title',
        right: 'dayGridMonth,timeGridDay,listMonth'
      },
      locale: 'pt',
      events: 'eventos_marcacoes_adm.php',
      eventClick: function(info) {
        const quemFez = info.event.extendedProps.login;
        abrirPopup("Marcação feita por: " + quemFez);
      },
      eventContent: function(arg) {
        let hora = 'root';
        if(arg.event.start) {
          const date = arg.event.start;
          hora = date.toLocaleTimeString('pt-PT', {hour: '2-digit', minute:'2-digit'});
        }
        return {
          html: `<b>${hora}</b> - <i>${arg.event.title}</i>`
        };
      },
      eventColor: '#b19a4f',
      nowIndicator: true,
      slotDuration: '00:30:00',
      slotLabelFormat: {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
      }
    });

    calendar.render();
  });
</script>

</body>
</html>