<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/ProfissionalController.php";

$id_usuario = $_SESSION["id_usuario"];
$nome = $_SESSION["nome"];

$profissionalController = new ProfissionalController();

$profissionais = $profissionalController->listarProfissionaisPorUsuario(
    $id_usuario
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

    <title>Profissionais - ConectaTI</title>

</head>

<body>

    <?php require_once __DIR__ . "/../menu/menu.php"; ?>

    <h1>ConectaTI</h1>

    <h2>Profissionais 👩‍💻</h2>

    <p>
        Olá, <?php echo htmlspecialchars($nome); ?>!
    </p>

    <p>
        Conheça mulheres que atuam em diferentes áreas
        da Tecnologia da Informação.
    </p>

    <p>
        Aqui mostramos profissionais relacionadas às
        áreas de TI que você escolheu.
    </p>

    <hr>

    <h3>Profissionais para você</h3>

    <?php if (empty($profissionais)): ?>

        <p>
            Ainda não encontramos profissionais relacionadas
            às suas áreas de interesse.
        </p>

    <?php else: ?>

        <?php foreach ($profissionais as $profissional): ?>

            <article>

                <h4>
                    <?php echo htmlspecialchars(
                        $profissional["nome"]
                    ); ?>
                </h4>

                <?php if (!empty($profissional["cargo"])): ?>

                    <p>
                        <strong>Cargo:</strong>

                        <?php echo htmlspecialchars(
                            $profissional["cargo"]
                        ); ?>
                    </p>

                <?php endif; ?>

                <?php if (!empty($profissional["empresa"])): ?>

                    <p>
                        <strong>Empresa:</strong>

                        <?php echo htmlspecialchars(
                            $profissional["empresa"]
                        ); ?>
                    </p>

                <?php endif; ?>

                <?php if (!empty($profissional["biografia"])): ?>

                    <p>
                        <?php echo htmlspecialchars(
                            $profissional["biografia"]
                        ); ?>
                    </p>

                <?php endif; ?>

                <p>

                    <a
                        href="/ProjetoConectaTI/ConectaTI/views/profissionais/perfil.php?id=<?php echo $profissional["id_profissional"]; ?>"
                    >
                        👩‍💻 Ver perfil
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