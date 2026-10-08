<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../controllers/RespostaController.php";
require_once __DIR__ . "/../../controllers/NotificacaoController.php";

$id_usuario = $_SESSION["id_usuario"];
$tipo = $_SESSION["tipo"];


// ============================================
// VERIFICA SE O ID DA DÚVIDA FOI INFORMADO
// ============================================

if (!isset($_GET["id"])) {
    echo "<h2>Dúvida não encontrada.</h2>";
    exit;
}

$id_duvida = intval($_GET["id"]);


// ============================================
// BUSCA A DÚVIDA
// ============================================

$sql = "SELECT
            d.id_duvida,
            d.id_usuario,
            d.id_profissional,
            d.id_empresa,
            d.titulo,
            d.pergunta,
            d.status,
            d.data_criacao,
            a.nome AS area
        FROM duvida d
        INNER JOIN area_ti a
            ON d.id_area = a.id_area
        WHERE d.id_duvida = ?";

$stmt = $conexao->prepare($sql);

$stmt->bind_param(
    "i",
    $id_duvida
);

$stmt->execute();

$resultado = $stmt->get_result();

$duvida = $resultado->fetch_assoc();


if (!$duvida) {

    echo "<h2>Dúvida não encontrada.</h2>";

    exit;
}


// ============================================
// VERIFICA AUTORIZAÇÃO
// ============================================

$autorizado = false;


// ADMINISTRADORA
if ($tipo === "ADMINISTRADORA") {

    $autorizado = true;
}


// PROFISSIONAL
elseif ($tipo === "PROFISSIONAL") {

    $sql = "SELECT id_profissional
            FROM profissional
            WHERE id_usuario = ?
            AND status = 'ATIVO'";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "i",
        $id_usuario
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    $profissional = $resultado->fetch_assoc();


    if (
        $profissional &&
        $duvida["id_profissional"] == $profissional["id_profissional"]
    ) {

        $autorizado = true;
    }
}


// EMPRESA
elseif ($tipo === "EMPRESA") {

    $sql = "SELECT id_empresa
            FROM empresa
            WHERE id_usuario = ?
            AND status = 'ATIVA'";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "i",
        $id_usuario
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    $empresa = $resultado->fetch_assoc();


    if (
        $empresa &&
        $duvida["id_empresa"] == $empresa["id_empresa"]
    ) {

        $autorizado = true;
    }
}


// ============================================
// BLOQUEIA ACESSO NÃO AUTORIZADO
// ============================================

if (!$autorizado) {

    echo "<h2>Acesso não permitido</h2>";

    echo "<p>
            Você não tem permissão para responder esta dúvida.
          </p>";

    echo "<p>
            <a href='/ProjetoConectaTI/ConectaTI/views/duvidas/recebidas.php'>
                ← Voltar para dúvidas recebidas
            </a>
          </p>";

    exit;
}


// ============================================
// ENVIO DA RESPOSTA
// ============================================

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $resposta = trim($_POST["resposta"] ?? "");


    if ($resposta === "") {

        $mensagem = "Digite uma resposta antes de enviar.";

    } else {

        $respostaController = new RespostaController();

        $resultadoResposta =
            $respostaController->cadastrar(
                $id_duvida,
                $id_usuario,
                $resposta
            );


        if ($resultadoResposta) {

            // ============================================
            // ATUALIZA O STATUS DA DÚVIDA
            // ============================================

            $sql = "UPDATE duvida
                    SET status = 'RESPONDIDA',
                        data_resposta = NOW()
                    WHERE id_duvida = ?";

            $stmt = $conexao->prepare($sql);

            $stmt->bind_param(
                "i",
                $id_duvida
            );

            $stmt->execute();


            // ============================================
            // CRIA A NOTIFICAÇÃO
            // ============================================

            $notificacaoController =
                new NotificacaoController();

            $notificacaoController->cadastrar(
                $duvida["id_usuario"],
                "Sua dúvida foi respondida",
                "Sua dúvida \"" .
                $duvida["titulo"] .
                "\" recebeu uma nova resposta.",
                "DUVIDA"
            );


            // ============================================
            // REDIRECIONA PARA A DÚVIDA
            // ============================================

            header(
                "Location: /ProjetoConectaTI/ConectaTI/views/duvidas/visualizar.php?id="
                . $id_duvida
            );

            exit;

        } else {

            $mensagem =
                "Não foi possível cadastrar a resposta.";
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

    <title>Responder Dúvida - ConectaTI</title>

</head>


<body>


<?php

require_once __DIR__ . "/../menu/menu.php";

?>


<h1>ConectaTI</h1>


<h2>Responder Dúvida 💬</h2>


<hr>


<h3>

    <?php echo htmlspecialchars(
        $duvida["titulo"]
    ); ?>

</h3>


<p>

    <strong>Área:</strong>

    <?php echo htmlspecialchars(
        $duvida["area"]
    ); ?>

</p>


<p>

    <strong>Pergunta:</strong>

</p>


<p>

    <?php echo nl2br(
        htmlspecialchars(
            $duvida["pergunta"]
        )
    ); ?>

</p>


<hr>


<?php if ($mensagem !== ""): ?>

    <p>

        <strong>

            <?php echo htmlspecialchars(
                $mensagem
            ); ?>

        </strong>

    </p>

<?php endif; ?>


<form method="POST">


    <label for="resposta">

        <strong>Sua resposta:</strong>

    </label>


    <br><br>


    <textarea
        name="resposta"
        id="resposta"
        rows="8"
        cols="60"
        required
    ></textarea>


    <br><br>


    <button type="submit">

        Enviar resposta

    </button>


</form>


<hr>


<p>

    <a
        href="/ProjetoConectaTI/ConectaTI/views/duvidas/recebidas.php"
    >

        ← Voltar para dúvidas recebidas

    </a>

</p>


</body>

</html>