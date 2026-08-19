<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro do Prato</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <h1>Cadastro do Prato</h1>

    <form action="cadastrar_prato.php" method="POST">

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required></textarea>

        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" min="0" required>

        <label for="categoria">Categoria:</label>
        <input type="text" id="categoria" name="categoria" required>

        <button type="submit">Cadastrar prato</button>

    </form>
    <br>
    <a href="index.php">Voltar</a>

</body>
</html>