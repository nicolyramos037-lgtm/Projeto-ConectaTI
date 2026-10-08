<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../controllers/RespostaController.php";


// ============================================
// VERIFICA A DÚVIDA
// ============================================

if (!isset($_GET["id"])) {
    echo "Dúvida não encontrada.";
    exit;
}

$id_duvida = intval($_GET["id"]);


// ============================================
// APAGAR RESPOSTA
// ============================================

if (
    isset($_GET["apagar_resposta"]) &&
    isset($_GET["id_resposta"])
) {

    $id_resposta = intval($_GET["id_resposta"]);

    $respostaController = new RespostaController();

    $respostaController->apagar(
        $id_resposta,
        $_SESSION["id_usuario"]
    );

    header(
        "Location: /ProjetoConectaTI/ConectaTI/views/duvidas/visualizar.php?id="
        . $id_duvida
    );

    exit;
}


// ============================================
// BUSCAR A DÚVIDA
// ============================================

$sql = "SELECT
            d.id_duvida,
            d.id_usuario,
            d.id_area,
            d.titulo,
            d.pergunta,
            d.status,
            d.data_criacao,
            a.nome AS area,
            u.nome AS nome_usuario
        FROM duvida d

        INNER JOIN area_ti a
            ON d.id_area = a.id_area

        INNER JOIN usuario u
            ON d.id_usuario = u.id_usuario

        WHERE d.id_duvida = ?";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "i",
    $id_duvida
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    echo "Dúvida não encontrada.";

    exit;
}

$duvida = $resultado->fetch_assoc();


// ============================================
// BUSCAR RESPOSTAS
// ============================================

$respostaController = new RespostaController();

$respostas = $respostaController->listarRespostas(
    $id_duvida
);

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

        <?php echo htmlspecialchars(
            $duvida["titulo"]
        ); ?>

        - ConectaTI

    </title>

</head>


<body>


<?php

require_once __DIR__ . "/../menu/menu.php";

?>


<h1>ConectaTI</h1>


<h2>

    <?php echo htmlspecialchars(
        $duvida["titulo"]
    ); ?>

</h2>


<p>

    <strong>Área:</strong>

    <?php echo htmlspecialchars(
        $duvida["area"]
    ); ?>

</p>


<p>

    <strong>Pergunta feita por:</strong>

    <?php echo htmlspecialchars(
        $duvida["nome_usuario"]
    ); ?>

</p>


<p>

    <strong>Status:</strong>

    <?php echo htmlspecialchars(
        $duvida["status"]
    ); ?>

</p>


<hr>


<h3>Pergunta</h3>


<p>

    <?php echo nl2br(
        htmlspecialchars(
            $duvida["pergunta"]
        )
    ); ?>

</p>


<hr>


<h3>Respostas</h3>


<?php if (empty($respostas)): ?>

    <p>

        Esta dúvida ainda não recebeu respostas.

    </p>


<?php else: ?>


    <?php foreach ($respostas as $resposta): ?>


        <article>


            <p>

                <?php if (
                    $resposta["tipo"] === "PROFISSIONAL"
                ): ?>

                    👩‍💻

                <?php elseif (
                    $resposta["tipo"] === "EMPRESA"
                ): ?>

                    🏢

                <?php elseif (
                    $resposta["tipo"] === "ADMINISTRADORA"
                ): ?>

                    👑

                <?php endif; ?>


                <strong>

                    <?php if (
                        $resposta["tipo"] === "EMPRESA"
                        && !empty(
                            $resposta["nome_fantasia"]
                        )
                    ): ?>

                        <?php echo htmlspecialchars(
                            $resposta["nome_fantasia"]
                        ); ?>

                    <?php else: ?>

                        <?php echo htmlspecialchars(
                            $resposta["nome"]
                        ); ?>

                    <?php endif; ?>

                </strong>

            </p>


            <p>

                <?php echo nl2br(
                    htmlspecialchars(
                        $resposta["resposta"]
                    )
                ); ?>

            </p>


            <small>

                Respondido em:

                <?php echo htmlspecialchars(
                    $resposta["data_resposta"]
                ); ?>

            </small>


            <?php

            /*
             * Mostra o botão de apagar somente:
             *
             * 1. Para quem criou a resposta
             * 2. Ou para a administradora
             */

            if (
                $_SESSION["id_usuario"]
                == $resposta["id_usuario"]
                ||
                $_SESSION["tipo"]
                === "ADMINISTRADORA"
            ):

            ?>


                <p>

                    <a
                        href="/ProjetoConectaTI/ConectaTI/views/duvidas/visualizar.php?id=<?php echo $id_duvida; ?>&apagar_resposta=1&id_resposta=<?php echo $resposta["id_resposta"]; ?>"
                        onclick="return confirm('Tem certeza que deseja apagar esta resposta?');"
                    >

                        🗑️ Apagar resposta

                    </a>

                </p>


            <?php endif; ?>


            <hr>


        </article>


    <?php endforeach; ?>


<?php endif; ?>


<?php

/*
 * Somente profissionais, empresas e administradoras
 * poderão responder.
 */

if (
    $_SESSION["tipo"] === "PROFISSIONAL"
    ||
    $_SESSION["tipo"] === "EMPRESA"
    ||
    $_SESSION["tipo"] === "ADMINISTRADORA"
):

?>

    <p>

        <a
            href="/ProjetoConectaTI/ConectaTI/views/duvidas/responder.php?id=<?php echo $id_duvida; ?>"
        >

            💬 Responder esta dúvida

        </a>

    </p>

<?php endif; ?>


<hr>


<p>

    <a
        href="/ProjetoConectaTI/ConectaTI/views/duvidas/duvidas.php"
    >

        ← Voltar para minhas dúvidas

    </a>

</p>


<p>

    <a
        href="/ProjetoConectaTI/ConectaTI/views/inicio/inicio.php"
    >

        ← Voltar para o início

    </a>

</p>


</body>

</html>