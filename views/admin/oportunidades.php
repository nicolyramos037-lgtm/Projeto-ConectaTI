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

    <title>Oportunidades - Administração</title>

</head>

<body>

    <h1>ConectaTI</h1>

    <h2>Gerenciamento de Oportunidades</h2>

    <p>
        Olá, <?php echo htmlspecialchars($nome); ?>!
    </p>

    <p>
        Nesta área a administradora poderá gerenciar as oportunidades disponíveis no ConectaTI.
    </p>

    <hr>

    <h3>Oportunidades cadastradas</h3>

    <p>
        As oportunidades cadastradas no sistema serão exibidas aqui.
    </p>

    <ul>

        <li>Visualizar oportunidades</li>

        <li>Cadastrar novas oportunidades</li>

        <li>Alterar oportunidades</li>

        <li>Inativar oportunidades</li>

    </ul>

    <hr>

    <p>
        <a href="admin.php">
            ← Voltar para administração
        </a>
    </p>

</body>

</html>