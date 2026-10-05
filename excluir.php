<?php
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php?msg=erro');
    exit;
}

try {
    $stmt = $pdo->prepare('DELETE FROM brinquedos WHERE id = ?');
    $stmt->execute([$id]);
    $msg = $stmt->rowCount() > 0 ? 'excluido' : 'naoencontrado';
} catch (PDOException $e) {
    $msg = 'erro';
}

header('Location: index.php?msg=' . $msg);
exit;


?>