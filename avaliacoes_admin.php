<?php
//avaliacoes_admin.php
include "valida_session.php";
$login = $_SESSION["login"];

$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);if (!$ligax) die("Erro na ligação à base de dados.");

$consulta = "SELECT * FROM utilizadores WHERE login='$login'";
$result = mysqli_query($ligax, $consulta);
$registo = mysqli_fetch_assoc($result);
if ($registo['sit_ut'] != "ADM") exit;
?>


<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inserir Avaliação | FIX YOU BODY</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body, html { font-family: 'Open Sans', sans-serif; background-color: #f1f1f1; color: #333; display: flex; flex-direction: column; min-height: 100vh; }
    header { position: fixed; top: 0; width: 100%; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; background: rgba(0, 0, 0, 0.6); z-index: 10; height: 80px; }
    header h1 { font-family: 'Bebas Neue', cursive; font-size: 32px; color: #b19a4f; letter-spacing: 2px; }
    nav ul { list-style: none; display: flex; gap: 20px; }
    nav ul li a { color: #fff; text-decoration: none; font-weight: bold; padding: 8px 16px; border: 2px solid transparent; border-radius: 20px; transition: 0.3s ease; }
    nav ul li a:hover { border-color: #b19a4f; background-color: #b19a4f; }
    .no-style-link { color: inherit; text-decoration: none; cursor: pointer; }

    /* Hamburger styles */
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

    /* Responsive */
    @media (max-width: 768px) {
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

      .hamburger {
        display: flex;
      }
    }

    h2 { margin-top: 120px; text-align: center; font-family: 'Bebas Neue', cursive; font-size: 40px; color: #b19a4f; }
    .container { 
      max-width: 600px;
      margin: 30px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    form label {
      display: block;
      margin-bottom: 6px;
      margin-top: 16px;
      font-weight: bold;
      color: #1e293b;
    }
    form input, form select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background: #f9fafb;
      font-size: 1rem;
    }
    form input:focus, form select:focus {
      border-color: #b19a4f;
      outline: none;
    }
    button {
      margin-top: 24px;
      width: 100%;
      padding: 12px;
      background-color: #b19a4f;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #8a763d;
    }
    footer {
      text-align: center;
      padding: 20px;
      background-color: #000;
      font-size: 14px;
      color: #888;
      margin-top: auto;
    }
  </style>
</head>
<body>

<header>
  <h1><a href="pagina_principal_adm.php" class="no-style-link">FIX YOU BODY</a></h1>
  <nav>
    <div class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false" role="button" tabindex="0">
      <div></div>
      <div></div>
      <div></div>
    </div>
    <ul id="nav-menu">
      <li><a href="admin_chat.php">Chats</a></li>
      <li><a href="inserir_utilizadores_frm.htm">Adicionar utilizadores</a></li>
      <li><a href="remover_utilizadores_listados.php">Listagem utilizadores</a></li>
      <li><a href="marcacoes.frm.html">Adicionar Marcações</a></li>
      <li><a href="remover_marcacoes_listadas.php">Listagem Marcações</a></li>
      <li><a href="marcacoes_calendario_adm.php">Calendário Marcações</a></li>
      <li><a href="avaliacoes.php">Avaliações</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
</header>

<h2>Inserir Avaliação Física</h2>

<div class="container">
  <form action="gravar_avaliacao.php" method="POST" enctype="multipart/form-data">
    <label for="cod_ut">Nome do Cliente</label>
    <select name="cod_ut" required>
      <option value="root">Selecione o utilizador</option>
      <?php
        $sql_users = "SELECT cod_ut, login FROM utilizadores WHERE sit_ut = 'UTI' ORDER BY login ASC";
        $res_users = mysqli_query($ligax, $sql_users);
        while ($user = mysqli_fetch_assoc($res_users)) {
          echo "<option value='{$user['cod_ut']}'>{$user['login']}</option>";
        }
      ?>
    </select>

    <label for="data">Data</label>
    <input type="date" name="data" required>

    <label for="peso">Peso (kg)</label>
    <input type="number" step="0.1" name="peso" required>

    <label for="cintura">Cintura (cm)</label>
    <input type="number" name="cintura">

    <label for="quadril">Quadril (cm)</label>
    <input type="number" name="quadril">

    <label for="braco">Braço (cm)</label>
    <input type="number" name="braco">

    <label for="coxa">Coxa (cm)</label>
    <input type="number" name="coxa">

    <label for="foto">Foto do Cliente</label>
    <input type="file" name="fotos[]" accept="image/*" multiple>

    <button type="submit">Gravar Avaliação</button>
  </form>
</div>

<script>
  const hamburger = document.getElementById('hamburger');
  const navMenu = document.getElementById('nav-menu');

  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
    const expanded = hamburger.getAttribute('aria-expanded') === 'true';
    hamburger.setAttribute('aria-expanded', !expanded);
  });

  hamburger.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      hamburger.click();
    }
  });
</script>

</body>
</html>
