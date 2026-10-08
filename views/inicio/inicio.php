<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../config/conexao.php";
require_once __DIR__ . "/../../controllers/ConteudoController.php";
require_once __DIR__ . "/../../controllers/CursoController.php";

$id_usuario = $_SESSION["id_usuario"];
$nome = $_SESSION["nome"];

/*
|--------------------------------------------------------------------------
| Buscar preferências da usuária
|--------------------------------------------------------------------------
*/

$sqlPreferencia = "SELECT
                        nivel_ti,
                        objetivo
                   FROM preferencia_usuario
                   WHERE id_usuario = ?";

$stmtPreferencia = $conexao->prepare($sqlPreferencia);

$stmtPreferencia->bind_param(
    "i",
    $id_usuario
);

$stmtPreferencia->execute();

$resultadoPreferencia = $stmtPreferencia->get_result();

$preferencia = $resultadoPreferencia->fetch_assoc();

/*
|--------------------------------------------------------------------------
| Buscar áreas escolhidas
|--------------------------------------------------------------------------
*/

$sqlAreas = "SELECT
                a.nome
             FROM usuario_area ua

             INNER JOIN area_ti a
                ON ua.id_area = a.id_area

             WHERE ua.id_usuario = ?

             ORDER BY a.nome";

$stmtAreas = $conexao->prepare($sqlAreas);

$stmtAreas->bind_param(
    "i",
    $id_usuario
);

$stmtAreas->execute();

$resultadoAreas = $stmtAreas->get_result();

$areas = [];

while ($area = $resultadoAreas->fetch_assoc()) {
    $areas[] = $area["nome"];
}

/*
|--------------------------------------------------------------------------
| Buscar conteúdos personalizados
|--------------------------------------------------------------------------
*/

$conteudoController = new ConteudoController();

$conteudos = $conteudoController->listarConteudosPorUsuario(
    $id_usuario
);

/*
|--------------------------------------------------------------------------
| Buscar cursos personalizados
|--------------------------------------------------------------------------
*/

$cursoController = new CursoController();

$cursos = $cursoController->listarCursosPorUsuario(
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

<title>Início - ConectaTI</title>

</head>

<body>

<?php require_once __DIR__ . "/../menu/menu.php"; ?>

<h1>ConectaTI</h1>

<h2>
Olá, <?php echo htmlspecialchars($nome); ?>! 💜
</h2>

<p>
Bem-vinda ao seu espaço no ConectaTI.
</p>

<hr>

<h3>Seu perfil de interesse</h3>

<?php if ($preferencia): ?>

<p>

<strong>
Seu nível em TI:
</strong>

<?php

$nivel = $preferencia["nivel_ti"];

if ($nivel === "INICIANTE") {

    echo "Estou começando agora";

} elseif ($nivel === "BASICO") {

    echo "Já tenho algum conhecimento";

} elseif ($nivel === "ESTUDANTE") {

    echo "Sou estudante de TI";

} elseif ($nivel === "PROFISSIONAL") {

    echo "Já trabalho com TI";

}

?>

</p>

<p>

<strong>
Seu objetivo:
</strong>

<?php

$objetivo = $preferencia["objetivo"];

if ($objetivo === "CONHECER_TI") {

    echo "Conhecer mais sobre TI";

} elseif ($objetivo === "APRENDER") {

    echo "Aprender e estudar";

} elseif ($objetivo === "CONSEGUIR_EMPREGO") {

    echo "Encontrar oportunidades de trabalho";

} elseif ($objetivo === "MELHORAR_CARREIRA") {

    echo "Melhorar minha carreira";

} elseif ($objetivo === "NETWORKING") {

    echo "Conhecer pessoas da área";

}

?>

</p>

<?php endif; ?>

<hr>

<h3>Áreas que interessam você</h3>

<?php if (empty($areas)): ?>

<p>
Você ainda não selecionou nenhuma área.
</p>

<?php else: ?>

<ul>

<?php foreach ($areas as $area): ?>

<li>
<?php echo htmlspecialchars($area); ?>
</li>

<?php endforeach; ?>

</ul>

<?php endif; ?>

<hr>

<h3>Conteúdos para você 💜</h3>

<?php if (empty($conteudos)): ?>

<p>
Ainda não encontramos conteúdos relacionados às
áreas que você escolheu.
</p>

<?php else: ?>

<?php foreach ($conteudos as $conteudo): ?>

<article>

<h4>
<?php echo htmlspecialchars($conteudo["titulo"]); ?>
</h4>

<p>
<?php echo htmlspecialchars($conteudo["descricao"]); ?>
</p>

<p>

<strong>
Tipo:
</strong>

<?php echo htmlspecialchars($conteudo["tipo"]); ?>

</p>

<p>

<a href="/ProjetoConectaTI/ConectaTI/views/conteudos/visualizar.php?id=<?php echo $conteudo["id_conteudo"]; ?>">
Ver conteúdo
</a>

</p>

<hr>

</article>

<?php endforeach; ?>

<?php endif; ?>

<hr>

<h3>Cursos para você 📚</h3>

<?php if (empty($cursos)): ?>

<p>
Ainda não encontramos cursos relacionados às
áreas que você escolheu.
</p>

<?php else: ?>

<?php foreach ($cursos as $curso): ?>

<article>

<h4>
<?php echo htmlspecialchars($curso["titulo"]); ?>
</h4>

<p>
<?php echo htmlspecialchars($curso["descricao"]); ?>
</p>

<p>

<strong>
Instituição:
</strong>

<?php echo htmlspecialchars($curso["instituicao"]); ?>

</p>

<p>

<strong>
Modalidade:
</strong>

<?php echo htmlspecialchars($curso["modalidade"]); ?>

</p>

<p>

<strong>
Nível:
</strong>

<?php echo htmlspecialchars($curso["nivel"]); ?>

</p>

<?php if ($curso["gratuito"] == 1): ?>

<p>
<strong>Gratuito</strong>
</p>

<?php else: ?>

<p>
Curso pago
</p>

<?php endif; ?>

<?php if (!empty($curso["link"])): ?>

<p>

<a
href="<?php echo htmlspecialchars($curso["link"]); ?>"
target="_blank"
>
Acessar curso
</a>

</p>

<?php endif; ?>

<hr>

</article>

<?php endforeach; ?>

<?php endif; ?>

<hr>

<h3>Oportunidades 💼</h3>

<p>
Encontre oportunidades relacionadas às áreas de TI que
interessam você.
</p>

<p>
<a href="/ProjetoConectaTI/ConectaTI/views/oportunidades/oportunidades.php">
Ver oportunidades
</a>
</p>

<hr>

<h3>Profissionais 👩‍💻</h3>

<p>
Conheça profissionais das áreas de TI que você escolheu
e encontre pessoas que podem ajudar na sua jornada.
</p>

<p>
<a href="/ProjetoConectaTI/ConectaTI/views/profissionais/profissionais.php">
Conhecer profissionais
</a>
</p>

<hr>

<h3>Dúvidas 💬</h3>

<p>
Faça perguntas, acompanhe suas dúvidas e veja respostas
de profissionais e empresas.
</p>

<p>
<a href="/ProjetoConectaTI/ConectaTI/views/duvidas/duvidas.php">
Acessar minhas dúvidas
</a>
</p>

<hr>

<h3>Favoritos ⭐</h3>

<p>
Acesse os conteúdos, cursos, eventos e oportunidades
que você salvou.
</p>

<p>
<a href="/ProjetoConectaTI/ConectaTI/views/favoritos/favoritos.php">
Ver meus favoritos
</a>
</p>

<hr>

<p>
<strong>
Seu perfil está personalizado de acordo com
seus interesses.
</strong>
</p>

</body>

</html>