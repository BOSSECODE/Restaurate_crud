<?php
link rel="stylesheet" href="css/style.css">
require_once "conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

$erro = "";


$stmt = $conn->prepare(
    "SELECT id_prato, nome, descricao, preco, categoria, id_usuario
     FROM pratos
     WHERE id_prato = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$prato = $resultado->fetch_assoc();

$stmt->close();

if (!$prato) {
    die("Prato não encontrado.");
}

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

    } elseif (!is_numeric($preco) || (float)$preco < 0) {
        $erro = "Digite um preço válido.";

    } else {

        $preco = (float)$preco;

        $stmt = $conn->prepare(
            "UPDATE pratos
             SET nome = ?,
                 descricao = ?,
                 preco = ?,
                 categoria = ?,
                 id_usuario = ?
             WHERE id_prato = ?"
        );

        $stmt->bind_param(
            "ssd sii",
            $nome,
            $descricao,
            $preco,
            $categoria,
            $id_usuario,
            $id
        );

        $stmt->close();

        $stmt = $conn->prepare(
            "UPDATE pratos
             SET nome = ?,
                 descricao = ?,
                 preco = ?,
                 categoria = ?,
                 id_usuario = ?
             WHERE id_prato = ?"
        );

        $stmt->bind_param(
            "ssdsii",
            $nome,
            $descricao,
            $preco,
            $categoria,
            $id_usuario,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();

            header("Location: index.php?sucesso=1");
            exit;
        }

        $erro = "Erro ao atualizar o prato.";

        $stmt->close();
    }

    $prato["nome"] = $nome;
    $prato["descricao"] = $descricao;
    $prato["preco"] = $preco;
    $prato["categoria"] = $categoria;
    $prato["id_usuario"] = $id_usuario;
}

$usuarios = $conn->query(
    "SELECT id_usuario, nome
     FROM usuarios
     ORDER BY nome"
);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Prato</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<header>

    <h1>Sistema de Pratos</h1>

    <nav>

        <a href="index.php">Início</a>

        <a href="cadastrar_usuario.php">
            Cadastrar Usuário
        </a>

        <a href="cadastrar_prato.php">
            Cadastrar Prato
        </a>

        <a href="pratos_usuario.php">
            Pratos por Usuário
        </a>

    </nav>

</header>

<main class="container pequeno">

    <div class="card">

        <h2>Editar prato</h2>

        <p>
            Altere as informações do prato abaixo.
        </p>

        <?php if ($erro !== ""): ?>

            <div class="mensagem erro">

                <?= htmlspecialchars($erro) ?>

            </div>

        <?php endif; ?>

        <form method="POST">

            <label for="nome">
                Nome do prato *
            </label>

            <input
                type="text"
                id="nome"
                name="nome"
                required
                value="<?= htmlspecialchars($prato["nome"]) ?>"
            >

            <label for="descricao">
                Descrição *
            </label>

            <textarea
                id="descricao"
                name="descricao"
                rows="4"
                required
            ><?= htmlspecialchars($prato["descricao"]) ?></textarea>

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
                value="<?= htmlspecialchars($prato["preco"]) ?>"
            >

            <label for="categoria">
                Categoria *
            </label>

            <input
                type="text"
                id="categoria"
                name="categoria"
                required
                value="<?= htmlspecialchars($prato["categoria"]) ?>"
            >

            <label for="id_usuario">
                Usuário responsável *
            </label>

            <select
                id="id_usuario"
                name="id_usuario"
                required
            >

                <?php while ($usuario = $usuarios->fetch_assoc()): ?>

                    <option
                        value="<?= $usuario["id_usuario"] ?>"
                        <?= $prato["id_usuario"] == $usuario["id_usuario"] ? "selected" : "" ?>
                    >

                        <?= htmlspecialchars($usuario["nome"]) ?>

                    </option>

                <?php endwhile; ?>

            </select>

            <button
                class="botao"
                type="submit"
            >
                Salvar alterações
            </button>

            <a
                class="botao secundario"
                href="index.php"
            >
                Cancelar
            </a>

        </form>

    </div>

</main>

</body>

</html>