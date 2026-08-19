<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
</head>

<body>

    <h1>Cadastrar Usuário</h1>

    <form action="cadastrar_usuario.php" method="POST">

        <label for="nome">Nome:</label>
        <br>
        <input type="text" id="nome" name="nome" required>
        <br><br>

        <label for="email">E-mail:</label>
        <br>

        <input type="email" id="email" name="email" required>

        <br><br>

        <button type="submit">
            Cadastrar Usuário
        </button>

    </form>

    <br>

    <a href = "index.php">
    </a>

</body>

</html>