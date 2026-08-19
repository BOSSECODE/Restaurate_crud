<?php

require_once __DIR__ . "/config/conexao.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("ID do prato inválido.");
}

$id = (int) $_GET["id"];

$sql = "DELETE FROM pratos WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar a consulta: " . $conn->error);
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: pratos.php");
    exit;
} else {

    echo "Erro ao excluir o prato: "
        . htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();

?>