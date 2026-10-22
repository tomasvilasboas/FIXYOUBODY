<?php
include "valida_session.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$login = $_SESSION["login"];
$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);if (!$ligax) die("Erro na ligação à base de dados.");

// Verificar se o utilizador é administrador
$consulta = "SELECT sit_ut FROM utilizadores WHERE login='$login'";
$result = mysqli_query($ligax, $consulta);
$registo = mysqli_fetch_assoc($result);

if ($registo['sit_ut'] !== "ADM") {
    echo "Acesso não autorizado.";
    exit;
}

// Verificar se ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID inválido.";
    exit;
}

$id = intval($_GET['id']);

// Obter o nome das fotos antes de apagar
$query = "SELECT foto FROM avaliacoes WHERE id_avaliacao = $id";
$res = mysqli_query($ligax, $query);
if (!$res || mysqli_num_rows($res) == 0) {
    echo "Avaliação não encontrada.";
    exit;
}

$linha = mysqli_fetch_assoc($res);
$fotos = explode(',', $linha['foto']);

// Apagar ficheiros de imagem
foreach ($fotos as $foto) {
    $foto = trim($foto);
    if ($foto !== 'root') {
        $caminho = __DIR__ . '/fotos_avaliacoes/' . $foto;
        if (file_exists($caminho)) {
            unlink($caminho); // Apagar o ficheiro do servidor
        }
    }
}

// Apagar a avaliação
$delete = "DELETE FROM avaliacoes WHERE id_avaliacao = $id";
mysqli_query($ligax, $delete);

// Redirecionar de volta
header("Location: ver_avaliacoes_admin.php");
exit;
?>
