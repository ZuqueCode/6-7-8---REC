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
    
<h1>🧸 Gestão de Brinquedos</h1>

  <?php if ($msg): ?><p class="msg"><?= h($msg) ?></p><?php endif; ?>
  <?php if ($erro): ?><p class="erro"><?= h($erro) ?></p><?php endif; ?>

  <p><a class="btn" href="form.php">+ Novo brinquedo</a></p>

  <table>
    <tr>
      <th>ID</th><th>Nome</th><th>Categoria</th><th>Faixa etária</th>
      <th>Preço</th><th>Estoque</th><th>Ações</th>
    </tr>
    <?php foreach ($brinquedos as $b): ?>
      <tr>
        <td><?= (int) $b['id'] ?></td>
        <td><?= h($b['nome']) ?></td>
        <td><?= h($b['categoria']) ?></td>
        <td><?= h($b['faixa_etaria']) ?></td>
        <td>R$ <?= number_format($b['preco'], 2, ',', '.') ?></td>
        <td><?= (int) $b['estoque'] ?></td>
        <td>
          <a class="btn" href="form.php?id=<?= (int) $b['id'] ?>">Editar</a>
          <form action="excluir.php" method="post" style="display:inline"
                onsubmit="return confirm('Excluir este brinquedo?');">
            <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
            <button class="btn perigo">Excluir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$brinquedos && !$erro): ?>
      <tr><td colspan="7">Nenhum brinquedo cadastrado.</td></tr>
    <?php endif; ?>
  </table>
</body>
</html>