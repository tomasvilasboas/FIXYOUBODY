<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if (!isset($_SESSION["login"])) {
  echo json_encode([]);
  exit;
}

$login = $_SESSION["login"];

$ligax = mysqli_connect(
    'localhost',    
    'root',        
    'root',              
    '4640454_fixyoubody' 
);if (!$ligax) {
  echo json_encode([]);
  exit;
}

// Modifique a query para incluir o nome do usuário que fez a marcação
$query = "SELECT m.data, m.hora, u.login AS nome_cliente 
          FROM marcacoes m
          JOIN utilizadores u ON m.login = u.login
          WHERE m.login = '$login'";
          
$result = mysqli_query($ligax, $query);

$eventos = [];

while ($row = mysqli_fetch_assoc($result)) {
  $horaFormatada = substr($row['hora'], 0, 5) . 'H';
  $eventos[] = [
    'title' => 'Marcação ' . $horaFormatada,
    'start' => $row['data'] . 'T' . $row['hora'],
    'color' => '#b19a4f',
    'extendedProps' => [
      'cliente' => $row['nome_cliente'],
      'hora_completa' => $horaFormatada
    ]
  ];
}

echo json_encode($eventos);
?>