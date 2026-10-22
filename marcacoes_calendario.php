<?php
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

if($registo['sit_ut'] != "UTI") exit();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Calendário de Marcações</title>

  <!-- FullCalendar -->
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

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

    .no-style-link {
      color: inherit;
      text-decoration: none;
    }

    .no-style-link:hover,
    .no-style-link:visited,
    .no-style-link:active {
      color: inherit;
      text-decoration: none;
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

    /* Estilos do calendário */
    .fc-header-toolbar {
      background-color: #1a1a1a;
      color: white;
      padding: 12px 20px;
      border-radius: 8px 8px 0 0;
      margin-bottom: 0;
      border-bottom: 3px solid #b19a4f;
    }

    .fc-toolbar-title {
      font-family: 'Bebas Neue', cursive;
      font-size: 1.8em;
      color: #b19a4f;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    .fc-button {
      background-color: transparent !important;
      border: 2px solid #b19a4f !important;
      color: #b19a4f !important;
      text-transform: uppercase;
      font-weight: bold;
      padding: 6px 15px !important;
      border-radius: 4px !important;
      transition: all 0.3s ease;
      font-size: 0.9em;
    }

    .fc-button:hover {
      background-color: #b19a4f !important;
      color: white !important;
    }

    .fc-button-active {
      background-color: #b19a4f !important;
      color: white !important;
    }

    .fc-button-group {
      gap: 8px;
    }

    .fc-today-button {
      margin-left: 10px !important;
    }

    /* Estilo dos dias do mês */
    .fc-daygrid-day-number {
      color: #333;
      font-weight: bold;
    }

    .fc-day-today {
      background-color: rgba(177, 154, 79, 0.1) !important;
    }

    .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
      color: #b19a4f;
      font-weight: bold;
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
      
      .fc-header-toolbar {
        flex-direction: column;
        gap: 12px;
        padding: 10px;
      }
      
      .fc-toolbar-title {
        font-size: 1.4em;
        order: 1;
        margin-bottom: 5px;
        text-align: center;
      }
      
      .fc-toolbar-chunk:nth-child(1) {
        order: 2;
        display: flex;
        justify-content: center;
        width: 100%;
      }
      
      .fc-toolbar-chunk:nth-child(3) {
        order: 3;
        display: flex;
        justify-content: center;
        width: 100%;
      }
      
      .fc-button {
        font-size: 0.8em;
        padding: 5px 10px !important;
      }
      
      .fc-today-button {
        margin-left: 0 !important;
        margin-top: 5px;
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
  // Funções para controlar o popup (adicionar estas no final do seu script)
  function abrirPopup(conteudo) {
    document.getElementById('popupText').innerHTML = conteudo;
    document.getElementById('popup').style.display = 'flex';
  }

  function fecharPopup() {
    document.getElementById('popup').style.display = 'none';
  }

  // Hamburger menu toggle
  const hamburger = document.getElementById('hamburger');
  const menu = document.getElementById('menu-principal');

  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    menu.classList.toggle('show');
  });

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev',
        center: 'title',
        right: 'next dayGridMonth,timeGridWeek,listWeek today'
      },
      locale: 'pt',
      events: {
        url: 'eventos_marcacoes.php',
        method: 'GET',
        failure: function() {
          abrirPopup('Falha ao carregar as marcações. Por favor, recarregue a página.');
        }
      },
      height: 'auto',
      firstDay: 0, // Começa no domingo
      
      buttonText: {
        today: 'HOJE',
        month: 'MÊS',
        week: 'SEMANA',
        list: 'LISTA'
      },
      
      columnHeaderText: function(date) {
        const dias = ['dom.', 'seg.', 'ter.', 'qua.', 'qui.', 'sex.', 'sáb.'];
        return dias[date.getDay()];
      },
      
      eventClick: function(info) {
        info.jsEvent.preventDefault(); // Impede comportamentos padrão
        
        const cliente = info.event.extendedProps.cliente || 'Não informado';
        const hora = info.event.extendedProps.hora_completa || info.event.start.toLocaleTimeString('pt-PT', {hour: '2-digit', minute:'2-digit'});
        const data = info.event.start.toLocaleDateString('pt-PT');
        
        abrirPopup(`
          <strong>Detalhes da Marcação</strong><br><br>
          <strong>Cliente:</strong> ${cliente}<br>
          <strong>Data:</strong> ${data}<br>
          <strong>Hora:</strong> ${hora}
        `);
      },
      
      eventContent: function(arg) {
        let hora = 'root';
        if(arg.event.start) {
          hora = arg.event.start.toLocaleTimeString('pt-PT', {
            hour: '2-digit', 
            minute:'2-digit'
          }).replace(':', 'h');
        }
        
        const cliente = arg.event.extendedProps?.cliente || 'Marcação';
        
        return {
          html: `
            <div style="padding: 2px; font-size: 0.8em;">
              <b>${hora}</b><br>
              <small>${cliente}</small>
            </div>
          `
        };
      },
      
      eventColor: '#b19a4f',
      nowIndicator: true,
      navLinks: true
    });

    calendar.render();
  });
</script>
</body>
</html>