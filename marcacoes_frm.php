<?php
include "valida_session.php";

// Ligação à base de dados
$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);if (!$ligax) {
    die("Erro na ligação à base de dados.");
}

// Consulta apenas utilizadores normais (exclui ADM)
$consulta = "SELECT login FROM utilizadores ORDER BY login";
$resultado = mysqli_query($ligax, $consulta);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Marcações</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    html, body {
      height: 100%;
      font-family: 'Open Sans', sans-serif;
      background-color: #f1f1f1;
      color: #333;
      overflow-x: hidden;
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
      padding: 20px;
      flex: 1;
    }
    h2 {
      font-family: 'Bebas Neue', cursive;
      font-size: 40px;
      color: #b19a4f;
      margin-bottom: 40px;
    }
    .form-container {
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
      width: 90%;
      max-width: 500px;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 8px;
      text-align: left;
    }
    input, select, button {
      width: 100%;
      padding: 12px;
      border: 2px solid #ddd;
      border-radius: 10px;
      background-color: #f7f7f7;
      color: #333;
      font-size: 16px;
      transition: 0.3s ease;
      margin-bottom: 15px;
    }
    input:focus, button:focus, select:focus {
      border-color: #b19a4f;
      box-shadow: 0px 4px 10px rgba(177, 154, 79, 0.6);
      outline: none;
    }
    button {
      background-color: #b19a4f;
      color: white;
      cursor: pointer;
      font-weight: bold;
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

    /* Responsivo */
    @media (max-width: 768px) {
      header {
        padding: 20px;
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
            <li><a href="avaliacoes.php">Avaliações</a></li>
            <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
</header>

<div id="main">
  <h2>MARCAÇÕES</h2>
  <form class="form-container" action="marcacoes.php" method="post">

    <label for="login_utilizador">Utilizador:</label>
    <select id="login_utilizador" name="login_utilizador" required>
      <option value="root">Escolha um utilizador</option>
      <?php
      while ($linha = mysqli_fetch_assoc($resultado)) {
        echo "<option value='" . htmlspecialchars($linha['login']) . "'>" . htmlspecialchars($linha['login']) . "</option>";
      }
      ?>
    </select>

    <label for="data">Data:</label>
    <input type="date" id="data" name="data_marcacao" required>

    <label for="hora">Hora:</label>
    <select id="hora" name="hora_marcacao" required>
      <option value="root">Selecione a hora</option>
      <?php
      for ($h = 9; $h <= 21; $h++) {
        $horaDB = str_pad($h, 2, "0", STR_PAD_LEFT) . ":00:00";
        $horaLabel = str_pad($h, 2, "0", STR_PAD_LEFT) . ":00";
        echo "<option value='$horaDB'>$horaLabel</option>";
      }
      ?>
    </select>

    <button type="submit" name="marcar">Marcar</button>

  </form>
</div>


<script>
  document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const menu = document.getElementById('menu-principal');

    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('active');
      menu.classList.toggle('show');
    });
  });
</script>

</body>
</html>