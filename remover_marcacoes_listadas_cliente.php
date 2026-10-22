<?php
include "valida_session.php";
$login = $_SESSION["login"];

// Conexão ao banco de dados
$ligax = mysqli_connect(
    'localhost',
    'root',
    'root',
    '4640454_fixyoubody'
);
if (!$ligax) {
  echo '<p> Falha na Ligação.</p>';
  exit;
}

mysqli_select_db($ligax, '4640454_fixyoubody');

// Consulta para verificar o tipo de utilizador
$consulta = "select * from utilizadores where login='" . $login . "'";
$result = mysqli_query($ligax, $consulta);
$registo = mysqli_fetch_assoc($result);
$sit_ut = $registo['sit_ut'];

if ($sit_ut != "UTI") exit(); // Verifica se o utilizador é um Cliente

?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listagem Marcações</title>
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

    #main {
      margin-top: 120px;
      text-align: center;
      width: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding-bottom: 100px;
      flex-grow: 1;
    }

    h2 {
      font-family: 'Bebas Neue', cursive;
      font-size: 40px;
      color: #b19a4f;
      margin-bottom: 40px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      font-size: 18px;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 30px 25px;
      text-align: left;
      line-height: 1.8;
    }

    th {
      background-color: #f2f2f2;
      font-size: 20px;
    }

    button {
      background-color: #b19a4f;
      color: white;
      cursor: pointer;
      transition: background 0.3s ease;
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 18px;
    }

    button:hover {
      background-color: #8d7b47;
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

    .no-style-link:hover,
    .no-style-link:visited,
    .no-style-link:active {
      color: inherit;
      text-decoration: none;
    }

    /* Botão hamburger - Estilo corrigido */
    .hamburger {
      display: none;
      cursor: pointer;
      background: transparent;
      border: none;
      padding: 0;
      z-index: 20;
      position: absolute;
      right: 40px; /* Posição igual ao segundo código */
      top: 25px;
    }

    .hamburger-box {
      width: 30px;
      height: 24px;
      display: inline-block;
      position: relative;
    }

    .hamburger-inner {
      width: 100%;
      height: 3px;
      background-color: #b19a4f;
      border-radius: 2px;
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      transition: transform 0.2s ease;
    }

    .hamburger-inner::before,
    .hamburger-inner::after {
      content: 'root';
      width: 100%;
      height: 3px;
      background-color: #b19a4f;
      border-radius: 2px;
      position: absolute;
      left: 0;
      transition: transform 0.3s ease;
    }

    .hamburger-inner::before {
      top: -10px;
    }

    .hamburger-inner::after {
      top: 10px;
    }

    .hamburger.active .hamburger-inner {
      background-color: transparent;
    }

    .hamburger.active .hamburger-inner::before {
      transform: translateY(10px) rotate(45deg);
    }

    .hamburger.active .hamburger-inner::after {
      transform: translateY(-10px) rotate(-45deg);
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
        height: calc(100vh - 80px);
        padding-top: 20px;
        transform: translateX(100%);
        transition: transform 0.3s ease-in-out;
      }

      nav ul.show {
        transform: translateX(0);
      }

      .hamburger {
        display: block;
      }

      header {
        flex-direction: row;
        align-items: center;
      }
      
      th, td {
        padding: 15px 10px;
        font-size: 16px;
      }
    }
  </style>
</head>
<body>

  <!-- Cabeçalho fixo -->
  <header>
    <h1><a href="pagina_principal.php" class="no-style-link">FIX YOU BODY</a></h1>
    
    <button class="hamburger" id="hamburger" aria-label="Menu">
      <span class="hamburger-box">
        <span class="hamburger-inner"></span>
      </span>
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

  <!-- Conteúdo principal -->
  <div id="main">
    <h2>LISTA DE MARCAÇÕES</h2>

    <?php
    $consulta = "SELECT * FROM marcacoes WHERE login='" . mysqli_real_escape_string($ligax, $login) . "'";
    $result = mysqli_query($ligax, $consulta);
    $nregistos = mysqli_num_rows($result);
    echo 'Nº de registos encontrados: ' . $nregistos;
    ?>

    <form style="max-width: 800px;">
      <table>
        <thead>
          <tr>
            <th>ID Marc</th>
            <th>Nome</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Ação</th>
          </tr>
        </thead>
        <tbody>
          <?php
          for ($i = 0; $i < $nregistos; $i++) {
            $registo = mysqli_fetch_assoc($result);
            $id_marc2 = $registo['id_marc'];
            echo '<tr>';
            echo '<td>' . $registo['id_marc'] . '</td>';
            echo '<td>' . $registo['login'] . '</td>';
            echo '<td>' . $registo['data'] . '</td>';
            echo '<td>' . $registo['hora'] . '</td>';
            echo "<td><a href='remover_marcacoes.php?id_marc=" . urlencode($id_marc2) . "' style='background-color:#b19a4f; color:white; padding:10px 20px; border-radius:8px; text-decoration:none; font-size:18px;'>Remover</a></td>";
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </form>
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