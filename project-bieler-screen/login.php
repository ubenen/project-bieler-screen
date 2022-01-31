<?php
    //Initialisiert PHP Session um Variable "anmeldung" abfragen und bearbeiten zu können
    session_start();

        //Verbindung zur Datenbank
        require 'includes/datenbank.php';

        //Header einbinden
        require 'includes/header.php';
    

        //Link für Weiterleitung wird zusammengestellt
        $host = htmlspecialchars($_SERVER['HTTP_HOST']);
        $uri = rtrim(dirname(htmlspecialchars($_SERVER['PHP_SELF'])), "/\\");
        $extra ='admin.php';

        $dbOutput = $datenbank->prepare('SELECT hash FROM passwort WHERE nutzer="admin";');

        $dbOutput->execute();

        $dbOutput->bind_result($passwortHash);

        $dbOutput->fetch();

        //Passwort wird aus FOrmular ausgelesen
        $passwort = $_POST['passwort'];

        //Passwort wird überprüft, bei Übereinstimmung erfolgt Weiterleitung
        if (password_verify($passwort, $passwortHash))
        {
            // echo "Erfolgreich angemeldet <br>";

            //anmedlung Variable wird Sessionweit auf TRUE gesetzt, um If-Anweisungen am Anfang von geschützten Seiten zu ermöglichen
            $_SESSION["anmeldung"] = 'admin';
            header("Location: http://$host$uri/$extra");
        }
        //Falls Passwort nicht übereinstimmt, wird eine Fehlermeldung und ein Link zurück auf die Indexseite ausgegeben
        else
        {
            echo "Fehler bei der Anmeldung";
            echo "<p><a href='screen.php'>Zurück zur Ansicht</a></p>";
        }
?>