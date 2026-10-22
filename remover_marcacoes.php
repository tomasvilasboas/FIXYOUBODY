<?php
$id_marc = $_GET['id_marc'] ?? null;

if (!$id_marc || !is_numeric($id_marc)) {
    die("Parâmetro 'id_marc' inválido ou não fornecido.");
}

$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);
if (!$ligax) {
    die("Erro na conexão com o banco.");
}

// Verifica se a marcação existe
$check_stmt = mysqli_prepare($ligax, "SELECT 1 FROM marcacoes WHERE id_marc = ?");
mysqli_stmt_bind_param($check_stmt, "i", $id_marc);
mysqli_stmt_execute($check_stmt);
$result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($check_stmt);
    mysqli_close($ligax);
    die("Nenhuma marcação encontrada com o ID informado.");
}

mysqli_stmt_close($check_stmt);

// Deleta a marcação
$del_stmt = mysqli_prepare($ligax, "DELETE FROM marcacoes WHERE id_marc = ?");
mysqli_stmt_bind_param($del_stmt, "i", $id_marc);

if (mysqli_stmt_execute($del_stmt)) {
    mysqli_stmt_close($del_stmt);
    mysqli_close($ligax);

    // Redireciona para a listagem após sucesso
    header("Location: remover_marcacoes_listadas.php?status=sucesso");
    exit;
} else {
    mysqli_stmt_close($del_stmt);
    mysqli_close($ligax);
    die("Erro ao executar remoção.");
}
?>
