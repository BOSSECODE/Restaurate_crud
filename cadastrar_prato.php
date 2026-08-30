<?php
link rel="stylesheet" href="css /style.css">

require_once "conexao.php";

$erro = "";
$sucesso = "";

$usuarios = $conn->query(
    "SELECT id_usuario, nome
     FROM usuarios
     ORDER BY nome"
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"]);
    $descricao = trim($_POST["descricao"]);
    $preco = $_POST["preco"];
    $categoria = trim($_POST["categoria"]);
    $usuario_id = $_POST["usuario_id"];

    if (
        empty($nome) ||
        empty($descricao) ||
        empty($preco) ||
        empty($categoria) ||
        empty($usuario_id)
    ) {

        die("Erro: preencha todos os campos obrigatórios.");

    }

    if (!is_numeric($preco) || $preco < 0) {

        die("Erro: informe um preço válido.");

    }

    $sql = "INSERT INTO pratos 
            (nome, descricao, preco, categoria, usuario_id)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        die("Erro ao preparar a consulta: " . $conn->error);

    }

    $stmt->bind_param(
        "ssdsi",
        $nome,
        $descricao,
        $preco,
        $categoria,
        $usuario_id
    );

    if ($stmt->execute()) {

        echo "<h2>Prato cadastrado com sucesso!</h2>";

        echo "<p><strong>Prato:</strong> "
            . htmlspecialchars($nome)
            . "</p>";

        echo "<p><strong>Preço:</strong> R$ "
            . number_format($preco, 2, ",", ".")
            . "</p>";

        echo "<p><strong>Categoria:</strong> "
            . htmlspecialchars($categoria)
            . "</p>";

        echo "<br>";

        echo "<a href='cadastrar_prato.php'>Cadastrar outro prato</a>";

        echo "<br><br>";

        echo "<a href='index.php'>Voltar para o início</a>";

    } else {

        echo "<h2>Erro ao cadastrar prato.</h2>";

        echo "<p>"
            . htmlspecialchars($stmt->error)
            . "</p>";

    }

    $stmt->close();

    $conn->close();

    exit;
}

$sql_usuarios = "SELECT id, nome FROM usuarios ORDER BY nome";

$resultado_usuarios = $conn->query($sql_usuarios);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Prato</title>

</head>

<body>

    <h1>Cadastrar Prato</h1>

    <form action="cadastrar_prato.php" method="POST">

        <label for="nome"> Nome do prato:</label>

        <br>

        <input type="text" id="nome" name="nome" required >

        <br><br>

        <label for="descricao"> Descrição:</label>

        <br>

        <textarea id="descricao" name="descricao" required></textarea>

        <br><br>


        <label for="preco"> Preço: </label>

        <br>

        <input type="number" id="preco" name="preco" step="0.01" min="0" required>

        <br><br>

        <label for="categoria"> Categoria:</label>

        <br>

        <input type="text" id="categoria" name="categoria" required>

        <br><br>


        <label for="usuario_id"> Usuário responsável:</label>

        <br>

        <select id="usuario_id" name="usuario_id" required >

            <option value=""> Selecione um usuário </option>

            <?php while ($usuario = $resultado_usuarios->fetch_assoc()): ?>

                <option value="<?= $usuario['id'] ?>">

                    <?= htmlspecialchars($usuario['nome']) ?>

                </option>

            <?php endwhile; ?>

        </select>

        <br><br>

        <button type="submit">Cadastrar Prato</button>

    </form>

    <br>

    <a href="index.php"> Voltar para o início</a>

</body>

</html>

<?php

$conn->close();

?>