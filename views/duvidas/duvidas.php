<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/DuvidaController.php";

$nome = $_SESSION["nome"];
$id_usuario = $_SESSION["id_usuario"];

$controller = new DuvidaController();

$duvidas = $controller->listarMinhasDuvidas($id_usuario);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Minhas Dúvidas - ConectaTI</title>

</head>

<body>

    <?php require_once __DIR__ . "/../menu/menu.php"; ?>

    <h1>ConectaTI</h1>

    <h2>Minhas Dúvidas</h2>

    <p>
        Olá,
        <?php echo htmlspecialchars($nome); ?>!
    </p>

    <p>
        Aqui você pode acompanhar as dúvidas que publicou
        e verificar as respostas recebidas.
    </p>

    <p>

        <a href="/ProjetoConectaTI/ConectaTI/views/duvidas/cadastrar.php">

            ➕ Fazer uma nova pergunta

        </a>

    </p>

    <hr>

    <h3>Minhas perguntas</h3>

    <?php if (empty($duvidas)): ?>

        <p>
            Você ainda não cadastrou nenhuma dúvida.
        </p>

    <?php else: ?>

        <?php foreach ($duvidas as $duvida): ?>

            <article>

                <h4>

                    <?php echo htmlspecialchars(
                        $duvida["titulo"]
                    ); ?>

                </h4>

                <p>

                    <strong>Área:</strong>

                    <?php echo htmlspecialchars(
                        $duvida["area"]
                    ); ?>

                </p>

                <p>

                    <?php echo nl2br(
                        htmlspecialchars(
                            $duvida["pergunta"]
                        )
                    ); ?>

                </p>

                <p>

                    <strong>Status:</strong>

                    <?php echo htmlspecialchars(
                        $duvida["status"]
                    ); ?>

                </p>

                <p>

                    <strong>Data:</strong>

                    <?php echo htmlspecialchars(
                        $duvida["data_criacao"]
                    ); ?>

                </p>

                <p>

                    <a
                        href="/ProjetoConectaTI/ConectaTI/views/duvidas/visualizar.php?id=<?php echo $duvida["id_duvida"]; ?>"
                    >

                        💬 Ver pergunta e respostas

                    </a>

                </p>

                <hr>

            </article>

        <?php endforeach; ?>

    <?php endif; ?>

    <p>

        <a href="/ProjetoConectaTI/ConectaTI/views/inicio/inicio.php">

            ← Voltar para o início

        </a>

    </p>

</body>

</html>