<?php

session_start();

session_unset();
session_destroy();

header("Location: /ProjetoConectaTI/ConectaTI/views/login/login.php");
exit;
?>