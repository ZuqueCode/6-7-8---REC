<?php
require 'conexao.php';

$mensagens = [
    'criado'        => 'Brinquedo cadastrado com sucesso!',
    'editado'       => 'Brinquedo atualizado com sucesso!',
    'excluido'      => 'Brinquedo excluído com sucesso!',
    'naoencontrado' => 'Brinquedo não encontrado.',
    'erro'          => 'Ocorreu um erro. Tente novamente.',
];
$msg = $mensagens[$_GET['msg'] ?? ''] ?? '';

$brinquedos = [];
$erro = '';
try {
   
    $stmt = $pdo->prepare('SELECT * FROM brinquedos ORDER BY nome');
    $stmt->execute();
    $brinquedos = $stmt->fetchAll();
} catch (PDOException $e) {
    $erro = 'Não foi possível listar os brinquedos.';
}





?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestão de Brinquedos</title>
</head>
<body>
    
</body>
</html>