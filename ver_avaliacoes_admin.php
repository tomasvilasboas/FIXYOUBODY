<?php
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

// Obter filtro da pesquisa (se existir)
$filtro = "root";
$termo_pesquisa = "root";

if (isset($_GET['pesquisa']) && !empty(trim($_GET['pesquisa']))) {
    $termo_pesquisa = mysqli_real_escape_string($ligax, trim($_GET['pesquisa']));
    $filtro = "WHERE u.login LIKE '%$termo_pesquisa%'";
}

// Consulta com ou sem filtro
$sql = "SELECT a.*, u.login 
        FROM avaliacoes a 
        JOIN utilizadores u ON a.cod_ut = u.cod_ut 
        $filtro
        ORDER BY a.data DESC";
$res = mysqli_query($ligax, $sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ver Avaliações | FIX YOU BODY</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body, html { font-family: 'Open Sans', sans-serif; background-color: #f1f1f1; color: #333; display: flex; flex-direction: column; min-height: 100vh; }
    header { position: fixed; top: 0; width: 100%; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; background: rgba(0, 0, 0, 0.6); z-index: 10; height: 80px; }
    header h1 { font-family: 'Bebas Neue', cursive; font-size: 32px; color: #b19a4f; letter-spacing: 2px; }
    
    /* Menu padrão (desktop) */
    nav ul { list-style: none; display: flex; gap: 20px; }
    nav ul li a { color: #fff; text-decoration: none; font-weight: bold; padding: 8px 16px; border: 2px solid transparent; border-radius: 20px; transition: 0.3s ease; }
    nav ul li a:hover { border-color: #b19a4f; background-color: #b19a4f; }
    
    .no-style-link { color: inherit; text-decoration: none; cursor: pointer; }
    
    /* Botão hamburger */
    .hamburger {
      display: none;
      flex-direction: column;
      cursor: pointer;
      width: 30px;
      height: 25px;
      justify-content: space-between;
      background: transparent;
      border: none;
      z-index: 20;
      position: absolute;
      right: 20px;
      top: 25px;
      padding: 0;
    }
    .hamburger div {
      height: 3px;
      background: #b19a4f;
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

    /* Mostrar hamburger e esconder menu no mobile */
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
        gap: 10px;
      }
      nav ul.show {
        transform: translateX(0);
      }
      .hamburger {
        display: flex;
      }
    }

    h2 { margin-top: 120px; text-align: center; font-family: 'Bebas Neue', cursive; font-size: 40px; color: #b19a4f; }
    .container { 
      width: 95%;
      margin: 30px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      table-layout: auto;
      font-size: 15px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 12px 8px;
      text-align: left;
    }
    th {
      background-color: #b19a4f;
      color: white;
    }
    td img {
      width: 80px;
      height: auto;
      border-radius: 6px;
      cursor: pointer;
    }
    .btn-remove {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      width: 32px;
      height: 32px;
      background-color: #e74c3c;
      color: white;
      font-size: 18px;
      font-weight: bold;
      border: none;
      border-radius: 50%;
      text-decoration: none;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .btn-remove:hover {
      background-color: #c0392b;
      transform: scale(1.1);
    }
    /* Modal */
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

<h2>Avaliações Físicas</h2>
<br>
<div style="text-align:center; margin-bottom: 20px;">
  <form method="get" action="ver_avaliacoes_admin.php">
    <input type="text" name="pesquisa" placeholder="Pesquisar por cliente..." value="<?= htmlspecialchars($termo_pesquisa) ?>" style="padding: 8px; font-size: 16px; border-radius: 6px; border: 1px solid #ccc; width: 250px;">
    <button type="submit" style="padding: 8px 16px; font-size: 16px; background-color: #b19a4f; color: white; border: none; border-radius: 6px; cursor: pointer;">Pesquisar</button>
  </form>
</div>

<div class="container">
  <table>
    <thead>
      <tr>
        <th>Cliente</th>
        <th>Data</th>
        <th>Peso</th>
        <th>Cintura</th>
        <th>Quadril</th>
        <th>Braço</th>
        <th>Coxa</th>
        <th>Foto</th>
        <th>Remover</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($linha = mysqli_fetch_assoc($res)): ?>
        <tr>
          <td><?= htmlspecialchars($linha['login']) ?></td>
          <td><?= htmlspecialchars($linha['data']) ?></td>
          <td><?= htmlspecialchars($linha['peso']) ?> kg</td>
          <td><?= htmlspecialchars($linha['cintura']) ?> cm</td>
          <td><?= htmlspecialchars($linha['quadril']) ?> cm</td>
          <td><?= htmlspecialchars($linha['braco']) ?> cm</td>
          <td><?= htmlspecialchars($linha['coxa']) ?> cm</td>
          <td>
            <?php
              $fotos = explode(',', $linha['foto']);

              if (!empty($linha['foto'])) {
                  foreach ($fotos as $foto) {
                      $foto = trim($foto);
                      $caminhoFisico = __DIR__ . '/fotos_avaliacoes/' . $foto;
                      $caminhoWeb = 'fotos_avaliacoes/' . rawurlencode($foto);

                      if (file_exists($caminhoFisico)) {
                          echo "<img src=\"$caminhoWeb\" alt=\"Foto do cliente\" onclick=\"abrirModal('$caminhoWeb')\" style=\"margin: 5px;\">";
                      }
                  }
              } else {
                  echo "Sem foto";
              }
            ?>
          </td>
          <td>
            <a href="remover_avaliacao.php?id=<?= urlencode($linha['id_avaliacao']) ?>"
               class="btn-remove"
               onclick="return confirm('Tem certeza que deseja remover esta avaliação?')">
               ✖
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Modal -->
<div id="imagemModal" class="modal">
  <img class="modal-content" id="imgGrande" alt="Imagem em tamanho grande">
</div>

<!-- Popup Modal -->
<div id="popup" style="display:none;">
  <div>
    <p id="popupText"></p>
    <button onclick="fecharPopup()">Fechar</button>
  </div>
</div>

<script>
  // Função para abrir modal da imagem
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

  // Hamburger menu toggle
  const hamburger = document.getElementById('hamburger');
  const menu = document.getElementById('menu-principal');

  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    menu.classList.toggle('show');
  });

  // Popup functions
  function fecharPopup() {
    document.getElementById("popup").style.display = "none";
  }

  function abrirPopup(texto) {
    document.getElementById("popupText").textContent = texto;
    document.getElementById("popup").style.display = "flex";
  }
</script>

</body>
</html>