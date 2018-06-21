<?php
session_start();
    $_SESSION['ausweis'] = 0;
    $_SESSION['access'] = 0;
    session_destroy();
    header("Location: index.php");
    ?>