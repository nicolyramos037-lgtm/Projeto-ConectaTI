<?php

session_start();

if (
    !isset($_SESSION["id_usuario"]) ||
    !isset($_SESSION["tipo"]) ||
    $_SESSION["tipo"] !== "ADMINISTRADORA"
) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/AdminController.php";

$adminController = new AdminController();


// ==============================
// ALTERAÇÃO DE STATUS
// ==============================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_usuario = intval($_POST["id_usuario"] ?? 0);
    $id_empresa = intval($_POST["id_empresa"] ?? 0);
    $id_conteudo = intval($_POST["id_conteudo"] ?? 0);

    $status = $_POST["status"] ?? "";


    // Usuário
    if ($id_usuario > 0) {

        $adminController->alterarStatusUsuario(
            $id_usuario,
            $status
        );
    }


    // Empresa
    if ($id_empresa > 0) {

        $adminController->alterarStatusEmpresa(
            $id_empresa,
            $status
        );
    }


    // Conteúdo
    if ($id_conteudo > 0) {

        $adminController->alterarStatusConteudo(
            $id_conteudo,
            $status
        );
    }


    header(
        "Location: /ProjetoConectaTI/ConectaTI/views/admin/dashboard.php"
    );

    exit;
}


// ==============================
// DADOS DO PAINEL
// ==============================

$usuarios = $adminController->listarUsuarios();

$empresas = $adminController->listarEmpresas();

$conteudos = $adminController->listarConteudos();

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Painel Administrativo - ConectaTI</title>

</head>

<body>


<?php

require_once __DIR__ . "/../menu/menu.php";

?>


<h1>Painel Administrativo</h1>

<p>
    Bem-vinda,
    <strong>
        <?= htmlspecialchars($_SESSION["nome"] ?? "Administradora"); ?>
    </strong>!
</p>

<hr>


<!-- ============================== -->
<!-- RESUMO -->
<!-- ============================== -->

<h2>Resumo</h2>

<p>
    <strong>Usuários cadastrados:</strong>
    <?= count($usuarios); ?>
</p>

<p>
    <strong>Empresas cadastradas:</strong>
    <?= count($empresas); ?>
</p>

<p>
    <strong>Conteúdos cadastrados:</strong>
    <?= count($conteudos); ?>
</p>

<hr>


<!-- ============================== -->
<!-- USUÁRIOS -->
<!-- ============================== -->

<h2>Usuários</h2>

<table border="1" cellpadding="8">

    <tr>

        <th>ID</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Tipo</th>
        <th>Status</th>
        <th>Ação</th>

    </tr>


    <?php foreach ($usuarios as $usuario): ?>

        <tr>

            <td>
                <?= htmlspecialchars($usuario["id_usuario"]); ?>
            </td>

            <td>
                <?= htmlspecialchars($usuario["nome"]); ?>
            </td>

            <td>
                <?= htmlspecialchars($usuario["email"]); ?>
            </td>

            <td>
                <?= htmlspecialchars($usuario["tipo"]); ?>
            </td>

            <td>
                <?= htmlspecialchars($usuario["status"]); ?>
            </td>

            <td>

                <?php if (
                    $usuario["id_usuario"]
                    == $_SESSION["id_usuario"]
                ): ?>

                    Conta atual

                <?php else: ?>

                    <form method="POST">

                        <input
                            type="hidden"
                            name="id_usuario"
                            value="<?= $usuario["id_usuario"]; ?>"
                        >

                        <?php if ($usuario["status"] === "ATIVO"): ?>

                            <input
                                type="hidden"
                                name="status"
                                value="INATIVO"
                            >

                            <button
                                type="submit"
                                onclick="return confirm('Deseja inativar este usuário?');"
                            >
                                Inativar
                            </button>

                        <?php else: ?>

                            <input
                                type="hidden"
                                name="status"
                                value="ATIVO"
                            >

                            <button
                                type="submit"
                                onclick="return confirm('Deseja ativar este usuário?');"
                            >
                                Ativar
                            </button>

                        <?php endif; ?>

                    </form>

                <?php endif; ?>

            </td>

        </tr>

    <?php endforeach; ?>

</table>


<hr>


<!-- ============================== -->
<!-- EMPRESAS -->
<!-- ============================== -->

<h2>Empresas</h2>

<table border="1" cellpadding="8">

    <tr>

        <th>ID</th>
        <th>Nome</th>
        <th>CNPJ</th>
        <th>E-mail</th>
        <th>Status</th>
        <th>Ação</th>

    </tr>


    <?php foreach ($empresas as $empresa): ?>

        <tr>

            <td>
                <?= htmlspecialchars($empresa["id_empresa"]); ?>
            </td>

            <td>
                <?= htmlspecialchars($empresa["nome_fantasia"]); ?>
            </td>

            <td>
                <?= htmlspecialchars($empresa["cnpj"] ?? ""); ?>
            </td>

            <td>
                <?= htmlspecialchars($empresa["email_contato"] ?? ""); ?>
            </td>

            <td>
                <?= htmlspecialchars($empresa["status"]); ?>
            </td>

            <td>

                <form method="POST">

                    <input
                        type="hidden"
                        name="id_empresa"
                        value="<?= $empresa["id_empresa"]; ?>"
                    >

                    <?php if ($empresa["status"] === "ATIVA"): ?>

                        <input
                            type="hidden"
                            name="status"
                            value="INATIVA"
                        >

                        <button
                            type="submit"
                            onclick="return confirm('Deseja inativar esta empresa?');"
                        >
                            Inativar
                        </button>

                    <?php else: ?>

                        <input
                            type="hidden"
                            name="status"
                            value="ATIVA"
                        >

                        <button
                            type="submit"
                            onclick="return confirm('Deseja ativar esta empresa?');"
                        >
                            Ativar
                        </button>

                    <?php endif; ?>

                </form>

            </td>

        </tr>

    <?php endforeach; ?>

</table>


<hr>


<!-- ============================== -->
<!-- CONTEÚDOS -->
<!-- ============================== -->

<h2>Conteúdos</h2>

<table border="1" cellpadding="8">

    <tr>

        <th>ID</th>
        <th>Título</th>
        <th>Tipo</th>
        <th>Status</th>
        <th>Data de publicação</th>
        <th>Ação</th>

    </tr>


    <?php foreach ($conteudos as $conteudo): ?>

        <tr>

            <td>
                <?= htmlspecialchars($conteudo["id_conteudo"]); ?>
            </td>

            <td>
                <?= htmlspecialchars($conteudo["titulo"]); ?>
            </td>

            <td>
                <?= htmlspecialchars($conteudo["tipo"]); ?>
            </td>

            <td>
                <?= htmlspecialchars($conteudo["status"]); ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $conteudo["data_publicacao"] ?? ""
                ); ?>
            </td>

            <td>

                <?php if ($conteudo["status"] === "PUBLICADO"): ?>

                    <form method="POST">

                        <input
                            type="hidden"
                            name="id_conteudo"
                            value="<?= $conteudo["id_conteudo"]; ?>"
                        >

                        <input
                            type="hidden"
                            name="status"
                            value="INATIVO"
                        >

                        <button
                            type="submit"
                            onclick="return confirm('Deseja inativar este conteúdo?');"
                        >
                            Inativar
                        </button>

                    </form>


                <?php elseif ($conteudo["status"] === "INATIVO"): ?>

                    <form method="POST">

                        <input
                            type="hidden"
                            name="id_conteudo"
                            value="<?= $conteudo["id_conteudo"]; ?>"
                        >

                        <input
                            type="hidden"
                            name="status"
                            value="PUBLICADO"
                        >

                        <button
                            type="submit"
                            onclick="return confirm('Deseja publicar este conteúdo novamente?');"
                        >
                            Publicar
                        </button>

                    </form>


                <?php else: ?>

                    <form method="POST">

                        <input
                            type="hidden"
                            name="id_conteudo"
                            value="<?= $conteudo["id_conteudo"]; ?>"
                        >

                        <input
                            type="hidden"
                            name="status"
                            value="PUBLICADO"
                        >

                        <button
                            type="submit"
                            onclick="return confirm('Deseja publicar este conteúdo?');"
                        >
                            Publicar
                        </button>

                    </form>

                <?php endif; ?>

            </td>

        </tr>

    <?php endforeach; ?>

</table>


</body>

</html>