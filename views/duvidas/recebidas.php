<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../controllers/DuvidaController.php";

$id_usuario = $_SESSION["id_usuario"];
$nome = $_SESSION["nome"];
$tipo = $_SESSION["tipo"];

$controller = new DuvidaController();

$duvidas = [];

$tipo_responsavel = "";


// ============================================
// PROFISSIONAL
// ============================================

if ($tipo === "PROFISSIONAL") {

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

    if (!$profissional) {

        echo "<h2>Acesso não permitido</h2>";

        echo "<p>
                Esta página é destinada às profissionais cadastradas.
              </p>";

        exit;
    }

    $id_profissional = $profissional["id_profissional"];

    $duvidas = $controller->listarDuvidasDaProfissional(
        $id_profissional
    );

    $tipo_responsavel = "PROFISSIONAL";
}


// ============================================
// EMPRESA
// ============================================

elseif ($tipo === "EMPRESA") {

    /*
     * Busca a empresa diretamente pelo usuário logado.
     */

    $sql = "SELECT
                id_empresa,
                nome_fantasia
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


    if (!$empresa) {

        echo "<h2>Cadastro da empresa não encontrado</h2>";

        echo "<p>
                É necessário cadastrar os dados da empresa
                antes de receber dúvidas.
              </p>";

        echo "<p>
                <a href='/ProjetoConectaTI/ConectaTI/views/empresa/cadastro.php'>
                    Cadastrar empresa
                </a>
              </p>";

        exit;
    }


    /*
     * Pegamos o ID real da empresa.
     */

    $id_empresa = $empresa["id_empresa"];


    /*
     * Busca as dúvidas destinadas a essa empresa.
     */

    $duvidas = $controller->listarDuvidasDaEmpresa(
        $id_empresa
    );

    $tipo_responsavel = "EMPRESA";
}


// ============================================
// OUTROS TIPOS
// ============================================

else {

    echo "<h2>Acesso não permitido</h2>";

    echo "<p>
            Apenas profissionais e empresas podem
            acessar as dúvidas recebidas.
          </p>";

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

    <title>Dúvidas Recebidas - ConectaTI</title>

</head>


<body>


<?php

require_once __DIR__ . "/../menu/menu.php";

?>


<h1>ConectaTI</h1>


<h2>Dúvidas Recebidas 💬</h2>


<p>

    Olá,
    <?php echo htmlspecialchars($nome); ?>!

</p>


<?php if ($tipo_responsavel === "PROFISSIONAL"): ?>

    <p>

        Aqui estão as dúvidas enviadas pelas usuárias
        diretamente para você.

    </p>

<?php elseif ($tipo_responsavel === "EMPRESA"): ?>

    <p>

        Aqui estão as dúvidas enviadas pelas usuárias
        para sua empresa.

    </p>

<?php endif; ?>


<hr>


<?php if (empty($duvidas)): ?>

    <p>

        Você ainda não recebeu nenhuma dúvida.

    </p>


<?php else: ?>


    <?php foreach ($duvidas as $duvida): ?>


        <article>


            <h3>

                <?php echo htmlspecialchars(
                    $duvida["titulo"]
                ); ?>

            </h3>


            <p>

                <strong>Usuária:</strong>

                <?php echo htmlspecialchars(
                    $duvida["usuaria"]
                ); ?>

            </p>


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


            <p>

                <strong>Status:</strong>

                <?php echo htmlspecialchars(
                    $duvida["status"]
                ); ?>

            </p>


            <p>

                <strong>Data:</strong>

                <?php echo htmlspecialchars(
                    $duvida["data_criacao"]
                ); ?>

            </p>


            <p>

                <a
                    href="/ProjetoConectaTI/ConectaTI/views/duvidas/visualizar.php?id=<?php echo $duvida["id_duvida"]; ?>"
                >

                    💬 Ver pergunta e responder

                </a>

            </p>


            <hr>


        </article>


    <?php endforeach; ?>


<?php endif; ?>


<p>

    <a
        href="/ProjetoConectaTI/ConectaTI/views/inicio/inicio.php"
    >

        ← Voltar para o início

    </a>

</p>


</body>

</html>