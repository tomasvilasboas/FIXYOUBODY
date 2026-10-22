<?php
$cod_ut = $_GET['cod_ut'] ?? null;

if (!$cod_ut || !is_numeric($cod_ut)) {
    die('Atenção! Campos em falta. Volte atrás e tente de novo.');
}

// Conexão à base de dados
$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);
if (!$ligax) {
    die('<p>Erro: Falha na ligação.</p>');
}

// Valida se o utilizador existe
$check = mysqli_query($ligax, "SELECT * FROM utilizadores WHERE cod_ut = $cod_ut");

if (mysqli_num_rows($check) == 0) {
    die('<p>Utilizador não encontrado.</p>');
}

// Executa remoção
$remove = "DELETE FROM utilizadores WHERE cod_ut = $cod_ut";
$result = mysqli_query($ligax, $remove);

if ($result) {
    // Sucesso: redireciona
    header("Location: remover_utilizadores_listados.php?status=sucesso");
    exit;
} else {
    // Falha: redireciona com erro
    header("Location: remover_utilizadores_listados.php?status=erro");
    exit;
}
?>
