<?php
    //initilisiert Session um Anmeldestatus überprüfen zu können
    session_start();

    //Anmeldestatus wird überprüft
    if (isset($_SESSION["anmeldung"])) :

        require 'includes/datenbank.php';
        require 'includes/header.php';

        //Speichert momentanen Host (localhost) in Array, weist diesem den Wert "HTTP_HOST" zu
        $host = htmlspecialchars($_SERVER['HTTP_HOST']);

        //rtrim entfernt anhängende Leerzeichen, dirname gibt den Namen des Verzeichnisses an, htmlspecialchars wandelt Sonderzeichen in HTML-freundliche Entities um
        $uri = rtrim(dirname(htmlspecialchars($_SERVER['PHP_SELF'])), "/\\");

        //Gibt Teilziel für Links an, in diesem fall admin.php
        $extra ='bildAdmin.php';

        //Falls Produkt Nummer nicht gesetzt, oder nicht nummerisch ist, wird Header gesendet
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) :
            header("Location: http://$host$uri/$extra");
        endif;

        //Formular Input wird zugeordnet
        $id = $_GET['id'];

        //i gbt Datentype an, wird in SQL statement eingesetzt, mehrere ? sind möglich, in diesem Fall werden mehrere Datentyp/Wert Paare angegeben
        if ($dbOutput = $datenbank->prepare('DELETE FROM bilder WHERE id=?')) :
            $dbOutput->bind_param('i', $id);
            $dbOutput->execute();
            $dbOutput->close();

            $datenbank->close();

            //Leitet auf admin.php weiter
            header("Location: http://$host$uri/$extra");
        endif;
    
    else :
        //Falls Variable anmeldung nicht gesetzt ist, wird Fehlermedlung und Link zu Indexseite ausgegeben
        echo "Fehler bei Anmeldung";
        echo "<p><a href='screen.php'>Zur Anzeige</a></p>";

        //Bindet footer mit html code ein
        require 'includes/footer.php';
    endif;
?>