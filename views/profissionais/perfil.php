<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/ProfissionalController.php";

$nome = $_SESSION["nome"];

if (!isset($_GET["id"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/profissionais/profissionais.php");
    exit;
}

$id_profissional = intval($_GET["id"]);

$profissionalController = new ProfissionalController();

$profissional = $profissionalController->buscarProfissional(
    $id_profissional
);

if ($profissional === null) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/profissionais/profissionais.php");
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

    <title>Perfil da Profissional - ConectaTI</title>

</head>

<body>

    <?php require_once __DIR__ . "/../menu/menu.php"; ?>

    <h1>ConectaTI</h1>

    <h2>Perfil da Profissional 👩‍💻</h2>

    <p>
        Olá, <?php echo htmlspecialchars($nome); ?>!
    </p>

    <hr>

    <h3>
        <?php echo htmlspecialchars($profissional["nome"]); ?>
    </h3>

    <?php if (!empty($profissional["cargo"])): ?>

        <p>
            <strong>Cargo:</strong>

            <?php echo htmlspecialchars($profissional["cargo"]); ?>
        </p>

    <?php endif; ?>

    <?php if (!empty($profissional["empresa"])): ?>

        <p>
            <strong>Empresa:</strong>

            <?php echo htmlspecialchars($profissional["empresa"]); ?>
        </p>

    <?php endif; ?>

    <?php if (!empty($profissional["biografia"])): ?>

        <p>
            <strong>Sobre:</strong>
        </p>

        <p>
            <?php echo htmlspecialchars($profissional["biografia"]); ?>
        </p>

    <?php endif; ?>

    <hr>

    <h3>Contato</h3>

    <?php if (!empty($profissional["whatsapp"])): ?>

        <p>

            <strong>WhatsApp:</strong>

            <a
                href="https://wa.me/<?php echo htmlspecialchars(
                    $profissional["whatsapp"]
                ); ?>"
                target="_blank"
            >
                Entrar em contato
            </a>

        </p>

    <?php else: ?>

        <p>
            WhatsApp não informado.
        </p>

    <?php endif; ?>

    <?php if (!empty($profissional["linkedin"])): ?>

        <p>

            <strong>LinkedIn:</strong>

            <a
                href="<?php echo htmlspecialchars(
                    $profissional["linkedin"]
                ); ?>"
                target="_blank"
            >
                Ver perfil no LinkedIn
            </a>

        </p>

    <?php else: ?>

        <p>
            LinkedIn não informado.
        </p>

    <?php endif; ?>

    <hr>

    <h3>Enviar dúvida 💬</h3>

    <p>
        Tem alguma dúvida sobre a área de atuação
        dessa profissional?
    </p>

    <p>

        <a
            href="/ProjetoConectaTI/ConectaTI/views/duvidas/cadastrar.php?id_profissional=<?php echo $profissional["id_profissional"]; ?>"
        >
            ❓ Enviar uma dúvida para esta profissional
        </a>

    </p>

    <hr>

    <p>

        <a href="/ProjetoConectaTI/ConectaTI/views/profissionais/profissionais.php">
            ← Voltar para profissionais
        </a>

    </p>

    <p>

        <a href="/ProjetoConectaTI/ConectaTI/views/inicio/inicio.php">
            ← Voltar para o início
        </a>

    </p>

</body>

</html>