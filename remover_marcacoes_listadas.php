<?php
include "valida_session.php";
$login = $_SESSION["login"];

// Conexão ao banco de dados
$ligax = mysqli_connect(
    'localhost',
    'root',
    'root',
    '4640454_fixyoubody'
);if (!$ligax) {
  echo '<p> Falha na Ligação.</p>';
  exit;
}

mysqli_select_db($ligax, '4640454_fixyoubody');

// Consulta para verificar o tipo de utilizador
$consulta = "select * from utilizadores where login='" . $login . "'";
$result = mysqli_query($ligax, $consulta);
$registo = mysqli_fetch_assoc($result);
$sit_ut = $registo['sit_ut'];

if ($sit_ut != "ADM") exit(); // Verifica se o utilizador é um administrador

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

  nav {
    position: relative;
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
    display: block;
  }

  nav ul li a:hover {
    border-color: #b19a4f;
    background-color: #b19a4f;
  }

  .hamburger {
    display: none;
    flex-direction: column;
    cursor: pointer;
    width: 25px;
    height: 20px;
    justify-content: space-between;
  }

  .hamburger div {
    background-color: white;
    height: 3px;
    border-radius: 2px;
    transition: 0.3s;
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

  #main {
    margin-top: 120px;
    text-align: center;
    width: 100%;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-bottom: 100px;
  }

  h2 {
    font-family: 'Bebas Neue', cursive;
    font-size: 40px;
    color: #b19a4f;
    margin-bottom: 40px;
  }

  .table-wrapper {
    width: 100%;
    overflow-x: auto;
    max-width: 1100px;
    margin: 0 auto;
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

  @media (max-width: 768px) {
    table {
      font-size: 14px;
      min-width: 700px;
    }

    th, td {
      padding: 12px 10px;
    }

    .hamburger {
      display: flex;
    }

    nav ul {
      flex-direction: column;
      gap: 10px;
      background-color: rgba(0, 0, 0, 0.9);
      padding: 15px;
      border-radius: 10px;
      position: absolute;
      top: 80px;
      right: 20px;
      display: none;
      width: 250px;
      z-index: 150;
    }

    nav ul.active {
      display: flex;
    }

    header {
      padding: 20px 20px;
    }
  }
</style>
</head>
<body>

<header>
  <h1><a href="pagina_principal_adm.php" class="no-style-link">FIX YOU BODY</a></h1>
  <nav>
    <div class="hamburger" id="hamburger">
      <div></div>
      <div></div>
      <div></div>
    </div>
    <ul id="nav-menu">
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

<div id="main">
  <h2>LISTA DE MARCAÇÕES</h2>

  <?php
  $consulta = "select * From marcacoes";
  $result = mysqli_query($ligax, $consulta);
  $nregistos = mysqli_num_rows($result);
  echo 'Nº de registos encontrados: ' . $nregistos;
  ?>

  <div class="table-wrapper">
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
  </div>
</div>

<script>
  const hamburger = document.getElementById('hamburger');
  const navMenu = document.getElementById('nav-menu');

  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
  });
</script>

</body>
</html>
