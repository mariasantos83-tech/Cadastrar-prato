<?php
include 'conexao.php';

if (isset($_GET['usuario_id'])) {
    $usuario_id = $_GET['usuario_id'];

    $stmt_user = $conn->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmt_user->bind_param("i", $usuario_id);
    $stmt_user->execute();
    $res_user = $stmt_user->get_result();
    $usuario = $res_user->fetch_assoc();
    $stmt_user->close();

    $stmt_pratos = $conn->prepare("SELECT * FROM pratos WHERE usuario_id = ?");
    $stmt_pratos->bind_param("i", $usuario_id);
    $stmt_pratos->execute();
    $pratos = $stmt_pratos->get_result();
    $stmt_pratos->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pratos por Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Pratos cadastrados por: <?php echo $usuario['nome']; ?></h1>
        <a href="index.php" class="btn-back">Voltar para o início</a>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($pratos->num_rows > 0): ?>
                    <?php while($prato = $pratos->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $prato['nome']; ?></strong></td>
                            <td><?php echo $prato['descricao']; ?></td>
                            <td>R$ <?php echo number_format($prato['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo $prato['categoria']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">Este colaborador não cadastrou nenhum prato.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
