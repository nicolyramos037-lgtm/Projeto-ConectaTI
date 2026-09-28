<?php

require_once __DIR__ . "/../../controllers/UsuarioController.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $controller = new UsuarioController();

    if ($controller->cadastrar($nome, $email, $senha)) {
        $mensagem = "Cadastro realizado com sucesso!";
    } else {
        $mensagem = "Erro ao realizar o cadastro.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro - ConectaTI</title>
</head>

<body>

    <h1>Cadastro ConectaTI</h1>

    <?php if ($mensagem != ""): ?>
        <p><?php echo $mensagem; ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Nome:</label>
        <input type="text" name="nome" required>

        <br><br>

        <label>E-mail:</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Senha:</label>
        <input type="password" name="senha" required>

        <br><br>

        <button type="submit">Cadastrar</button>

    </form>

</body>

</html>