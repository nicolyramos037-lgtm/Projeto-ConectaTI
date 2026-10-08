<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/UsuarioController.php";

$id_usuario = $_SESSION["id_usuario"];

$nome = $_SESSION["nome"];
$email = $_SESSION["email"];

$mensagem = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $novoNome = trim($_POST["nome"]);
    $novoEmail = trim($_POST["email"]);

    if ($novoNome === "" || $novoEmail === "") {

        $erro = "Preencha todos os campos.";

    } elseif (!filter_var($novoEmail, FILTER_VALIDATE_EMAIL)) {

        $erro = "Digite um e-mail válido.";

    } else {

        $usuarioController = new UsuarioController();

        $resultado = $usuarioController->atualizar(
            $id_usuario,
            $novoNome,
            $novoEmail
        );

        if ($resultado === "EMAIL_EXISTENTE") {

            $erro = "Este e-mail já está sendo utilizado.";

        } elseif ($resultado) {

            $_SESSION["nome"] = $novoNome;
            $_SESSION["email"] = $novoEmail;

            $mensagem = "Dados atualizados com sucesso.";

            $nome = $novoNome;
            $email = $novoEmail;

        } else {

            $erro = "Não foi possível atualizar os dados.";

        }
    }
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

    <title>Editar Perfil - ConectaTI</title>

</head>

<body>

    <?php require_once __DIR__ . "/../menu/menu.php"; ?>

    <h1>ConectaTI</h1>

    <h2>Editar Perfil</h2>

    <?php if ($mensagem !== ""): ?>

        <p>
            <strong>
                <?= htmlspecialchars($mensagem) ?>
            </strong>
        </p>

    <?php endif; ?>

    <?php if ($erro !== ""): ?>

        <p>
            <strong>
                <?= htmlspecialchars($erro) ?>
            </strong>
        </p>

    <?php endif; ?>

    <form method="POST">

        <p>

            <label for="nome">
                Nome:
            </label>

            <br>

            <input
                type="text"
                id="nome"
                name="nome"
                value="<?= htmlspecialchars($nome) ?>"
                required
            >

        </p>

        <p>

            <label for="email">
                E-mail:
            </label>

            <br>

            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($email) ?>"
                required
            >

        </p>

        <button type="submit">
            Salvar alterações
        </button>

    </form>

    <p>

        <a href="/ProjetoConectaTI/ConectaTI/views/perfil/perfil.php">
            ← Voltar para meu perfil
        </a>

    </p>

</body>

</html>