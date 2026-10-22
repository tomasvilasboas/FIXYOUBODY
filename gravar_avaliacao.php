<?php
//gravar_avaliacao.php
$ligax = mysqli_connect(
    'localhost',
    'root',
    'root',
    '4640454_fixyoubody'
);

if (!$ligax) {    die("Erro na ligação à base de dados.");
}

$cod_ut = $_POST['cod_ut'];
$data = $_POST['data'];
$peso = $_POST['peso'];
$cintura = $_POST['cintura'];
$quadril = $_POST['quadril'];
$braco = $_POST['braco'];
$coxa = $_POST['coxa'];

$nomes_fotos = [];

if (!empty($_FILES['fotos']['name'][0])) {
    foreach ($_FILES['fotos']['tmp_name'] as $index => $tmpName) {
        $nomeOriginal = $_FILES['fotos']['name'][$index];
        $nomeFinal = uniqid() . "_" . basename($nomeOriginal);
        if (move_uploaded_file($tmpName, "fotos_avaliacoes/" . $nomeFinal)) {
            $nomes_fotos[] = $nomeFinal;
        }
    }
}

$fotos_string = implode(',', $nomes_fotos);


$sql = "INSERT INTO avaliacoes (cod_ut, data, peso, cintura, quadril, braco, coxa, foto)
VALUES ('$cod_ut', '$data', '$peso', '$cintura', '$quadril', '$braco', '$coxa', '$fotos_string')";

if (mysqli_query($ligax, $sql)) {
    header("Location: avaliacoes_admin.php");
    exit;
} else {
    header("Location: avaliacoes_admin.php?erro=1");
    exit;
}
?>
