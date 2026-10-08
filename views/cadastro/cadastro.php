<?php

session_start();

require_once __DIR__ . "/../../controllers/UsuarioController.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];
    $tipo = $_POST["tipo"];

    $controller = new UsuarioController();

    $resultado = $controller->cadastrar(
        $nome,
        $email,
        $senha,
        $tipo
    );

    if ($resultado === true) {

        $usuario = $controller->buscarPorEmail($email);

        if ($usuario !== null) {

            $_SESSION["id_usuario"] = $usuario["id_usuario"];
            $_SESSION["nome"] = $usuario["nome"];
            $_SESSION["email"] = $usuario["email"];
            $_SESSION["tipo"] = $usuario["tipo"];

            if ($usuario["tipo"] === "EMPRESA") {

                header("Location: /ProjetoConectaTI/ConectaTI/views/empresa/cadastro.php");
                exit;
            }

            header("Location: /ProjetoConectaTI/ConectaTI/views/questionario/questionario.php");
            exit;
        }

        $mensagem = "Cadastro realizado, mas não foi possível iniciar o sistema.";

    } elseif ($resultado === "EMAIL_EXISTENTE") {

        $mensagem = "Este e-mail já está cadastrado.";

    } else {

        $mensagem = "Erro ao realizar o cadastro.";
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

<title>Cadastro - ConectaTI</title>

</head>

<body>

<h1>Cadastro ConectaTI</h1>

<?php if ($mensagem != ""): ?>

<p>

<?php echo htmlspecialchars($mensagem); ?>

</p>

<?php endif; ?>


<form method="POST">

<label>
    Nome:
</label>

<br>

<input
    type="text"
    name="nome"
    required
>

<br><br>


<label>
    E-mail:
</label>

<br>

<input
    type="email"
    name="email"
    required
>

<br><br>


<label>
    Senha:
</label>

<br>

<input
    type="password"
    name="senha"
    required
>

<br><br>


<label>
    Tipo de conta:
</label>

<br>

<select name="tipo" required>

    <option value="USUARIA">
        Usuária
    </option>

    <option value="EMPRESA">
        Empresa
    </option>

</select>

<br><br>


<button type="submit">

    Cadastrar

</button>

</form>


<p>

Já possui uma conta?

<a href="/ProjetoConectaTI/ConectaTI/views/login/login.php">

    Fazer login

</a>

</p>

</body>

</html>