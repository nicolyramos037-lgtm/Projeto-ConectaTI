<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/OportunidadeController.php";
require_once __DIR__ . "/../../controllers/FavoritoController.php";

$id_usuario = $_SESSION["id_usuario"];
$nome = $_SESSION["nome"];

$oportunidadeController = new OportunidadeController();
$favoritoController = new FavoritoController();

$oportunidades = $oportunidadeController->listarOportunidadesPorUsuario(
    $id_usuario
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_oportunidade = intval($_POST["id_oportunidade"]);

    $favoritoController->adicionarOportunidade(
        $id_usuario,
        $id_oportunidade
    );

    header("Location: /ProjetoConectaTI/ConectaTI/views/oportunidades/oportunidades.php");
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

    <title>Oportunidades - ConectaTI</title>

</head>

<body>

    <?php require_once __DIR__ . "/../menu/menu.php"; ?>

    <h1>ConectaTI</h1>

    <h2>Oportunidades 💼</h2>

    <p>
        Olá, <?php echo htmlspecialchars($nome); ?>!
    </p>

    <p>
        Aqui estão oportunidades relacionadas às áreas de TI
        que você escolheu no seu perfil.
    </p>

    <hr>

    <h3>Oportunidades para você</h3>

    <?php if (empty($oportunidades)): ?>

        <p>
            Ainda não encontramos oportunidades relacionadas
            às suas áreas de interesse.
        </p>

    <?php else: ?>

        <?php foreach ($oportunidades as $oportunidade): ?>

            <article>

                <h4>
                    <?php echo htmlspecialchars($oportunidade["titulo"]); ?>
                </h4>

                <p>
                    <?php echo htmlspecialchars($oportunidade["descricao"]); ?>
                </p>

                <p>
                    <strong>Empresa:</strong>
                    <?php echo htmlspecialchars($oportunidade["empresa"]); ?>
                </p>

                <p>
                    <strong>Tipo:</strong>
                    <?php echo htmlspecialchars($oportunidade["tipo"]); ?>
                </p>

                <p>
                    <strong>Modalidade:</strong>
                    <?php echo htmlspecialchars($oportunidade["modalidade"]); ?>
                </p>

                <?php if (!empty($oportunidade["local_oportunidade"])): ?>

                    <p>
                        <strong>Local:</strong>
                        <?php echo htmlspecialchars(
                            $oportunidade["local_oportunidade"]
                        ); ?>
                    </p>

                <?php endif; ?>

                <?php if (!empty($oportunidade["data_limite"])): ?>

                    <p>
                        <strong>Prazo para inscrição:</strong>
                        <?php echo date(
                            "d/m/Y",
                            strtotime($oportunidade["data_limite"])
                        ); ?>
                    </p>

                <?php endif; ?>

                <?php if (!empty($oportunidade["link"])): ?>

                    <p>
                        <a
                            href="<?php echo htmlspecialchars(
                                $oportunidade["link"]
                            ); ?>"
                            target="_blank"
                        >
                            Ver oportunidade
                        </a>
                    </p>

                <?php endif; ?>

                <form method="POST">

                    <input
                        type="hidden"
                        name="id_oportunidade"
                        value="<?php echo $oportunidade["id_oportunidade"]; ?>"
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