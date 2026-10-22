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

$consulta = "select * from utilizadores where login='" . $login . "'";
$result = mysqli_query($ligax, $consulta);
$registo = mysqli_fetch_assoc($result);
$sit_ut = $registo['sit_ut'];
if ($sit_ut != "ADM") exit();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listagem Utilizadores</title>
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

    .container {
      max-width: 1000px;
      margin: 120px auto 40px;
      padding: 20px;
      width: 100%;
      text-align: center;
    }

    .container h2,
    .container p {
      text-align: center;
    }

    .form-container {
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 1100px;
      margin: 0 auto;
    }

    .table-wrapper {
      overflow-x: auto;
      width: 100%;
      margin: 20px 0;
      display: flex;
      justify-content: center;
    }

    table {
      width: auto;
      border-collapse: collapse;
      margin: 0 auto;
    }

    h2 {
      font-family: 'Bebas Neue', cursive;
      font-size: 40px;
      color: #b19a4f;
      margin-bottom: 40px;
    }
    
    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 12px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .btn-remove {
      background-color: #b19a4f;
      color: white;
      padding: 8px 16px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 14px;
      display: inline-block;
      transition: background 0.3s ease;
    }

    .btn-remove:hover {
      background-color: #8d7b47;
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

      .form-container {
        width: 95%;
        padding: 15px;
      }

      table {
        font-size: 14px;
      }

      th, td {
        padding: 8px;
      }
      
      .table-wrapper {
        justify-content: flex-start;
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

<div class="container">
  <h2>LISTA DE UTILIZADORES</h2>
  <?php
$ligax = mysqli_connect(
    'localhost',
    'root',
    'root',
    '4640454_fixyoubody'
);
  if (!$ligax) { echo '<p> Falha na Ligação.'; exit; }
  mysqli_select_db($ligax, '4640454_fixyoubody');
  $consulta = "select * from utilizadores";
  $result = mysqli_query($ligax, $consulta);
  $nregistos = mysqli_num_rows($result);
  echo '<p>Nº de registos encontrados: '.$nregistos.'</p>';
  ?>
  <br>
  
  <div class="form-container">
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>Senha</th>
            <th>Telefone</th>
            <th>Morada</th>
            <th>Nascimento</th>
            <th>Sexo</th>
            <th>Cargo</th>
            <th>Remover</th>
          </tr>
        </thead>
        <tbody>
          <?php
          for ($i = 0; $i < $nregistos; $i++) {
            $registo = mysqli_fetch_assoc($result);
            $cod_ut2 = $registo['cod_ut'];
            echo '<tr>';
            echo '<td>'.$registo['login'].'</td>';
            echo '<td>'.$registo['senha'].'</td>';
            echo '<td>'.$registo['telef_ut'].'</td>';
            echo '<td>'.$registo['morada_ut'].'</td>';
            echo '<td>'.$registo['dat_nas_ut'].'</td>';
            echo '<td>'.$registo['sexo_ut'].'</td>';
            echo '<td>'.$registo['sit_ut'].'</td>';
            echo "<td><a href='remover_utilizadores.php?cod_ut=" . urlencode($cod_ut2) . "' class='btn-remove'>Remover</a></td>";
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
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