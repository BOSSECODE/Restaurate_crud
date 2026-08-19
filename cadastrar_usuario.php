<?php

require_once __DIR__ . "/config/conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);

    if (empty($nome) || empty($email)) {

        die("Erro: preencha todos os campos obrigatórios.");

    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        die("Erro: informe um e-mail válido.");

    }

    $sql = "INSERT INTO usuarios (nome, email) VALUES (?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        die("Erro ao preparar a consulta: " . $conn->error);

    }

    $stmt->bind_param("ss", $nome, $email);

    if ($stmt->execute()) {

        echo "<h2>Usuário cadastrado com sucesso!</h2>";

        echo "<p><strong>Nome:</strong> " 
            . htmlspecialchars($nome) 
            . "</p>";

        echo "<p><strong>E-mail:</strong> " 
            . htmlspecialchars($email) 
            . "</p>";

        echo "<br>";

        echo "<a href='usuario.php'>Cadastrar outro usuário</a>";

        echo "<br><br>";

        echo "<a href='index.php'>Voltar para o início</a>";

    } else {

        echo "<h2>Erro ao cadastrar usuário.</h2>";

        echo "<p>" 
            . htmlspecialchars($stmt->error) 
            . "</p>";

        echo "<br>";

        echo "<a href='usuario.php'>Voltar</a>";

    }

    $stmt->close();
    $conn->close();

} else {

    echo "<h2>Acesso inválido.</h2>";

    echo "<a href='usuario.php'>Voltar para o cadastro</a>";

}

?>