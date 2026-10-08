<?php

session_start();

require_once __DIR__ . "/../../controllers/EmpresaController.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

if (
    !isset($_SESSION["tipo"])
    || $_SESSION["tipo"] !== "EMPRESA"
) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/inicio/inicio.php");
    exit;
}

$controller = new EmpresaController();
$empresaExistente = $controller->buscarPorUsuario(
    $_SESSION["id_usuario"]
);

if ($empresaExistente !== null) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/empresa/perfil.php");
    exit;
}

$mensagem = "";
$dados = [
    "nome_fantasia" => "",
    "razao_social" => "",
    "cnpj" => "",
    "descricao" => "",
    "telefone" => "",
    "whatsapp" => "",
    "email_contato" => "",
    "site" => ""
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($dados as $campo => $valor) {
        if (isset($_POST[$campo]) && is_string($_POST[$campo])) {
            $dados[$campo] = trim($_POST[$campo]);
        }
    }

    $resultado = $controller->cadastrar(
        $_SESSION["id_usuario"],
        $dados["nome_fantasia"],
        $dados["razao_social"],
        $dados["cnpj"],
        $dados["descricao"],
        $dados["telefone"],
        $dados["whatsapp"],
        $dados["email_contato"],
        $dados["site"]
    );

    if ($resultado) {
        header("Location: /ProjetoConectaTI/ConectaTI/views/empresa/perfil.php");
        exit;
    }

    $mensagem = "Não foi possível cadastrar a empresa. Verifique os dados informados.";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro da Empresa - ConectaTI</title>
</head>

<body>

<?php require_once __DIR__ . "/../menu/menu.php"; ?>

<h1>Cadastro da Empresa</h1>

<p>Complete as informações da sua empresa.</p>

<?php if ($mensagem !== ""): ?>
    <p><strong><?php echo htmlspecialchars($mensagem); ?></strong></p>
<?php endif; ?>

<form method="POST">
    <label for="nome_fantasia">Nome fantasia:</label>
    <br>
    <input
        type="text"
        name="nome_fantasia"
        id="nome_fantasia"
        value="<?php echo htmlspecialchars($dados["nome_fantasia"]); ?>"
        required
    >
    <br><br>

    <label for="razao_social">Razão social:</label>
    <br>
    <input
        type="text"
        name="razao_social"
        id="razao_social"
        value="<?php echo htmlspecialchars($dados["razao_social"]); ?>"
        required
    >
    <br><br>

    <label for="cnpj">CNPJ:</label>
    <br>
    <input
        type="text"
        name="cnpj"
        id="cnpj"
        value="<?php echo htmlspecialchars($dados["cnpj"]); ?>"
        required
    >
    <br><br>

    <label for="descricao">Descrição da empresa:</label>
    <br>
    <textarea
        name="descricao"
        id="descricao"
        rows="5"
        required
    ><?php echo htmlspecialchars($dados["descricao"]); ?></textarea>
    <br><br>

    <label for="telefone">Telefone:</label>
    <br>
    <input
        type="text"
        name="telefone"
        id="telefone"
        value="<?php echo htmlspecialchars($dados["telefone"]); ?>"
    >
    <br><br>

    <label for="whatsapp">WhatsApp:</label>
    <br>
    <input
        type="text"
        name="whatsapp"
        id="whatsapp"
        value="<?php echo htmlspecialchars($dados["whatsapp"]); ?>"
    >
    <br><br>

    <label for="email_contato">E-mail para contato:</label>
    <br>
    <input
        type="email"
        name="email_contato"
        id="email_contato"
        value="<?php echo htmlspecialchars($dados["email_contato"]); ?>"
        required
    >
    <br><br>

    <label for="site">Site:</label>
    <br>
    <input
        type="url"
        name="site"
        id="site"
        value="<?php echo htmlspecialchars($dados["site"]); ?>"
        placeholder="https://"
    >
    <br><br>

    <button type="submit">Cadastrar empresa</button>
</form>

</body>
</html>
