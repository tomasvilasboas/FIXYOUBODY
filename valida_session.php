<?php
session_start();

if (isset($_SESSION["login"]) && isset($_SESSION["senha"])) {
    $login = $_SESSION["login"];
    $senha = $_SESSION["senha"];
} else {
    echo "Não efetuou o login.";
    exit();
}

if (!empty($login) && !empty($senha)) {
    // conecta à base de dados
    $ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);    if (!$ligax) {
        echo "Erro ao conectar à base de dados.";
        exit();
    }

    // escapa o valor para evitar SQL Injection
    $login_esc = mysqli_real_escape_string($ligax, $login);

    $resultado = mysqli_query($ligax, "SELECT * FROM utilizadores WHERE login = '$login_esc'");
    if ($resultado && mysqli_num_rows($resultado) == 1) {
        $registo = mysqli_fetch_assoc($resultado);
        if ($senha != $registo['senha']) {
            unset($_SESSION["login"]);
            unset($_SESSION["senha"]);
            echo "Não efetuou o login.";
            exit();
        }
        // login válido - pode avançar
    } else {
        unset($_SESSION["login"]);
        unset($_SESSION["senha"]);
        echo "Não efetuou o login.";
        exit();
    }

    mysqli_close($ligax);
} else {
    echo "Não efetuou o login.";
    exit();
}
?>
