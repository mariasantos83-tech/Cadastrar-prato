<?php
include 'conexao.php';

$mensagem = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT * FROM pratos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $prato = $resultado->fetch_assoc();
    $stmt->close();
}

-- SALVAR ALTERAÇÕES (RF4)
if (isset($_POST['atualizar_prato'])) {
    $id = $_POST['id'];
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = trim($_POST['preco']);
    $categoria = trim($_POST['categoria']);

    if (empty($nome) || empty($descricao) || empty($preco) || empty($categoria)) {
        $mensagem = "Erro: Todos os campos são obrigatórios!";
    } else {
        $stmt = $conn->prepare("UPDATE pratos SET nome = ?, descricao = ?, preco = ?, categoria = ? WHERE id = ?");
        $stmt->bind_param("ssdsi", $nome, $descricao, $preco, $categoria, $id);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Prato</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container small-container">
        <h1>Editar Prato</h1>
        <?php if (!empty($mensagem)) echo "<p class='alert'>$mensagem</p>"; ?>
        
        <form action="editar.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $prato['id']; ?>">
            
            <label>Nome do Prato</label>
            <input type="text" name="nome" value="<?php echo $prato['nome']; ?>" required>
            
            <label>Descrição</label>
            <textarea name="descricao" required><?php echo $prato['descricao']; ?></textarea>
            
            <label>Preço</label>
            <input type="number" name="preco" step="0.01" value="<?php echo $prato['preco']; ?>" required>
            
            <label>Categoria</label>
            <input type="text" name="categoria" value="<?php echo $prato['categoria']; ?>" required>
            
            <button type="submit" name="atualizar_prato">Salvar Alterações</button>
            <a href="index.php" class="btn-back">Voltar</a>
        </form>
    </div>
</body>
</html>
