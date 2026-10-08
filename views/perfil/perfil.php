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
$tipo = $_SESSION["tipo"];

$mensagem = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $acao = $_POST["acao"] ?? "";

    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR DADOS
    |--------------------------------------------------------------------------
    */

    if ($acao === "dados") {

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

                $nome = $novoNome;
                $email = $novoEmail;

                $mensagem = "Dados atualizados com sucesso.";

            } else {

                $erro = "Não foi possível atualizar os dados.";

            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ALTERAR SENHA
    |--------------------------------------------------------------------------
    */

    if ($acao === "senha") {

        $senhaAtual = $_POST["senha_atual"];
        $novaSenha = $_POST["nova_senha"];
        $confirmarSenha = $_POST["confirmar_senha"];

        if (
            $senhaAtual === "" ||
            $novaSenha === "" ||
            $confirmarSenha === ""
        ) {

            $erro = "Preencha todos os campos da senha.";

        } elseif (strlen($novaSenha) < 6) {

            $erro = "A nova senha deve ter pelo menos 6 caracteres.";

        } elseif ($novaSenha !== $confirmarSenha) {

            $erro = "A confirmação da nova senha não confere.";

        } else {

            $usuarioController = new UsuarioController();

            $resultado = $usuarioController->alterarSenha(
                $id_usuario,
                $senhaAtual,
                $novaSenha
            );

            if ($resultado === "SENHA_ATUAL_INCORRETA") {

                $erro = "A senha atual está incorreta.";

            } elseif ($resultado) {

                $mensagem = "Senha alterada com sucesso.";

            } else {

                $erro = "Não foi possível alterar a senha.";

            }
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

    <title>Meu Perfil - ConectaTI</title>

</head>

<body>

    <?php require_once __DIR__ . "/../menu/menu.php"; ?>

    <h1>ConectaTI</h1>

    <h2>Meu Perfil</h2>

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


    <!-- DADOS DA CONTA -->

    <h3>Dados da conta</h3>

    <form method="POST">

        <input
            type="hidden"
            name="acao"
            value="dados"
        >

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

        <p>

            <strong>
                Tipo de conta:
            </strong>

            <?= htmlspecialchars($tipo) ?>

        </p>

        <button type="submit">
            Salvar dados
        </button>

    </form>


    <hr>


    <!-- ALTERAR SENHA -->

    <h3>Alterar senha</h3>

    <form method="POST">

        <input
            type="hidden"
            name="acao"
            value="senha"
        >

        <p>

            <label for="senha_atual">
                Senha atual:
            </label>

            <br>

            <input
                type="password"
                id="senha_atual"
                name="senha_atual"
                required
            >

        </p>

        <p>

            <label for="nova_senha">
                Nova senha:
            </label>

            <br>

            <input
                type="password"
                id="nova_senha"
                name="nova_senha"
                required
            >

        </p>

        <p>

            <label for="confirmar_senha">
                Confirmar nova senha:
            </label>

            <br>

            <input
                type="password"
                id="confirmar_senha"
                name="confirmar_senha"
                required
            >

        </p>

        <button type="submit">
            Alterar senha
        </button>

    </form>


    <hr>


    <p>

        <a href="/ProjetoConectaTI/ConectaTI/views/inicio/inicio.php">
            ← Voltar para o início
        </a>

    </p>

</body>

</html>