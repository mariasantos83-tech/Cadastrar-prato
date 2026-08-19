<?php
require_once 'conexao.php';

$usuario_filtrado = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;

if ($usuario_filtrado) {
    $stmt = $pdo->prepare("SELECT pratos.*, usuarios.nome AS responsavel 
                           FROM pratos 
                           JOIN usuarios ON pratos.id_usuario = usuarios.id 
                           WHERE pratos.id_usuario = ? 
                           ORDER BY pratos.nome");
    $stmt->execute([$usuario_filtrado]);
    $pratos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmtUser = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmtUser->execute([$usuario_filtrado]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    $titulo_pagina = "Pratos cadastrados por: " . ($user ? $user['nome'] : 'Usuário Desconhecido');
} else {
    $pratos = $pdo->query("SELECT pratos.*, usuarios.nome AS responsavel 
                           FROM pratos 
                           JOIN usuarios ON pratos.id_usuario = usuarios.id 
                           ORDER BY pratos.id DESC")->fetchAll(PDO::FETCH_ASSOC);
    $titulo_pagina = "Todos os Pratos Cadastrados";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Listagem de Pratos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📋 Listagem do Cardápio</h1>
        <nav>
            <a href="index.php">Início / Cadastros</a>
            <a href="listar.php">Visualizar Todos os Pratos</a>
        </nav>

        <h2><?= htmlspecialchars($titulo_pagina) ?></h2>

        <?php if (count($pratos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Prato</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Categoria</th>
                        <th>Responsável pelo Registro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pratos as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nome']) ?></td>
                            <td><?= htmlspecialchars($p['descricao']) ?></td>
                            <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($p['categoria']) ?></td>
                            <td><strong><?= htmlspecialchars($p['responsavel']) ?></strong></td>
                            <td>
                                <!-- Links para RF4 e RF5 -->
                                <a href="editar.php?id=<?= $p['id'] ?>" class="btn-action btn-edit">Editar</a>
                                <a href="excluir.php?id=<?= $p['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Deseja realmente excluir este prato?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="margin-top: 20px;">Nenhum prato encontrado para os critérios selecionados.</p>
        <?php endif; ?>
    </div>
</body>
</html>
