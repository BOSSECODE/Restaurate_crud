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

    $nome = trim($_POST["nome"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $preco = trim($_POST["preco"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");
    $id_usuario = (int)($_POST["id_usuario"] ?? 0);

  
    if (
        $nome === "" ||
        $descricao === "" ||
        $preco === "" ||
        $categoria === "" ||
        $id_usuario <= 0
    ) {

        $erro = "Preencha todos os campos obrigatórios.";

    } elseif (!is_numeric($preco) || $preco < 0) {

        $erro = "Digite um preço válido.";

    } else {

        $preco = (float)$preco;

     
        $stmt = $conn->prepare(
            "INSERT INTO pratos
            (nome, descricao, preco, categoria, id_usuario)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssdsi",
            $nome,
            $descricao,
            $preco,
            $categoria,
            $id_usuario
        );

        if ($stmt->execute()) {

            $sucesso = "Prato cadastrado com sucesso!";

           
            $nome = "";
            $descricao = "";
            $preco = "";
            $categoria = "";

        } else {

            $erro = "Erro ao cadastrar o prato.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Cadastrar Prato</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>

<body>

<main class="container pequeno">

    <div class="card">

        <h2>Cadastrar Prato</h2>

        <p>
            Preencha os dados do prato.
        </p>

        <?php if ($erro !== ""): ?>

            <div class="mensagem erro">

                <?= htmlspecialchars($erro) ?>

            </div>

        <?php endif; ?>


        <?php if ($sucesso !== ""): ?>

            <div class="mensagem sucesso">

                <?= htmlspecialchars($sucesso) ?>

            </div>

        <?php endif; ?>


        <?php if ($usuarios->num_rows === 0): ?>

            <div class="mensagem erro">

                É necessário cadastrar um usuário antes
                de cadastrar um prato.

            </div>

        <?php else: ?>

            <form method="POST">

                <label for="nome">
                    Nome do prato *
                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    required
                    value="<?= htmlspecialchars($nome ?? "") ?>"
                >


                <label for="descricao">
                    Descrição *
                </label>

                <textarea
                    id="descricao"
                    name="descricao"
                    rows="4"
                    required
                ><?= htmlspecialchars($descricao ?? "") ?></textarea>


                <label for="preco">
                    Preço *
                </label>

                <input
                    type="number"
                    id="preco"
                    name="preco"
                    step="0.01"
                    min="0"
                    required
                    value="<?= htmlspecialchars($preco ?? "") ?>"
                >


                <label for="categoria">
                    Categoria *
                </label>

                <input
                    type="text"
                    id="categoria"
                    name="categoria"
                    required
                    value="<?= htmlspecialchars($categoria ?? "") ?>"
                >


                <label for="id_usuario">
                    Usuário responsável *
                </label>

                <select
                    id="id_usuario"
                    name="id_usuario"
                    required
                >

                    <option value="">
                        Selecione o usuário
                    </option>

                    <?php while ($usuario = $usuarios->fetch_assoc()): ?>

                        <option
                            value="<?= $usuario["id_usuario"] ?>"
                        >

                            <?= htmlspecialchars($usuario["nome"]) ?>

                        </option>

                    <?php endwhile; ?>

                </select>


                <button
                    class="botao"
                    type="submit"
                >
                    Cadastrar Prato
                </button>

            </form>

        <?php endif; ?>

    </div>

</main>

</body>

</html>
