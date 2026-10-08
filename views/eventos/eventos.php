<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/EventoController.php";
require_once __DIR__ . "/../../controllers/FavoritoController.php";

$id_usuario = $_SESSION["id_usuario"];
$nome = $_SESSION["nome"];

$eventoController = new EventoController();
$favoritoController = new FavoritoController();

$eventos = $eventoController->listarEventosPorUsuario(
    $id_usuario
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_evento = intval($_POST["id_evento"]);

    $favoritoController->adicionarEvento(
        $id_usuario,
        $id_evento
    );

    header("Location: /ProjetoConectaTI/ConectaTI/views/eventos/eventos.php");
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

<title>Eventos - ConectaTI</title>

</head>

<body>

<?php require_once __DIR__ . "/../menu/menu.php"; ?>

<h1>ConectaTI</h1>

<h2>Eventos 💜</h2>

<p>
Olá, <?php echo htmlspecialchars($nome); ?>!
</p>

<p>
Confira eventos relacionados às áreas de TI
que você escolheu.
</p>

<hr>

<h3>Eventos para você</h3>

<?php if (empty($eventos)): ?>

<p>
Ainda não encontramos eventos relacionados
às suas áreas de interesse.
</p>

<?php else: ?>

<?php foreach ($eventos as $evento): ?>

<article>

<h4>
<?php echo htmlspecialchars($evento["titulo"]); ?>
</h4>

<p>
<?php echo htmlspecialchars($evento["descricao"]); ?>
</p>

<p>
<strong>Organizador:</strong>
<?php echo htmlspecialchars($evento["organizador"]); ?>
</p>

<?php if (!empty($evento["local_evento"])): ?>

<p>
<strong>Local:</strong>
<?php echo htmlspecialchars($evento["local_evento"]); ?>
</p>

<?php endif; ?>

<p>
<strong>Modalidade:</strong>
<?php echo htmlspecialchars($evento["modalidade"]); ?>
</p>

<p>
<strong>Data:</strong>
<?php echo date(
    "d/m/Y H:i",
    strtotime($evento["data_inicio"])
); ?>
</p>

<?php if (!empty($evento["data_fim"])): ?>

<p>
<strong>Final:</strong>
<?php echo date(
    "d/m/Y H:i",
    strtotime($evento["data_fim"])
); ?>
</p>

<?php endif; ?>

<?php if ($evento["gratuito"] == 1): ?>

<p>
<strong>Evento gratuito</strong>
</p>

<?php else: ?>

<p>
Evento pago
</p>

<?php endif; ?>

<?php if (!empty($evento["link"])): ?>

<p>

<a
href="<?php echo htmlspecialchars($evento["link"]); ?>"
target="_blank"
>
Acessar evento
</a>

</p>

<?php endif; ?>


<form method="POST">

<input
type="hidden"
name="id_evento"
value="<?php echo $evento["id_evento"]; ?>"
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