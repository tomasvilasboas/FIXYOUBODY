<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if (!isset($_SESSION["login"])) {
    echo json_encode([]);
    exit;
}

$ligax = mysqli_connect(
    'localhost',
    'root',
    'root',
    '4640454_fixyoubody'
);

if (!$ligax) {    echo json_encode([]);
    exit;
}

$query = "SELECT data, hora, login FROM marcacoes";
$result = mysqli_query($ligax, $query);

if ($result) {
    $eventos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $horaFormatada = substr($row['hora'], 0, 5) . 'H';
        $dataHora = $row['data'] . 'T' . $row['hora']; // Ex: 2025-05-20T09:00:00

        $eventos[] = [
            'title' => 'Marcação ',
            'start' => $dataHora,
            'display' => 'block', // Força a mostrar como evento de bloco no modo "month"
            'color' => '#b19a4f',
            'extendedProps' => [
                'login' => $row['login']
            ]
        ];
    }
    echo json_encode($eventos);
} else {
    echo json_encode(["message" => "Erro na consulta ao banco de dados."]);
}
?>
