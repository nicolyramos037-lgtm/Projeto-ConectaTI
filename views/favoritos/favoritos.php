<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/FavoritoController.php";

$id_usuario = $_SESSION["id_usuario"];
$nome = $_SESSION["nome"];

$favoritoController = new FavoritoController();


// REMOVER FAVORITO

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tipo = $_POST["tipo"] ?? "";
    $id = intval($_POST["id"] ?? 0);

    if ($id > 0) {

        if ($tipo === "conteudo") {

            $favoritoController->removerConteudo(
                $id_usuario,
                $id
            );

        } elseif ($tipo === "curso") {

            $favoritoController->removerCurso(
                $id_usuario,
                $id
            );

        } elseif ($tipo === "evento") {

            $favoritoController->removerEvento(
                $id_usuario,
                $id
            );

        } elseif ($tipo === "oportunidade") {

            $favoritoController->removerOportunidade(
                $id_usuario,
                $id
            );
        }
    }

    header("Location: /ProjetoConectaTI/ConectaTI/views/favoritos/favoritos.php");
    exit;
}


// LISTAR FAVORITOS

$conteudos = $favoritoController->listarConteudos($id_usuario);

$cursos = $favoritoController->listarCursos($id_usuario);

$eventos = $favoritoController->listarEventos($id_usuario);

$oportunidades = $favoritoController->listarOportunidades($id_usuario);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Favoritos - ConectaTI</title>

</head>

<body>

    <?php require_once __DIR__ . "/../menu/menu.php"; ?>

    <h1>ConectaTI</h1>

    <h2>Favoritos ⭐</h2>

    <p>
        Olá, <?php echo htmlspecialchars($nome); ?>!
    </p>

    <p>
        Aqui estão os conteúdos, cursos, eventos e oportunidades
        que você salvou.
    </p>

    <hr>


    <!-- CONTEÚDOS -->

    <h3>📚 Conteúdos favoritos</h3>

    <?php if (empty($conteudos)): ?>

        <p>
            Você ainda não salvou nenhum conteúdo.
        </p>

    <?php else: ?>

        <?php foreach ($conteudos as $conteudo): ?>

            <article>

                <h4>
                    <?php echo htmlspecialchars($conteudo["titulo"]); ?>
                </h4>

                <?php if (!empty($conteudo["descricao"])): ?>

                    <p>
                        <?php echo htmlspecialchars($conteudo["descricao"]); ?>
                    </p>

                <?php endif; ?>

                <form method="POST">

                    <input
                        type="hidden"
                        name="tipo"
                        value="conteudo"
                    >

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo $conteudo["id_conteudo"]; ?>"
                    >

                    <button type="submit">
                        🗑 Remover dos favoritos
                    </button>

                </form>

            </article>

            <hr>

        <?php endforeach; ?>

    <?php endif; ?>


    <!-- CURSOS -->

    <h3>🎓 Cursos favoritos</h3>

    <?php if (empty($cursos)): ?>

        <p>
            Você ainda não salvou nenhum curso.
        </p>

    <?php else: ?>

        <?php foreach ($cursos as $curso): ?>

            <article>

                <h4>
                    <?php echo htmlspecialchars($curso["titulo"]); ?>
                </h4>

                <?php if (!empty($curso["descricao"])): ?>

                    <p>
                        <?php echo htmlspecialchars($curso["descricao"]); ?>
                    </p>

                <?php endif; ?>

                <?php if (!empty($curso["instituicao"])): ?>

                    <p>
                        <strong>Instituição:</strong>
                        <?php echo htmlspecialchars($curso["instituicao"]); ?>
                    </p>

                <?php endif; ?>

                <form method="POST">

                    <input
                        type="hidden"
                        name="tipo"
                        value="curso"
                    >

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo $curso["id_curso"]; ?>"
                    >

                    <button type="submit">
                        🗑 Remover dos favoritos
                    </button>

                </form>

            </article>

            <hr>

        <?php endforeach; ?>

    <?php endif; ?>


    <!-- EVENTOS -->

    <h3>📅 Eventos favoritos</h3>

    <?php if (empty($eventos)): ?>

        <p>
            Você ainda não salvou nenhum evento.
        </p>

    <?php else: ?>

        <?php foreach ($eventos as $evento): ?>

            <article>

                <h4>
                    <?php echo htmlspecialchars($evento["titulo"]); ?>
                </h4>

                <?php if (!empty($evento["descricao"])): ?>

                    <p>
                        <?php echo htmlspecialchars($evento["descricao"]); ?>
                    </p>

                <?php endif; ?>

                <?php if (!empty($evento["data_inicio"])): ?>

                    <p>
                        <strong>Data:</strong>

                        <?php echo date(
                            "d/m/Y H:i",
                            strtotime($evento["data_inicio"])
                        ); ?>

                    </p>

                <?php endif; ?>

                <?php if (!empty($evento["local_evento"])): ?>

                    <p>
                        <strong>Local:</strong>
                        <?php echo htmlspecialchars($evento["local_evento"]); ?>
                    </p>

                <?php endif; ?>

                <form method="POST">

                    <input
                        type="hidden"
                        name="tipo"
                        value="evento"
                    >

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo $evento["id_evento"]; ?>"
                    >

                    <button type="submit">
                        🗑 Remover dos favoritos
                    </button>

                </form>

            </article>

            <hr>

        <?php endforeach; ?>

    <?php endif; ?>


    <!-- OPORTUNIDADES -->

    <h3>💼 Oportunidades favoritas</h3>

    <?php if (empty($oportunidades)): ?>

        <p>
            Você ainda não salvou nenhuma oportunidade.
        </p>

    <?php else: ?>

        <?php foreach ($oportunidades as $oportunidade): ?>

            <article>

                <h4>
                    <?php echo htmlspecialchars($oportunidade["titulo"]); ?>
                </h4>

                <?php if (!empty($oportunidade["descricao"])): ?>

                    <p>
                        <?php echo htmlspecialchars($oportunidade["descricao"]); ?>
                    </p>

                <?php endif; ?>

                <?php if (!empty($oportunidade["empresa"])): ?>

                    <p>
                        <strong>Empresa:</strong>
                        <?php echo htmlspecialchars($oportunidade["empresa"]); ?>
                    </p>

                <?php endif; ?>

                <?php if (!empty($oportunidade["tipo"])): ?>

                    <p>
                        <strong>Tipo:</strong>
                        <?php echo htmlspecialchars($oportunidade["tipo"]); ?>
                    </p>

                <?php endif; ?>

                <?php if (!empty($oportunidade["modalidade"])): ?>

                    <p>
                        <strong>Modalidade:</strong>
                        <?php echo htmlspecialchars($oportunidade["modalidade"]); ?>
                    </p>

                <?php endif; ?>

                <form method="POST">

                    <input
                        type="hidden"
                        name="tipo"
                        value="oportunidade"
                    >

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo $oportunidade["id_oportunidade"]; ?>"
                    >

                    <button type="submit">
                        🗑 Remover dos favoritos
                    </button>

                </form>

            </article>

            <hr>

        <?php endforeach; ?>

    <?php endif; ?>


    <p>

        <a href="/ProjetoConectaTI/ConectaTI/views/inicio/inicio.php">

            ← Voltar para o início

        </a>

    </p>

</body>

</html>