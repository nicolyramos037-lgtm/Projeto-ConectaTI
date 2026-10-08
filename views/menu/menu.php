<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<nav>

    <strong>ConectaTI</strong>

    <a href="/ProjetoConectaTI/ConectaTI/views/inicio/inicio.php">
        Início
    </a>

    <a href="/ProjetoConectaTI/ConectaTI/views/conteudos/conteudos.php">
        Conteúdos
    </a>

    <a href="/ProjetoConectaTI/ConectaTI/views/cursos/cursos.php">
        Cursos
    </a>

    <a href="/ProjetoConectaTI/ConectaTI/views/eventos/eventos.php">
        Eventos
    </a>

    <a href="/ProjetoConectaTI/ConectaTI/views/oportunidades/oportunidades.php">
        Oportunidades
    </a>

    <?php if (
        isset($_SESSION["tipo"])
        && $_SESSION["tipo"] === "EMPRESA"
    ): ?>

        <a href="/ProjetoConectaTI/ConectaTI/views/oportunidades/cadastrar.php">
            Cadastrar oportunidade
        </a>

    <?php endif; ?>

    <a href="/ProjetoConectaTI/ConectaTI/views/profissionais/profissionais.php">
        Profissionais
    </a>

    <a href="/ProjetoConectaTI/ConectaTI/views/duvidas/duvidas.php">
        Dúvidas
    </a>

    <?php if (
        isset($_SESSION["tipo"])
        && $_SESSION["tipo"] === "EMPRESA"
    ): ?>

        <a href="/ProjetoConectaTI/ConectaTI/views/empresa/perfil.php">
            Meu perfil empresarial
        </a>

        <a href="/ProjetoConectaTI/ConectaTI/views/empresa/editar.php">
            Editar empresa
        </a>

    <?php endif; ?>

    <?php if (
        isset($_SESSION["tipo"]) &&
        (
            $_SESSION["tipo"] === "PROFISSIONAL" ||
            $_SESSION["tipo"] === "EMPRESA"
        )
    ): ?>

        <a href="/ProjetoConectaTI/ConectaTI/views/duvidas/recebidas.php">
            Dúvidas recebidas
        </a>

    <?php endif; ?>

    <a href="/ProjetoConectaTI/ConectaTI/views/notificacoes/notificacoes.php">
        Notificações
    </a>

    <a href="/ProjetoConectaTI/ConectaTI/views/favoritos/favoritos.php">
        Favoritos
    </a>

    <a href="/ProjetoConectaTI/ConectaTI/views/perfil/perfil.php">
        Meu perfil
    </a>

    <a href="/ProjetoConectaTI/ConectaTI/logout.php">
        Sair
    </a>

</nav>

<hr>