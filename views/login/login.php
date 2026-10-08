<?php

session_start();

require_once __DIR__ . "/../../controllers/UsuarioController.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $controller = new UsuarioController();

    $usuario = $controller->login($email, $senha);

    if ($usuario !== false) {

        $_SESSION["id_usuario"] = $usuario["id_usuario"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];
        $_SESSION["tipo"] = $usuario["tipo"];

        header("Location: /ProjetoConectaTI/ConectaTI/views/inicio/inicio.php");
        exit;

    } else {

        $mensagem = "E-mail ou senha incorretos.";
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

    <title>Login - ConectaTI</title>

</head>

<body>

    <h1>Login ConectaTI</h1>


    <?php if ($mensagem != ""): ?>

        <p>
            <?php echo htmlspecialchars($mensagem); ?>
        </p>

    <?php endif; ?>


    <form method="POST">

        <label>E-mail:</label>

        <input
            type="email"
            name="email"
            required
        >

        <br><br>


        <label>Senha:</label>

        <input
            type="password"
            name="senha"
            required
        >

        <br><br>


        <button type="submit">
            Entrar
        </button>

    </form>


    <p>

        Ainda não possui uma conta?

        <a href="/ProjetoConectaTI/ConectaTI/views/cadastro/cadastro.php">

            Cadastre-se

        </a>

    </p>


</body>

</html>