<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/CursoController.php";
require_once __DIR__ . "/../../controllers/FavoritoController.php";

$id_usuario = $_SESSION["id_usuario"];
$nome = $_SESSION["nome"];

$cursoController = new CursoController();
$favoritoController = new FavoritoController();

$cursos = $cursoController->listarCursosPorUsuario(
    $id_usuario
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_curso = intval($_POST["id_curso"]);

    $favoritoController->adicionarCurso(
        $id_usuario,
        $id_curso
    );

    header("Location: /ProjetoConectaTI/ConectaTI/views/cursos/cursos.php");
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

<title>Cursos - ConectaTI</title>

</head>

<body>

<?php require_once __DIR__ . "/../menu/menu.php"; ?>

<h1>ConectaTI</h1>

<h2>Cursos 📚</h2>

<p>
Olá, <?php echo htmlspecialchars($nome); ?>!
</p>

<p>
Aqui estão cursos relacionados às áreas de TI
que você escolheu no seu perfil.
</p>

<hr>

<h3>Cursos para você</h3>

<?php if (empty($cursos)): ?>

<p>
Ainda não encontramos cursos relacionados
às suas áreas de interesse.
</p>

<p>
Você pode atualizar suas áreas de interesse
no seu perfil futuramente.
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
<strong>Instituição:</strong>
<?php echo htmlspecialchars($curso["instituicao"]); ?>
</p>

<p>
<strong>Modalidade:</strong>
<?php echo htmlspecialchars($curso["modalidade"]); ?>
</p>

<p>
<strong>Nível:</strong>
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

<?php if (!empty($curso["data_inicio"])): ?>

<p>
<strong>Início:</strong>
<?php echo htmlspecialchars($curso["data_inicio"]); ?>
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


<form method="POST">

<input
type="hidden"
name="id_curso"
value="<?php echo $curso["id_curso"]; ?>"
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