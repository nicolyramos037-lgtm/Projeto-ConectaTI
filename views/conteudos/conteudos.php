<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/ConteudoController.php";
require_once __DIR__ . "/../../controllers/FavoritoController.php";

$nome = $_SESSION["nome"];
$id_usuario = $_SESSION["id_usuario"];

$controller = new ConteudoController();
$favoritoController = new FavoritoController();

/*
 * Lista somente os conteúdos publicados
 * e relacionados às áreas da usuária.
 */
$conteudos = $controller->listarConteudosPorUsuario($id_usuario);


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_conteudo = intval($_POST["id_conteudo"]);

    $favoritoController->adicionarConteudo(
        $id_usuario,
        $id_conteudo
    );

    header(
        "Location: /ProjetoConectaTI/ConectaTI/views/conteudos/conteudos.php"
    );

    exit;
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

    <title>Conteúdos - ConectaTI</title>

</head>

<body>

    <?php require_once __DIR__ . "/../menu/menu.php"; ?>


    <h1>ConectaTI</h1>

    <h2>Conteúdos</h2>


    <p>

        Olá,
        <?php echo htmlspecialchars($nome); ?>!

    </p>


    <p>

        Aqui você poderá encontrar conteúdos relacionados à
        Tecnologia da Informação.

    </p>


    <hr>


    <h3>Áreas de Tecnologia</h3>


    <ul>

        <li>Programação</li>

        <li>Banco de Dados</li>

        <li>Redes</li>

        <li>Segurança da Informação</li>

        <li>Desenvolvimento Web</li>

        <li>Suporte e Manutenção</li>

    </ul>


    <hr>


    <h3>Conteúdos disponíveis</h3>


    <?php if (empty($conteudos)): ?>

        <p>

            Nenhum conteúdo disponível no momento.

        </p>

    <?php else: ?>


        <?php foreach ($conteudos as $conteudo): ?>

            <article>

                <h4>

                    <?php echo htmlspecialchars(
                        $conteudo["titulo"]
                    ); ?>

                </h4>


                <p>

                    <?php echo htmlspecialchars(
                        $conteudo["descricao"]
                    ); ?>

                </p>


                <p>

                    <strong>Tipo:</strong>

                    <?php echo htmlspecialchars(
                        $conteudo["tipo"]
                    ); ?>

                </p>


                <p>

                    <a
                        href="/ProjetoConectaTI/ConectaTI/views/conteudos/visualizar.php?id=<?php echo $conteudo["id_conteudo"]; ?>"
                    >

                        Ver conteúdo

                    </a>

                </p>


                <?php if (!empty($conteudo["link"])): ?>

                    <p>

                        <a
                            href="<?php echo htmlspecialchars(
                                $conteudo["link"]
                            ); ?>"
                            target="_blank"
                        >

                            Acessar conteúdo

                        </a>

                    </p>

                <?php endif; ?>


                <form method="POST">

                    <input
                        type="hidden"
                        name="id_conteudo"
                        value="<?php echo $conteudo["id_conteudo"]; ?>"
                    >

                    <button type="submit">

                        ⭐ Adicionar aos favoritos

                    </button>

                </form>


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