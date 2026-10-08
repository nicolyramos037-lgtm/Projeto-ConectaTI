<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../controllers/NotificacaoController.php";

$id_usuario = $_SESSION["id_usuario"];

$notificacaoController = new NotificacaoController();


// Marcar uma notificação como lida
if (isset($_GET["ler"]) && isset($_GET["id"])) {

    $id_notificacao = (int) $_GET["id"];

    $notificacaoController->marcarComoLida(
        $id_notificacao,
        $id_usuario
    );

    header(
        "Location: /ProjetoConectaTI/ConectaTI/views/notificacoes/notificacoes.php"
    );

    exit;
}


// Marcar todas como lidas
if (isset($_GET["ler_todas"])) {

    $notificacaoController->marcarTodasComoLidas(
        $id_usuario
    );

    header(
        "Location: /ProjetoConectaTI/ConectaTI/views/notificacoes/notificacoes.php"
    );

    exit;
}


// Buscar notificações
$notificacoes = $notificacaoController->listarPorUsuario(
    $id_usuario
);

$totalNaoLidas = $notificacaoController->contarNaoLidas(
    $id_usuario
);

?>

<?php require_once __DIR__ . "/../menu/menu.php"; ?>


<h1>Notificações</h1>

<p>
    Você possui
    <strong><?= $totalNaoLidas ?></strong>
    notificação(ões) não lida(s).
</p>


<?php if (count($notificacoes) === 0): ?>

    <p>
        Você ainda não possui notificações.
    </p>

<?php else: ?>

    <p>
        <a href="?ler_todas=1">
            Marcar todas como lidas
        </a>
    </p>


    <?php foreach ($notificacoes as $notificacao): ?>

        <div>

            <?php if ($notificacao["lida"] == 0): ?>

                <strong>
                    🔵
                </strong>

            <?php else: ?>

                <strong>
                    ⚪
                </strong>

            <?php endif; ?>


            <h3>
                <?= htmlspecialchars($notificacao["titulo"]) ?>
            </h3>


            <p>
                <?= htmlspecialchars($notificacao["mensagem"]) ?>
            </p>


            <p>
                Tipo:
                <?= htmlspecialchars($notificacao["tipo"]) ?>
            </p>


            <p>
                Data:
                <?= htmlspecialchars($notificacao["data_criacao"]) ?>
            </p>


            <?php if ($notificacao["lida"] == 0): ?>

                <a
                    href="?ler=1&id=<?= $notificacao["id_notificacao"] ?>"
                >
                    Marcar como lida
                </a>

            <?php else: ?>

                <span>
                    Lida
                </span>

            <?php endif; ?>

        </div>

        <hr>

    <?php endforeach; ?>

<?php endif; ?>