<?php
include "valida_session.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login = $_POST["login_utilizador"];
    $data = $_POST["data_marcacao"];
    $hora = $_POST["hora_marcacao"];

    $ligacao = mysqli_connect(
        'localhost',
        'root',
        'root',
        '4640454_fixyoubody'
    );

    if (!$ligacao) {
        die("Erro na ligação.");
    }

    $stmt = mysqli_prepare(
        $ligacao,
        "INSERT INTO marcacoes (login, data, hora) VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "sss", $login, $data, $hora);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: marcacoes_calendario_adm.php");
        exit;
    } else {
        echo "Erro ao inserir marcação.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($ligacao);
}
?>