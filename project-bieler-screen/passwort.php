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


        //Falls Formular abgesendet wurde, wird nutzer aus Session Variable ausgelesen und eine abfrage für dessen Password hash druchgeführt
        if (isset($_SESSION["anmeldung"])) :
            $nutzer = $_SESSION['anmeldung'];
            $abfrage = 'SELECT id, nutzer, hash FROM passwort WHERE nutzer=?';

            //Prüft, ob SQL Statement vorbereitet werden kann
            if ($dbOutput = $datenbank->prepare($abfrage)) :

                //Variable wird an vorbereitetes Statement übermittelt, als Parameter angegeben
                $dbOutput->bind_param('s', $nutzer);

                //Vorbereitetes Statement wird ausgeführt
                $dbOutput->execute();

                //Bindet Ergebnis des SQL Statements an Variablen
                $dbOutput->bind_result($id, $nutzer, $passwortHash);

                //Zieht Werte Zeilenweise und speichert diese in Variable (Da nur ein Set an Tupeln angefordert wird, ist dies nicht mit einer Schleife verbunden)
                $dbOutput->fetch();
                $dbOutput->close();

                //Werte in Variablen werden bereinigt
                $nutzer = htmlspecialchars($nutzer);

            endif;
        endif;


        //Änderungen werden als Parameter an das UPDATE SQL statement gebunden

        //Wenn REQUEST_METHOD den Wert POST enthält (durch absenden des Formulars), wird ausgeführt
        if ($_SERVER["REQUEST_METHOD"] == "POST") :
            //Speichert SQL Abfrage in Variable
            $abfrage = 'UPDATE passwort SET nutzer=? hash=? WHERE id=?';

            if($_POST['passwortNeu1'] == $_POST['passwortNeu2']) :
                if (password_verify($_POST['passwortAlt'], $passwortHash)) :

                    if ($dbOutput = $datenbank->prepare($abfrage)) :
                        
                        //Werte aus Formularen wird ausgelesen
                        $passwortNeu = $_POST['passwortNeu1'];
                        $nutzer = $_POST['nutzer'];
                        $id = $_POST['id'];

                        $passwortHash = password_hash($passwortNeu, PASSWORD_DEFAULT);

                        //Bindet Variablen als Parameter an SQL statement in $abfrage
                        $dbOutput->bind_param('ssi', $nutzer, $passwortHash, $id);

                        //Führt vorbereitetes Statement aus
                        $dbOutput->execute();

                        //Schließt Verbindung mit Datenbank
                        $datenbank->close();

                        //Leitet an in $extra gespeicherte Seite weiter
                        header("Location: http://$host$uri/$extra");
                    endif;
                else :
                    echo "Bitte geben Sie das korrekte Alte Passwort an.";
                endif;
            else :
                echo "Neues Passwort stimmt nicht überein";
            endif;
        endif;

        ?>

        <!-- Formular zur Änderung bestehender Einträge, wird an selbst weitergeleitet -->
        <form method="POST" action="passwort.php">
        <input type="hidden" name="id" id="id" value=<?php echo $id;?>>

        <!-- Nutzer -->
        <label for="nutzer">Nutzer</label>
        <input type="text" name="nutzer" id="nutzer" value="<?php echo $nutzer; ?>">

        <!-- Altes Passwort -->
        <label for="passwortAlt">Altes Passwort</label>
        <input type="password" name="passwortAlt" id="passwortAlt" required>

        <!-- Neues Passwort -->
        <label for="passwortNeu1">Neues Passwort</label>
        <input type="password" name="passwortNeu1" id="passwortNeu1" required>

        <label for="passwortNeu2">Neues Passwort bestätigen</label>
        <input type="password" name="passwortNeu2" id="passwortNeu2" required>

        <input type="submit" value="Eintragen">
        </form>

        <?php

        //Link zurück zum Adminbereich
        echo "<p><a href='admin.php'>Zum Adminbereich</a></p>";

        //Einbinden von Footer
        require "includes/footer.php";
    else :
        //Falls Variable anmeldung nicht gesetzt ist, wird Fehlermedlung und Link zu Indexseite ausgegeben
        echo "Fehler bei Anmeldung";

        echo "<p><a href='screen.php'>Zur Anzeige</a></p>";
    endif;
?>