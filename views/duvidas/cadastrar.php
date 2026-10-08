<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../controllers/DuvidaController.php";

$mensagem = "";


// ============================================
// SELEÇÃO RECEBIDA PELA URL
// ============================================

$id_profissional = isset($_GET["id_profissional"])
    ? intval($_GET["id_profissional"])
    : 0;

$id_empresa = isset($_GET["id_empresa"])
    ? intval($_GET["id_empresa"])
    : 0;


// ============================================
// BUSCAR ÁREAS
// ============================================

$sql = "SELECT
            id_area,
            nome
        FROM area_ti
        WHERE status = 'ATIVA'
        ORDER BY nome";

$resultado = $conexao->query($sql);

$areas = [];

if ($resultado) {
    while ($area = $resultado->fetch_assoc()) {
        $areas[] = $area;
    }
}


// ============================================
// BUSCAR PROFISSIONAIS
// ============================================

$sql = "SELECT
            p.id_profissional,
            u.nome,
            p.cargo,
            p.empresa
        FROM profissional p
        INNER JOIN usuario u
            ON p.id_usuario = u.id_usuario
        WHERE p.status = 'ATIVO'
        AND u.status = 'ATIVO'
        ORDER BY u.nome";

$resultado = $conexao->query($sql);

$profissionais = [];

if ($resultado) {
    while ($profissional = $resultado->fetch_assoc()) {
        $profissionais[] = $profissional;
    }
}


// ============================================
// BUSCAR EMPRESAS
// ============================================

$sql = "SELECT
            id_empresa,
            nome_fantasia
        FROM empresa
        WHERE status = 'ATIVA'
        ORDER BY nome_fantasia";

$resultado = $conexao->query($sql);

$empresas = [];

if ($resultado) {
    while ($empresa = $resultado->fetch_assoc()) {
        $empresas[] = $empresa;
    }
}


// ============================================
// CADASTRAR DÚVIDA
// ============================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_usuario = $_SESSION["id_usuario"];

    $id_area = isset($_POST["id_area"])
        ? intval($_POST["id_area"])
        : 0;

    $titulo = isset($_POST["titulo"])
        ? trim($_POST["titulo"])
        : "";

    $pergunta = isset($_POST["pergunta"])
        ? trim($_POST["pergunta"])
        : "";

    $id_profissional = isset($_POST["id_profissional"])
        ? intval($_POST["id_profissional"])
        : 0;

    $id_empresa = isset($_POST["id_empresa"])
        ? intval($_POST["id_empresa"])
        : 0;


    // ============================================
    // GARANTIR APENAS UM RESPONSÁVEL
    // ============================================

    if ($id_empresa > 0) {

        // Se escolheu empresa,
        // não será direcionada para profissional.

        $id_profissional = 0;

    } elseif ($id_profissional > 0) {

        // Se escolheu profissional,
        // não será direcionada para empresa.

        $id_empresa = 0;

    } else {

        $mensagem = "Selecione uma profissional ou uma empresa.";

    }


    // ============================================
    // CADASTRAR
    // ============================================

    if ($mensagem === "") {

        $controller = new DuvidaController();

        $resultadoCadastro = $controller->cadastrar(
            $id_usuario,
            $id_area,
            $titulo,
            $pergunta,
            $id_profissional,
            $id_empresa
        );


        if ($resultadoCadastro) {

            $mensagem = "Dúvida cadastrada com sucesso!";

            $id_profissional = 0;
            $id_empresa = 0;

        } else {

            $mensagem = "Erro ao cadastrar a dúvida.";

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

    <title>Cadastrar Dúvida - ConectaTI</title>

</head>

<body>


<?php require_once __DIR__ . "/../menu/menu.php"; ?>


<h1>ConectaTI</h1>

<h2>Cadastrar Dúvida 💬</h2>


<?php if ($id_profissional > 0): ?>

    <p>
        Sua dúvida será direcionada para a profissional selecionada.
    </p>

<?php elseif ($id_empresa > 0): ?>

    <p>
        Sua dúvida será direcionada para a empresa selecionada.
    </p>

<?php else: ?>

    <p>
        Aqui você pode fazer uma pergunta e receber ajuda
        de mulheres profissionais ou empresas que atuam
        na área de Tecnologia da Informação.
    </p>

<?php endif; ?>


<?php if ($mensagem !== ""): ?>

    <p>
        <strong>
            <?= htmlspecialchars($mensagem) ?>
        </strong>
    </p>

<?php endif; ?>


<hr>


<form method="POST">


    <!-- ========================================= -->
    <!-- PROFISSIONAL -->
    <!-- ========================================= -->

    <label>
        Profissional:
    </label>

    <br>

    <select
        name="id_profissional"
        id="id_profissional"
    >

        <option value="0">
            Nenhuma profissional selecionada
        </option>

        <?php foreach ($profissionais as $profissional): ?>

            <option
                value="<?= $profissional["id_profissional"] ?>"
                <?= (
                    $id_profissional ==
                    $profissional["id_profissional"]
                ) ? "selected" : "" ?>
            >

                <?= htmlspecialchars($profissional["nome"]) ?>

                -
                <?= htmlspecialchars($profissional["cargo"]) ?>

            </option>

        <?php endforeach; ?>

    </select>


    <br><br>


    <!-- ========================================= -->
    <!-- EMPRESA -->
    <!-- ========================================= -->

    <label>
        Empresa:
    </label>

    <br>

    <select
        name="id_empresa"
        id="id_empresa"
    >

        <option value="0">
            Nenhuma empresa selecionada
        </option>

        <?php foreach ($empresas as $empresa): ?>

            <option
                value="<?= $empresa["id_empresa"] ?>"
                <?= (
                    $id_empresa ==
                    $empresa["id_empresa"]
                ) ? "selected" : "" ?>
            >

                <?= htmlspecialchars($empresa["nome_fantasia"]) ?>

            </option>

        <?php endforeach; ?>

    </select>


    <br><br>


    <!-- ========================================= -->
    <!-- ÁREA -->
    <!-- ========================================= -->

    <label>
        Área da Tecnologia:
    </label>

    <br>

    <select
        name="id_area"
        required
    >

        <option value="">
            Selecione uma área
        </option>

        <?php foreach ($areas as $area): ?>

            <option value="<?= $area["id_area"] ?>">

                <?= htmlspecialchars($area["nome"]) ?>

            </option>

        <?php endforeach; ?>

    </select>


    <br><br>


    <!-- ========================================= -->
    <!-- TÍTULO -->
    <!-- ========================================= -->

    <label>
        Título da dúvida:
    </label>

    <br>

    <input
        type="text"
        name="titulo"
        required
        maxlength="200"
        placeholder="Digite um título para sua dúvida"
    >


    <br><br>


    <!-- ========================================= -->
    <!-- PERGUNTA -->
    <!-- ========================================= -->

    <label>
        Sua dúvida:
    </label>

    <br>

    <textarea
        name="pergunta"
        rows="8"
        cols="60"
        required
        placeholder="Explique sua dúvida com o máximo de detalhes possível"
    ></textarea>


    <br><br>


    <button type="submit">
        Publicar dúvida
    </button>


</form>


<hr>


<p>

    <a href="/ProjetoConectaTI/ConectaTI/views/duvidas/duvidas.php">
        ← Voltar para minhas dúvidas
    </a>

</p>


<p>

    <a href="/ProjetoConectaTI/ConectaTI/views/inicio/inicio.php">
        ← Voltar para o início
    </a>

</p>


</body>

</html>