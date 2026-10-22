<?php
include "valida_session.php";
$login=$_SESSION["login"];
//echo $login;

$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);  if(!$ligax) {echo '<p> Falha na Ligação.';exit;}

  mysqli_select_db($ligax,'4640454_fixyoubody');

  $consulta = "select * from utilizadores where login='".$login."'";
  $result = mysqli_query($ligax, $consulta);

  $nregistos = mysqli_num_rows($result);
  //echo 'Nº de registos encontrados:'.$nregistos;

  $registo = mysqli_fetch_assoc($result);

  $sit_ut=$registo['sit_ut'];

  //echo "<h5><center>Utilizador:$tipo_ut</h5></center>";
  echo "<h5><center>Utilizador: $login</h5></center>";



if($sit_ut!="ADM") exit();
//if($tipo_ut=="fun") header("Location: pagina_principal_fun.php");
//if($tipo_ut!="adm") exit();



?>




<!DOCTYPE html>
<html lang="pt">
<link rel="stylesheet" href="assets/css/main.css" />
<head>
  <meta charset="UTF-8">
  <title>Marcaçãos</title>
  <style>
        body {
          font-family: Arial, sans-serif;
          margin: 30px;
        }
        form {
          max-width: 400px;
          margin: auto;
          padding: 20px;
          border: 1px solid #ccc;
          border-radius: 8px;
        }
        label {
          display: block;
          margin-bottom: 5px;
          font-weight: bold;
        }
        input, select, button {
          width: 100%;
          padding: 8px;
          margin-bottom: 15px;
          border: 1px solid #ccc;
          border-radius: 4px;
        }
        button {
          background-color: #6c5a5a00;
          color: #fff;
          border: none;
          cursor: pointer;
        }
        button:hover {
          background-color: #5f5f5f;
        }
      </style>
</head>
<body class="is-preload">

		<!-- Wrapper -->
    <div id="wrapper">

<!-- Header -->
  <header id="header">
    <h1><a href="pagina_principal_adm.php">FIX YOU BODY</a></h1>
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
    <nav class="main">
      <ul>
        <li class="menu">
          <a class="fa-bars" href="#menu">Menu</a>
        </li>
      </ul>
    </nav>
  </header>

<!-- Menu -->
  <section id="menu">


    <!-- Actions -->
      <section>
        <ul class="actions stacked">
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </section>

  </section>

<div id="main">


  <h1 style="text-align: center;">LISTA de MARCAÇÕES</h1>
  

  <?php
  $ligax = mysqli_connect('localhost', 'root', 'root');

  if (!$ligax) {echo '<p> Falha na Ligação.'; exit;}

  mysqli_select_db($ligax, 'vilaspap_bd');
  
  $consulta = "SELECT MARCACOES.*, utilizadores.login 
  FROM MARCACOES 
  INNER JOIN utilizadores ON MARCACOES.cod_ut = utilizadores.cod_ut";
  
  $result = mysqli_query($ligax, $consulta);

  $nregistos = mysqli_num_rows($result);
  echo 'Nº de registos encontrados: '.$nregistos;
?>


<form style="max-width: 600px;">
<table border= "1" width="100%">
<tr>
  <td>id_marc</td>
  <td>cod_ut</td>
  <td>nome</td>
  <td>data</td>
  <td>hora</td>
</tr>

<?php
  for($i=0; $i <$nregistos; $i++) 
  {
    $registo = mysqli_fetch_assoc($result);
    echo '<tr>';
    echo '<td>'.$registo['id_marc'].'</td>'; 
    echo '<td>'.$registo['cod_ut'].'</td>';
    echo '<td>'.$registo['login'].'</td>';
    echo '<td>'.$registo['data'].'</td>';
    echo '<td>'.$registo['hora'].'</td>';
       
    
    echo '</tr>';
    }
?>
</table>
</form>


</div>

</body>

</html>


