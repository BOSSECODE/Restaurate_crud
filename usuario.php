<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <h1>Cadastro de Usuário</h1>

    <form action="cadastrar_usuario.php" method="POST">

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>

        <button type="submit">Cadastrar usuário</button>

    </form>
    <br>
    <a href="index.php">Voltar</a>

</body>
</html>