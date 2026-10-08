<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login/login.php");
    exit;
}

if ($_SESSION["tipo"] !== "ADMINISTRADORA") {
    echo "Acesso permitido somente para administradoras.";
    exit;
}

$nome = $_SESSION["nome"];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administração - ConectaTI</title>

</head>

<body>

    <h1>ConectaTI</h1>

    <h2>Área Administrativa</h2>

    <p>
        Olá, <?php echo htmlspecialchars($nome); ?>!
    </p>

    <p>
        Bem-vinda à área de administração do ConectaTI.
    </p>

    <hr>

    <h3>Gerenciamento</h3>

    <p>
        <a href="usuarios.php">
            👥 Usuários
        </a>
    </p>

    <p>
        <a href="conteudos.php">
            📚 Conteúdos
        </a>
    </p>

    <p>
        <a href="cursos.php">
            🎓 Cursos
        </a>
    </p>

    <p>
        <a href="eventos.php">
            📅 Eventos
        </a>
    </p>

    <p>
        <a href="oportunidades.php">
            💼 Oportunidades
        </a>
    </p>

    <hr>

    <p>
        <a href="../inicio/inicio.php">
            ← Voltar para o início
        </a>
    </p>

</body>

</html>