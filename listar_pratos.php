<?php

require_once "conexao.php";

$id_usuario = $_GET["id_usuario"] ?? "";

$pratos = [];
$nome_usuario = "";

// Busca todos os usuários para o campo de seleção
$usuarios = $conn->query(
    "SELECT id_usuario, nome
     FROM usuarios
     ORDER BY nome"
);

// Se um usuário foi selecionado
if ($id_usuario !== "" && is_numeric($id_usuario)) {

    $id_usuario = (int)$id_usuario;

    // Busca o nome do usuário
    $stmtUsuario = $conn->prepare(
        "SELECT nome
         FROM usuarios
         WHERE id_usuario = ?"
    );

    $stmtUsuario->bind_param(
        "i",
        $id_usuario
    );

    $stmtUsuario->execute();

    $resultadoUsuario = $stmtUsuario->get_result();

    if ($usuario = $resultadoUsuario->fetch_assoc()) {
        $nome_usuario = $usuario["nome"];
    }

    $stmtUsuario->close();


    // Busca os pratos desse usuário
    $stmt = $conn->prepare(
        "SELECT
            id_prato,
            nome,
            descricao,
            preco,
            categoria
         FROM pratos
         WHERE id_usuario = ?
         ORDER BY nome"
    );

    $stmt->bind_param(
        "i",
        $id_usuario
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    while ($prato = $resultado->fetch_assoc()) {
        $pratos[] = $prato;
    }

    $stmt->close();
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

    <title>Pratos por Usuário</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>

<body>

<header>

    <h1>Sistema de Pratos</h1>

    <nav>

        <a href="index.php">
            Início
        </a>

        <a href="cadastrar_usuario.php">
            Cadastrar Usuário
        </a>

        <a href="cadastrar_prato.php">
            Cadastrar Prato
        </a>

        <a href="listar_pratos.php">
            Pratos por Usuário
        </a>

    </nav>

</header>


<main class="container">

    <div class="card">

        <h2>Pratos por usuário</h2>

        <p>
            Selecione um usuário para visualizar seus pratos.
        </p>


        <form method="GET">

            <label for="id_usuario">
                Usuário
            </label>

            <select
                name="id_usuario"
                id="id_usuario"
                required
            >

                <option value="">
                    Selecione um usuário
                </option>

                <?php while (
                    $usuario = $usuarios->fetch_assoc()
                ): ?>

                    <option
                        value="<?= $usuario["id_usuario"] ?>"
                        <?= (
                            $id_usuario == $usuario["id_usuario"]
                        ) ? "selected" : "" ?>
                    >

                        <?= htmlspecialchars(
                            $usuario["nome"]
                        ) ?>

                    </option>

                <?php endwhile; ?>

            </select>


            <button
                class="botao"
                type="submit"
            >
                Pesquisar
            </button>

        </form>

    </div>


    <?php if ($id_usuario !== ""): ?>

        <div class="titulo-area">

            <div>

                <h2>
                    Pratos de <?= htmlspecialchars(
                        $nome_usuario
                    ) ?>
                </h2>

            </div>

        </div>


        <?php if (count($pratos) > 0): ?>

            <div class="tabela-container">

                <table>

                    <thead>

                        <tr>

                            <th>Prato</th>

                            <th>Descrição</th>

                            <th>Preço</th>

                            <th>Categoria</th>

                            <th>Ações</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach (
                            $pratos as $prato
                        ): ?>

                            <tr>

                                <td>

                                    <?= htmlspecialchars(
                                        $prato["nome"]
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $prato["descricao"]
                                    ) ?>

                                </td>


                                <td>

                                    R$

                                    <?= number_format(
                                        $prato["preco"],
                                        2,
                                        ",",
                                        "."
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $prato["categoria"]
                                    ) ?>

                                </td>


                                <td class="acoes">

                                    <a
                                        class="editar"
                                        href="editar_prato.php?id=<?= $prato["id_prato"] ?>"
                                    >
                                        Editar
                                    </a>


                                    <a
                                        class="excluir"
                                        href="excluir_prato.php?id=<?= $prato["id_prato"] ?>"
                                        onclick="return confirm('Tem certeza que deseja excluir este prato?');"
                                    >
                                        Excluir
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="vazio">

                <h3>
                    Nenhum prato encontrado
                </h3>

                <p>
                    Este usuário ainda não possui pratos cadastrados.
                </p>

            </div>

        <?php endif; ?>

    <?php endif; ?>

</main>

</body>

</html>