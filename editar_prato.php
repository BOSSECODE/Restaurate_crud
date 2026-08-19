<?php

require_once __DIR__ . "/config/conexao.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    die("ID do prato inválido.");

}

$id = (int) $_GET["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

    $sql = "UPDATE pratos
            SET nome = ?,
                descricao = ?,
                preco = ?,
                categoria = ?,
                usuario_id = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param(
        "ssdsii",
        $nome,
        $descricao,
        $preco,
        $categoria,
        $usuario_id,
        $id
    );

    if ($stmt->execute()) {
        header("Location: pratos.php");
        exit;
    } else {

        echo "Erro ao atualizar o prato: "
            . htmlspecialchars($stmt->error);

    }

    $stmt->close();

}

$sql = "SELECT id, nome, descricao, preco, categoria, usuario_id FROM pratos WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    die("Prato não encontrado.");
}

$prato = $resultado->fetch_assoc();
$stmt->close();

$sql_usuarios = "SELECT id, nome FROM usuarios ORDER BY nome";

$resultado_usuarios = $conn->query($sql_usuarios);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Prato</title>

</head>

<body>

    <h1>Editar Prato</h1>

    <form action="editar_prato.php?id=<?= $prato["id"] ?>" method="POST" >

        <label for="nome"> Nome do prato: </label>
        <br>

        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($prato["nome"]) ?>" required >
        <br><br>

        <label for="descricao"> Descrição: </label>
        <br>

        <textarea id="descricao" name="descricao" required><?= htmlspecialchars($prato["descricao"]) ?></textarea>
        <br><br>

        <label for="preco"> Preço: </label>
        <br>

        <input type="number" id="preco" name="preco" step="0.01" min="0" value="<?= $prato["preco"] ?>" required>
        <br><br>

        <label for="categoria"> Categoria:</label>
        <br>

        <input type="text" id="categoria" name="categoria" value="<?= htmlspecialchars($prato["categoria"]) ?>" required>
        <br><br>

        <label for="usuario_id"> Usuário responsável:</label>
        <br>

        <select id="usuario_id" name="usuario_id" required>

            <?php while ($usuario = $resultado_usuarios->fetch_assoc()): ?>

                <option value="<?= $usuario["id"] ?>" <?= ($usuario["id"] == $prato["usuario_id"]) ? "selected" : "" ?>>
                    <?= htmlspecialchars($usuario["nome"]) ?>
                </option>

            <?php endwhile; ?>

        </select>
        <br><br>

        <button type="submit"> Salvar alterações </button>

    </form>

    <br>

    <a href="pratos.php"> Voltar para os pratos</a>

</body>
</html>

<?php
$conn->close();
?>