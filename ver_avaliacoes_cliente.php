<?php
include "valida_session.php";
$login = $_SESSION["login"];

$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);if (!$ligax) die("Erro na ligação.");

$consulta = "SELECT cod_ut FROM utilizadores WHERE login = '$login'";
$resultado = mysqli_query($ligax, $consulta);
$utilizador = mysqli_fetch_assoc($resultado);
$cod_ut = $utilizador['cod_ut'];

$sql = "SELECT * FROM avaliacoes WHERE cod_ut = '$cod_ut' ORDER BY data ASC";
$res = mysqli_query($ligax, $sql);

$contador = 1;
$ordem_texto = ["Primeira", "Segunda", "Terceira", "Quarta", "Quinta", "Sexta", "Sétima", "Oitava", "Nona", "Décima"];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Minhas Avaliações | FIX YOU BODY</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body, html {
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

    .no-style-link {
      color: inherit;
      text-decoration: none;
      cursor: pointer;
    }

    h2 {
      margin-top: 120px;
      text-align: center;
      font-family: 'Bebas Neue', cursive;
      font-size: 40px;
      color: #b19a4f;
    }

    .avaliacoes-container {
      width: 100%;
      padding: 30px 40px;
      display: flex;
      flex-direction: column;
      gap: 40px;
      background-color: #f5f5f5;
    }

    .avaliacao {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      background-color: #fff;
      padding: 24px 32px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      gap: 40px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .texto {
      flex: 2;
    }

    .texto h3 {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 28px;
      color: #b19a4f;
      margin-bottom: 12px;
    }

    .texto p {
      font-size: 16px;
      margin: 4px 0;
    }

    .fotos {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 10px;
      align-items: flex-end;
    }

    .fotos img {
      max-width: 140px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      transition: transform 0.3s ease;
      cursor: pointer;
    }

    .fotos img:hover {
      transform: scale(1.05);
    }

    @media (max-width: 900px) {
      .avaliacao {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .fotos {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
      }

      .fotos img {
        max-width: 100px;
      }
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      padding-top: 60px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.8);
    }

    .modal-content {
      margin: auto;
      display: block;
      max-width: 90%;
      max-height: 90%;
      border-radius: 10px;
    }

    .modal:hover {
      cursor: pointer;
    }

    footer {
      text-align: center;
      padding: 20px;
      background-color: #000;
      font-size: 14px;
      color: #888;
      margin-top: auto;
    }

    /* Botão hamburger */
    .hamburger {
      display: none;
      flex-direction: column;
      justify-content: space-around;
      width: 30px;
      height: 25px;
      background: transparent;
      border: none;
      cursor: pointer;
      z-index: 20;
      position: absolute;
      right: 10px;
      top: 25px;
    }

    .hamburger div {
      width: 30px;
      height: 3px;
      background-color: #b19a4f;
      border-radius: 2px;
      transition: all 0.3s linear;
    }

    .hamburger.active div:nth-child(1) {
      transform: rotate(45deg) translate(5px, 5px);
    }

    .hamburger.active div:nth-child(2) {
      opacity: 0;
    }

    .hamburger.active div:nth-child(3) {
      transform: rotate(-45deg) translate(6px, -6px);
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

      header {
        flex-direction: row;
        align-items: center;
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
      <li><a href="cliente_chat.php">Chat</a></li>
      <li><a href="marcacoes.frm_cliente.html">Marcar</a></li>
      <li><a href="remover_marcacoes_listadas_cliente.php">Minhas Marcações</a></li>
      <li><a href="marcacoes_calendario.php">Calendário</a></li>
      <li><a href="ver_avaliacoes_cliente.php">Avaliações</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
</header>

<h2>As Minhas Avaliações</h2>

<div class="avaliacoes-container">
<?php while ($linha = mysqli_fetch_assoc($res)): ?>
  <div class="avaliacao">
    <div class="texto">
      <h3><?= $ordem_texto[$contador - 1] ?? "{$contador}ª" ?> Avaliação</h3>
      <p><strong>Data:</strong> <?= htmlspecialchars($linha['data']) ?></p>
      <p><strong>Peso:</strong> <?= htmlspecialchars($linha['peso']) ?> kg</p>
      <p><strong>Cintura:</strong> <?= htmlspecialchars($linha['cintura']) ?> cm</p>
      <p><strong>Quadril:</strong> <?= htmlspecialchars($linha['quadril']) ?> cm</p>
      <p><strong>Braço:</strong> <?= htmlspecialchars($linha['braco']) ?> cm</p>
      <p><strong>Coxa:</strong> <?= htmlspecialchars($linha['coxa']) ?> cm</p>
    </div>
    <div class="fotos">
      <?php
        $fotos = explode(',', $linha['foto']);
        if (!empty($linha['foto'])) {
          foreach ($fotos as $foto) {
            $foto = trim($foto);
            $caminhoFisico = __DIR__ . '/fotos_avaliacoes/' . $foto;
            $caminhoWeb = 'fotos_avaliacoes/' . rawurlencode($foto);
            if (file_exists($caminhoFisico)) {
              echo "<img src=\"$caminhoWeb\" alt=\"Foto do cliente\" onclick=\"abrirModal('$caminhoWeb')\">";
            }
          }
        } else {
          echo "<p>Sem foto</p>";
        }
      ?>
    </div>
  </div>
  <?php $contador++; ?>
<?php endwhile; ?>
</div>

<!-- Modal -->
<div id="imagemModal" class="modal">
  <img class="modal-content" id="imgGrande" alt="Imagem em tamanho grande">
</div>

<script>
  function abrirModal(src) {
    const modal = document.getElementById("imagemModal");
    const modalImg = document.getElementById("imgGrande");
    modal.style.display = "block";
    modalImg.src = src;
  }

  window.onclick = function(event) {
    const modal = document.getElementById("imagemModal");
    if (event.target === modal) {
      modal.style.display = "none";
    }
  }

  // Hamburger menu functionality
  const hamburger = document.getElementById('hamburger');
  const menu = document.getElementById('menu-principal');

  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    menu.classList.toggle('show');
  });
</script>

</body>
</html>