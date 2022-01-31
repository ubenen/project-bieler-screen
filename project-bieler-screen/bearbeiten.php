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
        $extra = 'admin.php';


        //Falls Produktnummer aus Formular übermittelt wurde, wird diese einer Variable zugewiesen, und eine SQL abfrage in einer Variable gespeichert
        if (isset($_GET['id'])) :
            $id = $_GET['id'];
            $abfrage = 'SELECT id, buchname, beschreibung, preis, ISBN, autor, verfuegbarkeit, aktiviert FROM buecher WHERE id=?';

            //Prüft, ob SQL Statement vorbereitet werden kann
            if ($dbOutput = $datenbank->prepare($abfrage)) :

                //Variable wird an vorbereitetes Statement übermittelt, als Parameter angegeben
                $dbOutput->bind_param('i', $id);

                //Vorbereitetes Statement wird ausgeführt
                $dbOutput->execute();

                //Bindet Ergebnis des SQL Statements an Variablen
                $dbOutput->bind_result($id, $buchname, $beschreibung, $preis, $ISBN, $autor, $verfuegbarkeit, $aktiviert);

                //Zieht Werte Zeilenweise und speichert diese in Variable (Da nur ein Set an Tupeln angefordert wird, ist dies nicht mit einer SChleife verbunden)
                $dbOutput->fetch();
                $dbOutput->close();

                //Werte in Variablen werden bereinigt
                $buchname = htmlspecialchars($buchname);
                $beschreibung = htmlspecialchars($beschreibung);
                $preis = (double) $preis;
                $ISBN = (int) $ISBN;
                $autor = htmlspecialchars($autor);
                $verfuegbarkeit = (int) $verfuegbarkeit;
                $id = (int) $id;
                $aktiviert = (int) $aktiviert;

            endif;
        endif;


        //Änderungen werden als Parameter an das UPDATE SQL statement gebunden

        //Wenn REQUEST_METHOD den Wert POST enthält (durch absenden des Formulars), wird ausgeführt
        if ($_SERVER["REQUEST_METHOD"] == "POST") :
            //Speichert SQL Abfrage in Variable
            $abfrage = 'UPDATE buecher SET buchname=?, beschreibung=?, preis=?, ISBN=?, autor=?, verfuegbarkeit=?, aktiviert=? WHERE id=?';

            if ($dbOutput = $datenbank->prepare($abfrage)) :
                //Werte aus Formularen wird ausgelesen
                $id = $_POST["id"];
                $buchname = $_POST['buchname'];
                $beschreibung = $_POST['beschreibung'];
                $preis = (double) str_replace(",", ".", $_POST['preis']);
                $ISBN = (string) $_POST['ISBN'];
                $autor = $_POST['autor'];
                $verfuegbarkeit = $_POST['verfuegbarkeit'];
                $aktiviert = $_POST['aktiviert'];


                //Bindet Variablen als Parameter an SQL statement in $abfrage
                $dbOutput->bind_param('ssdisiii', $buchname, $beschreibung, $preis, $ISBN, $autor, $verfuegbarkeit, $aktiviert, $id);

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
        <form method="POST" action="bearbeiten.php">
        <!-- Produktnummer -->
        <label for="id">Produktnummer</label>
        <input type="number" name="id" id="id" value="<?php echo $id; ?>">

        <!-- Produktname -->
        <label for="buchname">Produktname</label>
        <input type="text" name="buchname" id="buchname" value="<?php echo $buchname; ?>">

        <!-- Produktbeschreibung -->
        <label for="beschreibung">Produktbeschreibung</label>
        <input type="textarea" name="beschreibung" id="beschreibung" rows="5" cols="30" value="<?php echo $beschreibung; ?>">

        <!-- Preis -->
        <label for="preis">Preis</label>
        <input type="text" name="preis" id="preis" value="<?php echo $preis; ?>">

        <!-- ISBN -->
        <label for="ISBN">ISBN</label>
        <input type="text" name="ISBN" id="ISBN" value="<?php echo $ISBN; ?>">

        <!-- Autor -->
        <label for="autor">Autor</label>
        <input type="text" name="autor" id="autor" value="<?php echo $autor; ?>">

        <!-- Verfügbarkeit -->
        <label for="verfuegbarkeit">Verfügbar?</label>
                
        <select name="verfuegbarkeit" id="verfuegbarkeit" required>
            <option value=1>Verfügbar</option>
            <option value=0>Nicht Verfügbar</option>
        </select>

        <!-- Aktiviert? -->
        <label for="aktiviert">Aktiviert?</label>

        <select name="aktiviert" id="aktiviert" required>
            <option value=1>Aktiv</option>
            <option value=0>Inaktiv</option>
        </select>

        <input type="submit" value="Eintragen">
        </form>

        <?php

        //Link zurück zum Adminbereich
        echo "<p><a href='admin.php'>Zum Adminbereich</a></p>";

        //Einbinden von Footer
        require "includes/footer.php";
    else :
        //Falls Variable anmeldung nicht gesetzt ist, wird Fehlermedlung und Link zu Ansichtseite ausgegeben
        echo "Fehler bei Anmeldung";

        echo "<p><a href='screen.php'>Zur Anzeige</a></p>";
    endif;
?>