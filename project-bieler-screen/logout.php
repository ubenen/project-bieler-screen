<?php
    //Session wird initialisiert um Anmeldungsvariable Zurücksetzen und die Session beenden zu können
    session_start();
    $host = htmlspecialchars($_SERVER['HTTP_HOST']);

    $uri = rtrim(dirname(htmlspecialchars($_SERVER['PHP_SELF'])), "/\\");

    $extra ='screen.php';

    echo "Erfoglreich abgemeldet";

    //Sessionvariablen werden entfernt
    session_unset();

    //Session wird zerstört
    session_destroy();

    //Weiterleitung an Indexseite
    header("Location: http://$host$uri/$extra");

?>