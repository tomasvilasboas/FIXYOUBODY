<?php
include "valida_session.php";
$login = $_SESSION["login"];

// Conexão com o banco de dados
$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);if (!$ligax) { echo '<p> Falha na Ligação.'; exit; }

mysqli_select_db($ligax, '4640454_fixyoubody');

$consulta = "select * from utilizadores where login='" . $login . "'";
$result = mysqli_query($ligax, $consulta);

$nregistos = mysqli_num_rows($result);

$registo = mysqli_fetch_assoc($result);
$sit_ut = $registo['sit_ut'];

echo "<h5><center>Utilizador: $login</h5></center>";

if ($sit_ut != "ADM") exit();
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

    body, html {
      height: 100%;
      font-family: 'Open Sans', sans-serif;
      background-color: #f1f1f1;
      color: #333;
      overflow-x: hidden;
      margin: 0;
      display: flex;
      flex-direction: column;
    }

    /* Cabeçalho fixo */
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

    /* Estilo para o conteúdo principal */
    #main {
      margin-top: 120px; /* Ajuste para garantir que o conteúdo fique abaixo do cabeçalho fixo */
      text-align: center;
      width: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding-bottom: 100px; /* Garantir que o rodapé não sobreponha o conteúdo */
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
      margin: 0 auto;
    }

    label {
      font-weight: bold;
      display: block;
      margin-bottom: 8px;
    }

    input, button {
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

    input:focus, button:focus {
      border-color: #b19a4f;
      box-shadow: 0px 4px 10px rgba(177, 154, 79, 0.6);
    }

    button {
      background-color: #b19a4f;
      color: white;
      cursor: pointer;
      transition: background 0.3s ease;
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
      position: fixed;
      bottom: 0;
      left: 0;
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

    @media (max-width: 768px) {
      header {
        padding: 15px 20px;
      }

      .form-container {
        width: 95%;
        padding: 20px;
      }

      h2 {
        font-size: 30px;
      }
    }
  </style>
</head>
<body class="is-preload">

  <!-- Cabeçalho fixo -->
  <header>
    <h1><a href="pagina_principal_adm.php" class="no-style-link">FIX YOU BODY</a></h1>
    <nav>
        <ul>
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

  <!-- Conteúdo principal -->
  <div id="main">
    <h2>LISTA de UTILIZADORES</h2>
    <form style="max-width: 800px;">
      <table border="1" width="100%">
        <tr>
          <td>cod_ut</td>
          <td>login</td>
          <td>senha</td>
          <td>telef_ut</td>
          <td>morada_ut</td>
          <td>dat_nas_ut</td>
          <td>sexo_ut</td>
          <td>sit_ut</td>
        </tr>

        <?php
        $consulta = "select * from utilizadores";
        $result = mysqli_query($ligax, $consulta);
        $nregistos = mysqli_num_rows($result);

        for ($i = 0; $i < $nregistos; $i++) {
          $registo = mysqli_fetch_assoc($result);
          echo '<tr>';
          echo '<td>' . $registo['cod_ut'] . '</td>';
          echo '<td>' . $registo['login'] . '</td>';
          echo '<td>' . $registo['senha'] . '</td>';
          echo '<td>' . $registo['telef_ut'] . '</td>';
          echo '<td>' . $registo['morada_ut'] . '</td>';
          echo '<td>' . $registo['dat_nas_ut'] . '</td>';
          echo '<td>' . $registo['sexo_ut'] . '</td>';
          echo '<td>' . $registo['sit_ut'] . '</td>';
          echo '</tr>';
        }
        ?>
      </table>
    </form>
  </div>



</body>
</html>
