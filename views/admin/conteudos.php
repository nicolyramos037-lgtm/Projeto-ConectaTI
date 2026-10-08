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

require_once __DIR__ . "/../../controllers/ConteudoController.php";

$nome = $_SESSION["nome"];

$controller = new ConteudoController();

$conteudos = $controller->listarConteudos();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Conteúdos - Administração</title>

</head>

<body>

    <h1>ConectaTI</h1>

    <h2>Gerenciamento de Conteúdos</h2>

    <p>
        Olá, <?php echo htmlspecialchars($nome); ?>!
    </p>

    <hr>

    <h3>Conteúdos cadastrados</h3>

    <?php if (count($conteudos) > 0): ?>

        <table border="1" cellpadding="8">

            <tr>

                <th>ID</th>

                <th>Título</th>

                <th>Descrição</th>

                <th>Tipo</th>

                <th>Link</th>

                <th>Status</th>

                <th>Data</th>

            </tr>

            <?php foreach ($conteudos as $conteudo): ?>

                <tr>

                    <td>
                        <?php echo $conteudo["id_conteudo"]; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($conteudo["titulo"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($conteudo["descricao"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($conteudo["tipo"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($conteudo["link"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($conteudo["status"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($conteudo["data_publicacao"]); ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    <?php else: ?>

        <p>
            Nenhum conteúdo cadastrado.
        </p>

    <?php endif; ?>

    <hr>

    <p>
        <a href="admin.php">
            ← Voltar para administração
        </a>
    </p>

</body>

</html>