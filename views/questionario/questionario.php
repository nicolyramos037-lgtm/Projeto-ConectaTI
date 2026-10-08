<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
    exit;
}

require_once __DIR__ . "/../../config/conexao.php";

$id_usuario = $_SESSION["id_usuario"];

$mensagem = "";

/*
|--------------------------------------------------------------------------
| Buscar áreas de TI
|--------------------------------------------------------------------------
*/

$sql = "SELECT id_area, nome
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

/*
|--------------------------------------------------------------------------
| Salvar questionário
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nivel_ti = $_POST["nivel_ti"] ?? "";
    $objetivo = $_POST["objetivo"] ?? "";
    $areas_selecionadas = $_POST["areas"] ?? [];

    if (
        $nivel_ti === "" ||
        $objetivo === "" ||
        empty($areas_selecionadas)
    ) {

        $mensagem = "Preencha todas as informações.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | Salvar preferência principal
        |--------------------------------------------------------------------------
        */

        $sqlPreferencia = "INSERT INTO preferencia_usuario
                           (id_usuario, nivel_ti, objetivo)
                           VALUES (?, ?, ?)
                           ON DUPLICATE KEY UPDATE
                           nivel_ti = VALUES(nivel_ti),
                           objetivo = VALUES(objetivo)";

        $stmtPreferencia = $conexao->prepare($sqlPreferencia);

        $stmtPreferencia->bind_param(
            "iss",
            $id_usuario,
            $nivel_ti,
            $objetivo
        );

        $stmtPreferencia->execute();

        /*
        |--------------------------------------------------------------------------
        | Limpar áreas anteriores
        |--------------------------------------------------------------------------
        */

        $sqlExcluir = "DELETE FROM usuario_area
                       WHERE id_usuario = ?";

        $stmtExcluir = $conexao->prepare($sqlExcluir);

        $stmtExcluir->bind_param(
            "i",
            $id_usuario
        );

        $stmtExcluir->execute();

        /*
        |--------------------------------------------------------------------------
        | Salvar áreas escolhidas
        |--------------------------------------------------------------------------
        */

        $sqlArea = "INSERT INTO usuario_area
                    (id_usuario, id_area)
                    VALUES (?, ?)";

        $stmtArea = $conexao->prepare($sqlArea);

        foreach ($areas_selecionadas as $id_area) {

            $id_area = intval($id_area);

            $stmtArea->bind_param(
                "ii",
                $id_usuario,
                $id_area
            );

            $stmtArea->execute();
        }

        /*
        |--------------------------------------------------------------------------
        | Finalizar
        |--------------------------------------------------------------------------
        */

        header("Location: /ProjetoConectaTI/ConectaTI/views/inicio/inicio.php");
        exit;
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

<title>Conheça você - ConectaTI</title>

</head>

<body>

<h1>ConectaTI</h1>

<h2>Vamos conhecer você 💜</h2>

<p>
Queremos entender seus interesses para deixar o ConectaTI
mais personalizado para você.
</p>


<?php if ($mensagem != ""): ?>

<p>

<strong>

<?php echo htmlspecialchars($mensagem); ?>

</strong>

</p>

<?php endif; ?>


<hr>


<form method="POST">

<h3>1. Qual é o seu nível de conhecimento em TI?</h3>

<label>

<input
    type="radio"
    name="nivel_ti"
    value="INICIANTE"
    required
>

Estou começando agora

</label>

<br><br>


<label>

<input
    type="radio"
    name="nivel_ti"
    value="BASICO"
>

Já tenho algum conhecimento

</label>

<br><br>


<label>

<input
    type="radio"
    name="nivel_ti"
    value="ESTUDANTE"
>

Sou estudante de TI

</label>

<br><br>


<label>

<input
    type="radio"
    name="nivel_ti"
    value="PROFISSIONAL"
>

Já trabalho com TI

</label>


<hr>


<h3>2. O que você procura no ConectaTI?</h3>

<select name="objetivo" required>

<option value="">
Selecione uma opção
</option>

<option value="CONHECER_TI">
Quero conhecer mais sobre TI
</option>

<option value="APRENDER">
Quero aprender e estudar
</option>

<option value="CONSEGUIR_EMPREGO">
Quero encontrar oportunidades de trabalho
</option>

<option value="MELHORAR_CARREIRA">
Quero melhorar minha carreira
</option>

<option value="NETWORKING">
Quero conhecer pessoas da área
</option>

</select>


<hr>


<h3>3. Quais áreas de TI interessam você?</h3>

<p>
Você pode escolher mais de uma opção.
</p>


<?php if (empty($areas)): ?>

<p>
Nenhuma área de TI cadastrada.
</p>

<?php else: ?>

<?php foreach ($areas as $area): ?>

<label>

<input
    type="checkbox"
    name="areas[]"
    value="<?php echo $area["id_area"]; ?>"
>

<?php echo htmlspecialchars($area["nome"]); ?>

</label>

<br><br>

<?php endforeach; ?>

<?php endif; ?>


<hr>


<button type="submit">

Continuar para o ConectaTI

</button>

</form>

</body>

</html>