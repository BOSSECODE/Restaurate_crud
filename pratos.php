<?php

require_once __DIR__ . "/config/conexao.php";

$usuario_id = isset($_GET["usuario_id"]) ? $_GET["usuario_id"] : "";

$sql_usuarios = "SELECT id, nome FROM usuarios ORDER BY nome";
$resultado_usuarios = $conn->query($sql_usuarios);

if (!empty($usuario_id)) {

$sql = "SELECT pratos.id, pratos.nome, pratos.descricao, pratos.preco, pratos.categoria, usuarios.nome AS usuario_nome FROM pratos INNER JOIN usuarios  ON pratos.usuario_id = usuarios.id WHERE pratos.usuario_id = ? ORDER BY pratos.id DESC";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultado_pratos = $stmt->get_result();

} else {

$sql = "SELECT  pratos.id, pratos.nome, pratos.descricao, pratos.preco, pratos.categoria, usuarios.nome AS usuario_nome FROM pratos INNER JOIN usuarios  ON pratos.usuario_id = usuarios.id ORDER BY pratos.id DESC";
    $resultado_pratos = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratos cadastrados</title>

</head>

<body>

    <h1>Pratos cadastrados</h1>

    <form action="pratos.php" method="GET">
        <label for="usuario_id"> Filtrar por usuário:</label>

        <select name="usuario_id" id="usuario_id">
            <option value="">Todos os usuários</option>

            <?php while ($usuario = $resultado_usuarios->fetch_assoc()): ?>

                <option value="<?= $usuario["id"] ?>" <?= ($usuario_id == $usuario["id"]) ? "selected" : "" ?>>

                    <?= htmlspecialchars($usuario["nome"]) ?>

                </option>

            <?php endwhile; ?>

        </select>

        <button type="submit"> Filtrar</button>

    </form>

    <br>

    <table border="1" cellpadding="8">

        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Usuário responsável</th>
                <th>Ações</th>
            </tr>

        </thead>

        <tbody>

            <?php if ($resultado_pratos->num_rows > 0): ?>
                <?php while ($prato = $resultado_pratos->fetch_assoc()): ?>
                    <tr>
                        <td><?= $prato["id"] ?></td>
                        <td><?= htmlspecialchars($prato["nome"]) ?></td>
                        <td><?= htmlspecialchars($prato["descricao"]) ?></td>
                        <td>R$<?= number_format($prato["preco"],2,",",".") ?></td>
                        <td><?= htmlspecialchars($prato["categoria"]) ?></td>
                        <td><?= htmlspecialchars($prato["usuario_nome"]) ?></td>
                        <td><a href="editar_prato.php?id=<?= $prato["id"] ?>"> Editar</a>|<a href="excluir_prato.php?id=<?= $prato["id"] ?>"onclick="return confirm('Tem certeza que deseja excluir este prato?');">Excluir</a></td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7">Nenhum prato encontrado.</td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

    <br>

    <a href="cadastrar_prato.php">Cadastrar novo prato</a>

    <br><br>

    <a href="index.php">Voltar para o início</a>

</body>

</html>

<?php
$conn->close();
?>