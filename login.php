<?php
session_start();

// Obtem os valores digitados
$login = $_POST["login"];
$senha = $_POST["senha"];

if (!$login || !$senha) {
    $mensagem = "Atenção! Campos em falta. Volte atrás e tente de novo.";
    $tipo = "erro";
} else {

    // Ligação à base de dados local
    $cn = mysqli_connect(
        "localhost",
        "root",
        "root",
        "4640454_fixyoubody"
    );

    if (!$cn) {
        die("Erro na ligação: " . mysqli_connect_error());
    }

    $resultado = mysqli_query(
        $cn,
        "SELECT * FROM utilizadores WHERE login = '$login'"
    );

    $linhas = mysqli_num_rows($resultado);

    if ($linhas == 0) {
        $mensagem = "Utilizador não encontrado.";
        $tipo = "erro";
    } else {

        $registo = mysqli_fetch_assoc($resultado);

        if ($senha != $registo['senha']) {
            $mensagem = "Senha inválida.";
            $tipo = "erro";
        } else {

            $_SESSION["cod_ut"] = $registo["cod_ut"];
            $_SESSION["login"] = $login;
            $_SESSION["senha"] = $senha;
            $_SESSION["sit_ut"] = $registo["sit_ut"];

            if ($registo["sit_ut"] == "ADM") {
                header("Location: pagina_principal_adm.php");
            } else {
                header("Location: pagina_principal.php");
            }

            exit;
        }
    }

    mysqli_close($cn);
}
?>