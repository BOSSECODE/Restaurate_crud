<?php
link rel="stylesheet" href="css/style.css">
require_once "conexao.php";


$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if ($id) {

    
    $stmt = $conn->prepare(
        "DELETE FROM pratos
         WHERE id_prato = ?"
    );

    $stmt->bind_param(
        "i",
        $id
    );

    $stmt->execute();

    $stmt->close();
}


header("Location: index.php?sucesso=1");
exit;

?>