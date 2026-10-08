<?php

session_start();

if (
    !isset($_SESSION["id_usuario"])
    || !isset($_SESSION["tipo"])
    || $_SESSION["tipo"] !== "EMPRESA"
) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/EmpresaController.php";
require_once __DIR__ . "/../../controllers/OportunidadeController.php";
require_once __DIR__ . "/../../config/conexao.php";

$id_usuario = $_SESSION["id_usuario"];

$empresaController = new EmpresaController();
$oportunidadeController = new OportunidadeController();

$empresa = $empresaController->buscarPorUsuario($id_usuario);

if ($empresa === null) {
    die("É necessário cadastrar o perfil da empresa antes de cadastrar uma oportunidade.");
}

/*
 * Buscar áreas de TI disponíveis
 */
$sqlAreas = "SELECT id_area, nome
             FROM area_ti
             WHERE status = 'ATIVA'
             ORDER BY nome ASC";

$resultadoAreas = $conexao->query($sqlAreas);

$areas = [];

if ($resultadoAreas) {
    while ($area = $resultadoAreas->fetch_assoc()) {
        $areas[] = $area;
    }
}

$mensagem = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titulo = trim($_POST["titulo"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $id_area = intval($_POST["id_area"] ?? 0);
    $tipo = $_POST["tipo"] ?? "";
    $modalidade = $_POST["modalidade"] ?? "";
    $local_oportunidade = trim($_POST["local_oportunidade"] ?? "");
    $link = trim($_POST["link"] ?? "");
    $data_limite = $_POST["data_limite"] ?? "";

    if (
        $titulo === ""
        || $descricao === ""
        || $id_area <= 0
        || $tipo === ""
        || $modalidade === ""
    ) {

        $erro = "Preencha todos os campos obrigatórios.";

    } else {

        $dados = [
            "id_empresa" => $empresa["id_empresa"],
            "titulo" => $titulo,
            "descricao" => $descricao,
            "empresa" => $empresa["nome_fantasia"],
            "id_area" => $id_area,
            "tipo" => $tipo,
            "modalidade" => $modalidade,
            "local_oportunidade" => $local_oportunidade !== ""
                ? $local_oportunidade
                : null,
            "link" => $link !== ""
                ? $link
                : null,
            "data_limite" => $data_limite !== ""
                ? $data_limite
                : null
        ];

        if ($oportunidadeController->cadastrar($dados)) {

            $mensagem = "Oportunidade cadastrada com sucesso!";

            $titulo = "";
            $descricao = "";
            $id_area = 0;
            $tipo = "";
            $modalidade = "";
            $local_oportunidade = "";
            $link = "";
            $data_limite = "";

        } else {

            $erro = "Não foi possível cadastrar a oportunidade.";

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

    <title>Cadastrar oportunidade - ConectaTI</title>

</head>

<body>

    <?php require_once __DIR__ . "/../menu/menu.php"; ?>

    <h1>ConectaTI</h1>

    <h2>Cadastrar oportunidade</h2>

    <p>
        Empresa:
        <strong>
            <?php echo htmlspecialchars($empresa["nome_fantasia"]); ?>
        </strong>
    </p>

    <?php if ($mensagem !== ""): ?>

        <p>
            <?php echo htmlspecialchars($mensagem); ?>
        </p>

    <?php endif; ?>

    <?php if ($erro !== ""): ?>

        <p>
            <?php echo htmlspecialchars($erro); ?>
        </p>

    <?php endif; ?>

    <form method="POST">

        <p>

            <label for="titulo">
                Título da oportunidade:
            </label>

            <br>

            <input
                type="text"
                id="titulo"
                name="titulo"
                value="<?php echo htmlspecialchars($titulo ?? ""); ?>"
                required
            >

        </p>

        <p>

            <label for="descricao">
                Descrição:
            </label>

            <br>

            <textarea
                id="descricao"
                name="descricao"
                rows="6"
                required
            ><?php echo htmlspecialchars($descricao ?? ""); ?></textarea>

        </p>

        <p>

            <label for="id_area">
                Área de TI:
            </label>

            <br>

            <select
                id="id_area"
                name="id_area"
                required
            >

                <option value="">
                    Selecione uma área
                </option>

                <?php foreach ($areas as $area): ?>

                    <option
                        value="<?php echo $area["id_area"]; ?>"
                        <?php echo (
                            ($id_area ?? 0) == $area["id_area"]
                        ) ? "selected" : ""; ?>
                    >

                        <?php echo htmlspecialchars($area["nome"]); ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </p>

        <p>

            <label for="tipo">
                Tipo:
            </label>

            <br>

            <select
                id="tipo"
                name="tipo"
                required
            >

                <option value="">
                    Selecione
                </option>

                <option
                    value="EMPREGO"
                    <?php echo (($tipo ?? "") === "EMPREGO") ? "selected" : ""; ?>
                >
                    Emprego
                </option>

                <option
                    value="ESTAGIO"
                    <?php echo (($tipo ?? "") === "ESTAGIO") ? "selected" : ""; ?>
                >
                    Estágio
                </option>

                <option
                    value="APRENDIZ"
                    <?php echo (($tipo ?? "") === "APRENDIZ") ? "selected" : ""; ?>
                >
                    Aprendiz
                </option>

                <option
                    value="FREELANCE"
                    <?php echo (($tipo ?? "") === "FREELANCE") ? "selected" : ""; ?>
                >
                    Freelance
                </option>

                <option
                    value="VOLUNTARIADO"
                    <?php echo (($tipo ?? "") === "VOLUNTARIADO") ? "selected" : ""; ?>
                >
                    Voluntariado
                </option>

                <option
                    value="OUTRA"
                    <?php echo (($tipo ?? "") === "OUTRA") ? "selected" : ""; ?>
                >
                    Outra
                </option>

            </select>

        </p>

        <p>

            <label for="modalidade">
                Modalidade:
            </label>

            <br>

            <select
                id="modalidade"
                name="modalidade"
                required
            >

                <option value="">
                    Selecione
                </option>

                <option
                    value="REMOTO"
                    <?php echo (($modalidade ?? "") === "REMOTO") ? "selected" : ""; ?>
                >
                    Remoto
                </option>

                <option
                    value="PRESENCIAL"
                    <?php echo (($modalidade ?? "") === "PRESENCIAL") ? "selected" : ""; ?>
                >
                    Presencial
                </option>

                <option
                    value="HIBRIDO"
                    <?php echo (($modalidade ?? "") === "HIBRIDO") ? "selected" : ""; ?>
                >
                    Híbrido
                </option>

            </select>

        </p>

        <p>

            <label for="local_oportunidade">
                Local:
            </label>

            <br>

            <input
                type="text"
                id="local_oportunidade"
                name="local_oportunidade"
                value="<?php echo htmlspecialchars($local_oportunidade ?? ""); ?>"
            >

        </p>

        <p>

            <label for="link">
                Link para candidatura:
            </label>

            <br>

            <input
                type="url"
                id="link"
                name="link"
                value="<?php echo htmlspecialchars($link ?? ""); ?>"
            >

        </p>

        <p>

            <label for="data_limite">
                Data limite:
            </label>

            <br>

            <input
                type="date"
                id="data_limite"
                name="data_limite"
                value="<?php echo htmlspecialchars($data_limite ?? ""); ?>"
            >

        </p>

        <button type="submit">
            Cadastrar oportunidade
        </button>

    </form>

    <p>

        <a href="/ProjetoConectaTI/ConectaTI/views/oportunidades/oportunidades.php">
            ← Voltar para oportunidades
        </a>

    </p>

</body>

</html>