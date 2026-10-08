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

require_once __DIR__ . "/../../controllers/UsuarioController.php";

$nome = $_SESSION["nome"];

$controller = new UsuarioController();

$usuarios = $controller->listarUsuarios();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Usuários - Administração</title>

</head>

<body>

    <h1>ConectaTI</h1>

    <h2>Gerenciamento de Usuários</h2>

    <p>
        Olá, <?php echo htmlspecialchars($nome); ?>!
    </p>

    <hr>

    <h3>Usuários cadastrados</h3>

    <?php if (count($usuarios) > 0): ?>

        <table border="1" cellpadding="8">

            <tr>

                <th>ID</th>

                <th>Nome</th>

                <th>E-mail</th>

                <th>Tipo</th>

                <th>Status</th>

                <th>Data de cadastro</th>

            </tr>

            <?php foreach ($usuarios as $usuario): ?>

                <tr>

                    <td>
                        <?php echo $usuario["id_usuario"]; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($usuario["nome"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($usuario["email"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($usuario["tipo"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($usuario["status"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($usuario["data_cadastro"]); ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    <?php else: ?>

        <p>
            Nenhum usuário cadastrado.
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