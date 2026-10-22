<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Resultado da Inserção</title>
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
      padding-bottom: 100px;
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
<body>

<header>
  <h1><a href="pagina_principal_adm.php" class="no-style-link">FIX YOU BODY</a></h1>
  <nav>
      <ul>
          <li><a href="admin_chat.php">Chats</a></li>
          <li><a href="inserir_utilizadores_frm.htm">Adicionar utilizadores</a></li>
          <li><a href="remover_utilizadores_listados.php">Listagem utilizadores</a></li>
          <li><a href="marcacoes_frm.php">Adicionar Marcações</a></li>
          <li><a href="remover_marcacoes_listadas.php">Listagem Marcações</a></li>
          <li><a href="marcacoes_calendario_adm.php">Calendário Marcações</a></li>
          <li><a href="logout.php">Logout</a></li>
      </ul>
  </nav>
</header>
<br><br>

<div id="main">
  <h2>Resultado da Inserção</h2>

  <div class="form-container">
    <?php
    // Ativar relatórios de erros do MySQLi
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $login = $_POST['login'] ?? 'root';
    $senha = $_POST['senha'] ?? 'root';
    $telef_ut = $_POST['telef_ut'] ?? 'root';
    $morada_ut = $_POST['morada_ut'] ?? 'root';
    $dat_nas_ut = $_POST['dat_nas_ut'] ?? 'root';
    $sexo_ut = $_POST['sexo_ut'] ?? 'root';
    $sit_ut = $_POST['sit_ut'] ?? 'root';

    if (!$login || !$senha) {
        echo '<p style="color:red;">Atenção! Campos em falta. Volte atrás e tente de novo.</p>';
        exit;
    }

    // Define is_admin conforme o tipo de utilizador
    $is_admin = ($sit_ut === 'ADM') ? 1 : 0;

    // Conexão à base de dados
$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);    // Preparar a query
    $stmt = mysqli_prepare($ligax, "INSERT INTO utilizadores 
      (login, senha, telef_ut, morada_ut, dat_nas_ut, sexo_ut, sit_ut, is_admin) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Associar os parâmetros SEM encriptação da senha
    mysqli_stmt_bind_param($stmt, 'sssssssi', $login, $senha, $telef_ut, $morada_ut, $dat_nas_ut, $sexo_ut, $sit_ut, $is_admin);

    try {
        mysqli_stmt_execute($stmt);
        echo '<p style="color:green;">Dados inseridos com sucesso.</p>';
    } catch (Exception $e) {
        echo '<p style="color:red;">Erro ao inserir os dados: ' . $e->getMessage() . '</p>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($ligax);
    ?>
    <br>
    <a href="remover_utilizadores_listados.php">Listagem de utilizadores</a>
  </div>
</div>

</body>
</html>
