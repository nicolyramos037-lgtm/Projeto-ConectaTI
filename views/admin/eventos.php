<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login/login.php");
    exit;
}

if ($_SESSION["tipo"] !== "ADMINISTRADORA") {
    echo "Acesso permitido somente para administradoras.";
    exit;
}

$nome = $_SESSION["nome"];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Eventos - Administração</title>

</head>

<body>

    <h1>ConectaTI</h1>

    <h2>Gerenciamento de Eventos</h2>

    <p>
        Olá, <?php echo htmlspecialchars($nome); ?>!
    </p>

    <p>
        Nesta área a administradora poderá gerenciar os eventos disponíveis no ConectaTI.
    </p>

    <hr>

    <h3>Eventos cadastrados</h3>

    <p>
        Os eventos cadastrados no sistema serão exibidos aqui.
    </p>

    <ul>

        <li>Visualizar eventos</li>

        <li>Cadastrar novos eventos</li>

        <li>Alterar eventos</li>

        <li>Inativar eventos</li>

    </ul>

    <hr>

    <p>
        <a href="admin.php">
            ← Voltar para administração
        </a>
    </p>

</body>

</html>