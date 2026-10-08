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

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_usuario = $_SESSION["id_usuario"];

    $titulo = $_POST["titulo"];
    $descricao = $_POST["descricao"];
    $tipo = $_POST["tipo"];
    $link = !empty($_POST["link"]) ? $_POST["link"] : null;
    $imagem = !empty($_POST["imagem"]) ? $_POST["imagem"] : null;

    $controller = new ConteudoController();

    $resultado = $controller->cadastrar(
        $id_usuario,
        $titulo,
        $descricao,
        $tipo,
        $link,
        $imagem
    );

    if ($resultado) {
        $mensagem = "Conteúdo cadastrado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar conteúdo.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Conteúdo - ConectaTI</title>

</head>

<body>

    <h1>ConectaTI</h1>

    <h2>Cadastrar Conteúdo</h2>

    <?php if ($mensagem != ""): ?>

        <p>
            <?php echo htmlspecialchars($mensagem); ?>
        </p>

    <?php endif; ?>

    <hr>

    <form method="POST">

        <label>Título:</label>

        <br>

        <input
            type="text"
            name="titulo"
            required
        >

        <br><br>

        <label>Descrição:</label>

        <br>

        <textarea
            name="descricao"
            rows="6"
            cols="50"
            required
        ></textarea>

        <br><br>

        <label>Tipo:</label>

        <br>

        <select name="tipo" required>

            <option value="ARTIGO">
                Artigo
            </option>

            <option value="VIDEO">
                Vídeo
            </option>

            <option value="GUIA">
                Guia
            </option>

            <option value="NOTICIA">
                Notícia
            </option>

        </select>

        <br><br>

        <label>Link:</label>

        <br>

        <input
            type="url"
            name="link"
        >

        <br><br>

        <label>Imagem:</label>

        <br>

        <input
            type="text"
            name="imagem"
            placeholder="Nome ou caminho da imagem"
        >

        <br><br>

        <button type="submit">
            Cadastrar conteúdo
        </button>

    </form>

    <hr>

    <p>
        <a href="conteudos.php">
            ← Voltar para conteúdos
        </a>
    </p>

    <p>
        <a href="admin.php">
            ← Voltar para administração
        </a>
    </p>

</body>

</html>