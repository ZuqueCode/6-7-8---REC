<?php

require 'conexao.php';

$faixas = ['0 a 2 anos', '3 a 5 anos', '6 a 8 anos', '9 a 12 anos', '13+ anos'];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$d = ['nome' => '', 'categoria' => '', 'faixa_etaria' => '', 'preco' => '', 'estoque' => ''];
$erros = [];

try {
   
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM brinquedos WHERE id = ?');
        $stmt->execute([$id]);
        $brinquedo = $stmt->fetch();
        if (!$brinquedo) {
            header('Location: index.php?msg=naoencontrado');
            exit;
        }
        $d = $brinquedo;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $d['nome']         = trim($_POST['nome'] ?? '');
        $d['categoria']    = trim($_POST['categoria'] ?? '');
        $d['faixa_etaria'] = $_POST['faixa_etaria'] ?? '';
        $d['preco']        = trim($_POST['preco'] ?? '');
        $d['estoque']      = trim($_POST['estoque'] ?? '');

       
        $tam = mb_strlen($d['nome']);
        if ($tam < 2 || $tam > 100) {
            $erros[] = 'O nome deve ter entre 2 e 100 caracteres.';
        }
        if ($d['categoria'] === '' || mb_strlen($d['categoria']) > 50) {
            $erros[] = 'Informe a categoria (até 50 caracteres).';
        }
        if (!in_array($d['faixa_etaria'], $faixas, true)) {
            $erros[] = 'Selecione uma faixa etária válida.';
        }
        $preco = str_replace(',', '.', $d['preco']);
        if (!is_numeric($preco) || $preco <= 0 || $preco > 99999999) {
            $erros[] = 'Informe um preço válido maior que zero (ex: 49,90).';
        }
        $estoque = filter_var($d['estoque'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
        if ($estoque === false) {
            $erros[] = 'O estoque deve ser um número inteiro maior ou igual a zero.';
        }

        
        if (!$erros) {
            if ($id) {
               
                $stmt = $pdo->prepare(
                    'UPDATE brinquedos SET nome = ?, categoria = ?, faixa_etaria = ?, preco = ?, estoque = ? WHERE id = ?'
                );
                $stmt->execute([$d['nome'], $d['categoria'], $d['faixa_etaria'], $preco, $estoque, $id]);
                header('Location: index.php?msg=editado');
            } else {
                
                $stmt = $pdo->prepare(
                    'INSERT INTO brinquedos (nome, categoria, faixa_etaria, preco, estoque) VALUES (?, ?, ?, ?, ?)'
                );
                $stmt->execute([$d['nome'], $d['categoria'], $d['faixa_etaria'], $preco, $estoque]);
                header('Location: index.php?msg=criado');
            }
            exit;
        }
    }
} catch (PDOException $e) {
    $erros[] = 'Erro ao acessar o banco de dados. Tente novamente.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?= $id ? 'Editar' : 'Novo' ?> brinquedo</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1><?= $id ? 'Editar' : 'Novo' ?> brinquedo</h1>

  <?php foreach ($erros as $e): ?><p class="erro"><?= h($e) ?></p><?php endforeach; ?>

  <form method="post">
    <label>Nome</label>
    <input type="text" name="nome" maxlength="100" value="<?= h($d['nome']) ?>">

    <label>Categoria</label>
    <input type="text" name="categoria" maxlength="50" value="<?= h($d['categoria']) ?>">

    <label>Faixa etária</label>
    <select name="faixa_etaria">
      <option value="">Selecione...</option>
      <?php foreach ($faixas as $f): ?>
        <option value="<?= h($f) ?>" <?= $d['faixa_etaria'] === $f ? 'selected' : '' ?>><?= h($f) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Preço (R$)</label>
    <input type="text" name="preco" placeholder="0,00" value="<?= h($d['preco']) ?>">

    <label>Estoque</label>
    <input type="number" name="estoque" min="0" value="<?= h($d['estoque']) ?>">

    <button class="btn">Salvar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>