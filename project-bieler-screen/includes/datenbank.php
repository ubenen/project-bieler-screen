<?php
    $datenbank = new mysqli('localhost', 'root', '', 'bieler');

    if ($datenbank->connect_error)
    {
        echo 'Fehler bei der Verbindung: ' . mysqli_connect_error();
        exit();
    }

    if (!$datenbank->set_charset('utf8'))
    {
        echo 'Fehler beim Laden von UTF8 ' . $datenbank->error;
    }
?>
