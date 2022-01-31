<?php
    //Initialisiert PHP Session um Variable "anmeldung" abfragen zu können
    session_start();

    //Prüft, ob Nutzer in der Session angemeldet ist
    if (isset($_SESSION["anmeldung"])) :

        //Datenbank verbindungherstellen und Header einbinden
        require 'includes/datenbank.php';
        require 'includes/header.php';

        //Host, URI und extra für URL speichern
        $host = htmlspecialchars($_SERVER['HTTP_HOST']);
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), "/\\");
        $extra = 'bildAdmin.php';


        //Falls Produktnummer aus Formular übermittelt wurde, wird diese einer Variable zugewiesen, und eine SQL abfrage in einer Variable gespeichert
        if (isset($_GET['id'])) :
            $id = $_GET['id'];
            $abfrage = 'SELECT id, ordner, artikelNr FROM bilder WHERE id=?';

            //Prüft, ob SQL Statement vorbereitet werden kann
            if ($dbOutput = $datenbank->prepare($abfrage)) :

                //Variable wird an vorbereitetes Statement übermittelt, als Parameter angegeben
                $dbOutput->bind_param('i', $id);

                //Vorbereitetes Statement wird ausgeführt
                $dbOutput->execute();

                //Bindet Ergebnis des SQL Statements an Variablen
                $dbOutput->bind_result($id, $ordner, $artikelNr);

                //Zieht Werte Zeilenweise und speichert diese in Variable (Da nur ein Set an Tupeln angefordert wird, ist dies nicht mit einer SChleife verbunden)
                $dbOutput->fetch();
                $dbOutput->close();

                //Werte in Variablen werden bereinigt
                $ordner = htmlspecialchars($ordner);
                $id = (int) $id;
                $artikelNr = (int) $artikelNr;

            endif;
        endif;


        //Änderungen werden als Parameter an das UPDATE SQL statement gebunden

        //Wenn REQUEST_METHOD den Wert POST enthält (durch absenden des Formulars), wird ausgeführt
        if ($_SERVER["REQUEST_METHOD"] == "POST") :
            //Speichert SQL Abfrage in Variable
            $abfrage = 'UPDATE bilder SET ordner=?, artikelNr=? WHERE id=?';

            if ($dbOutput = $datenbank->prepare($abfrage)) :
                //Werte aus Formularen wird ausgelesen
                $id = $_POST["id"];
                $ordner = $_POST['ordner'];
                $artikelNr = $_POST['artikelNr'];


                //Bindet Variablen als Parameter an SQL statement in $abfrage
                $dbOutput->bind_param('sii', $ordner, $artikelNr, $id);

                //Führt vorbereitetes Statement aus
                $dbOutput->execute();

                //Schließt Verbindung mit Datenbank
                $datenbank->close();

                //Leitet an in $extra gespeicherte Seite weiter
                header("Location: http://$host$uri/$extra");
            endif;
        endif;

        ?>

        <!-- Formular zur Änderung bestehender Einträge, wird an selbst weitergeleitet -->
        <form method="POST" action="bildBearbeiten.php">
        <!-- Produktnummer -->
        <label for="id">Bild ID</label>
        <input type="number" name="id" id="id" value="<?php echo $id; ?>">

        <!-- Produktname -->
        <label for="ordner">Speicherort</label>
        <input type="text" name="ordner" id="ordner" value="<?php echo $ordner; ?>">

        <!-- Buch ID -->
        <label for="artikelNr">Produktnummer</label>
        <input type="number" name="artikelNr" id="artikelNr" value="<?php echo $artikelNr; ?>">


        <input type="submit" value="Eintragen">
        </form>

        <?php

        //Link zurück zum Adminbereich
        echo "<p><a href='bildAdmin.php'>Zur Bildverwaltung</a></p>";

        //Einbinden von Footer
        require "includes/footer.php";
    else :
        //Falls Variable anmeldung nicht gesetzt ist, wird Fehlermedlung und Link zu Indexseite ausgegeben
        echo "Fehler bei Anmeldung";

        echo "<p><a href='screen.php'>Zur Anzeige</a></p>";
    endif;
?>