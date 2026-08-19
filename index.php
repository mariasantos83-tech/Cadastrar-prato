<?php
require_once 'conexao.php';

$mensagem = "";
$status = "";

if (isset($_POST['cadastrar_usuario'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    if (empty($nome) || empty($email)) {
        $mensagem = "Todos os campos de usuário são obrigatórios!";
        $status = "error";
    } else {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
        $stmt->execute([$nome, $email]);
        $mensagem = "Usuário cadastrado com sucesso!";
        $status = "success";
    }
}
if (isset($_POST['cadastrar_prato'])) {
    $nome_prato = trim($_POST['nome_prato']);
    $descricao = trim($_POST['descricao']);
    $preco = trim($_POST['preco']);
    $categoria = trim($_POST['categoria']);
    $id_usuario = trim($_POST['id_usuario']);

    if (empty($nome_prato) || empty($descricao) || empty($preco) || empty($categoria) || empty($id_usuario)) {
        $mensagem = "Todos os campos do prato são obrigatórios!";
        $status = "error";
    } else {
        $stmt = $pdo->prepare("INSERT INTO pratos (nome, descricao, preco, categoria, id_usuario) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome_prato, $descricao, $preco, $categoria, $id_usuario]);
        $mensagem = "Prato cadastrado com sucesso!";
        $status = "success";
    }
}

$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Controle do Restaurante</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>🍽️ Sistema de Gestão do Restaurante</h1>
        
        <nav>
            <a href="index.php">Início / Cadastros</a>
            <a href="listar.php">Visualizar Todos os Pratos</a>
        </nav>

        <?php if (!empty($mensagem)): ?>
            <div class="alert <?= $status == 'success' ? 'success' : '' ?>"><?= $mensagem ?></div>
        <?php endif; ?>

        <div class="grid">
            <!-- Formulário RF1 -->
            <div class="card">
                <h2>👤 Cadastrar Usuário (Colaborador)</h2>
                <form action="index.php" method="POST">
                    <label>Nome:</label>
                    <input type="text" name="nome">
                    
                    <label>E-mail:</label>
                    <input type="email" name="email">
                    
                    <button type="submit" name="cadastrar_usuario">Cadastrar Usuário</button>
                </form>
            </div>

            <!-- Formulário RF2 -->
            <div class="card">
                <h2>🍲 Cadastrar Novo Prato</h2>
                <form action="index.php" method="POST">
                    <label>Nome do Prato:</label>
                    <input type="text" name="nome_prato">
                    
                    <label>Descrição:</label>
                    <textarea name="descricao" rows="3"></textarea>
                    
                    <label>Preço (R$):</label>
                    <input type="number" step="0.01" name="preco">
                    
                    <label>Categoria:</label>
                    <select name="categoria">
                        <option value="">Selecione...</option>
                        <option value="Entrada">Entrada</option>
                        <option value="Prato Principal">Prato Principal</option>
                        <option value="Sobremesa">Sobremesa</option>
                        <option value="Bebida">Bebida</option>
                    </select>
                    
                    <label>Colaborador Responsável:</label>
                    <select name="id_usuario">
                        <option value="">Selecione quem está cadastrando...</option>
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= $u['nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <button type="submit" name="cadastrar_prato">Cadastrar Prato</button>
                </form>
            </div>
        </div>

        <!-- Formulário RF6 -->
        <div class="card">
            <h2>🔍 Filtrar Pratos por Colaborador</h2>
            <form action="listar.php" method="GET">
                <label>Selecione o Usuário:</label>
                <select name="usuario_id" required>
                    <option value="">Escolha um colaborador...</option>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= $u['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Visualizar Pratos Relacionados</button>
            </form>
        </div>
    </div>
</body>
</html>
