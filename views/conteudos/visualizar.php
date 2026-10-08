<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../config/conexao.php";

if (!isset($_GET["id"])) {
    echo "Conteúdo não encontrado.";
    exit;
}

$id_conteudo = intval($_GET["id"]);

$sql = "SELECT
            id_conteudo,
            titulo,
            descricao,
            tipo,
            link,
            imagem,
            data_publicacao
        FROM conteudo
        WHERE id_conteudo = ?
        AND status = 'PUBLICADO'";

$stmt = $conexao->prepare($sql);

$stmt->bind_param("i", $id_conteudo);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Conteúdo não encontrado.";
    exit;
}

$conteudo = $resultado->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?php echo htmlspecialchars($conteudo["titulo"]); ?> - ConectaTI
    </title>

</head>

<body>

    <?php require_once __DIR__ . "/../menu/menu.php"; ?>

    <h1>ConectaTI</h1>

    <h2>
        <?php echo htmlspecialchars($conteudo["titulo"]); ?>
    </h2>

    <p>
        <strong>Tipo:</strong>
        <?php echo htmlspecialchars($conteudo["tipo"]); ?>
    </p>

    <hr>

    <?php if (!empty($conteudo["imagem"])): ?>

        <p>

            <img
                src="<?php echo htmlspecialchars($conteudo["imagem"]); ?>"
                alt="<?php echo htmlspecialchars($conteudo["titulo"]); ?>"
                width="300"
            >

        </p>

    <?php endif; ?>

    <p>

        <?php echo nl2br(
            htmlspecialchars($conteudo["descricao"])
        ); ?>

    </p>

    <?php if (!empty($conteudo["link"])): ?>

        <p>

            <a
                href="<?php echo htmlspecialchars($conteudo["link"]); ?>"
                target="_blank"
            >

                Acessar conteúdo

            </a>

        </p>

    <?php endif; ?>

    <hr>

    <p>

        <a href="/ProjetoConectaTI/ConectaTI/views/conteudos/conteudos.php">

            ← Voltar para conteúdos

        </a>

    </p>

    <p>

        <a href="/ProjetoConectaTI/ConectaTI/views/inicio/inicio.php">

            ← Voltar para o início

        </a>

    </p>

</body>

</html>