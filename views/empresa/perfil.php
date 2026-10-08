<?php

session_start();

require_once __DIR__ . "/../../controllers/EmpresaController.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

if (!isset($_SESSION["tipo"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/inicio/inicio.php");
    exit;
}

$controller = new EmpresaController();

if ($_SESSION["tipo"] === "EMPRESA") {
    $empresa = $controller->buscarPorUsuario(
        $_SESSION["id_usuario"]
    );
} elseif ($_SESSION["tipo"] === "ADMINISTRADORA") {
    $id_empresa = filter_input(
        INPUT_GET,
        "id",
        FILTER_VALIDATE_INT
    );

    if ($id_empresa === false || $id_empresa === null) {
        header("Location: /ProjetoConectaTI/ConectaTI/views/inicio/inicio.php");
        exit;
    }

    $empresa = $controller->buscarPorId($id_empresa);
} else {
    header("Location: /ProjetoConectaTI/ConectaTI/views/inicio/inicio.php");
    exit;
}

if ($empresa === null) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/inicio/inicio.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil da Empresa - ConectaTI</title>
</head>

<body>

<?php require_once __DIR__ . "/../menu/menu.php"; ?>

<h1>Perfil da Empresa</h1>

<?php if (!empty($empresa["logo"])): ?>
    <p>
        <img
            src="<?php echo htmlspecialchars($empresa["logo"], ENT_QUOTES, "UTF-8"); ?>"
            alt="Logo de <?php echo htmlspecialchars($empresa["nome_fantasia"], ENT_QUOTES, "UTF-8"); ?>"
        >
    </p>
<?php endif; ?>

<h2><?php echo htmlspecialchars($empresa["nome_fantasia"]); ?></h2>

<p>
    <strong>Razão social:</strong>
    <?php echo htmlspecialchars($empresa["razao_social"]); ?>
</p>

<p>
    <strong>CNPJ:</strong>
    <?php echo htmlspecialchars($empresa["cnpj"]); ?>
</p>

<p>
    <strong>Descrição:</strong><br>
    <?php echo nl2br(htmlspecialchars($empresa["descricao"])); ?>
</p>

<p>
    <strong>Telefone:</strong>
    <?php echo htmlspecialchars($empresa["telefone"] ?? ""); ?>
</p>

<p>
    <strong>WhatsApp:</strong>
    <?php echo htmlspecialchars($empresa["whatsapp"] ?? ""); ?>
</p>

<p>
    <strong>E-mail:</strong>
    <?php echo htmlspecialchars($empresa["email_contato"]); ?>
</p>

<p>
    <strong>Site:</strong>
    <?php if (!empty($empresa["site"])): ?>
        <a
            href="<?php echo htmlspecialchars($empresa["site"], ENT_QUOTES, "UTF-8"); ?>"
            target="_blank"
            rel="noopener noreferrer"
        >
            <?php echo htmlspecialchars($empresa["site"]); ?>
        </a>
    <?php else: ?>
        Não informado
    <?php endif; ?>
</p>

<p>
    <strong>Status:</strong>
    <?php echo htmlspecialchars($empresa["status"]); ?>
</p>

<?php if ($_SESSION["tipo"] === "EMPRESA"): ?>
    <p>
        <a href="/ProjetoConectaTI/ConectaTI/views/empresa/editar.php">
            Editar empresa
        </a>
    </p>
<?php endif; ?>

<p>
    <a href="/ProjetoConectaTI/ConectaTI/views/inicio/inicio.php">
        Voltar para o início
    </a>
</p>

</body>
</html>
