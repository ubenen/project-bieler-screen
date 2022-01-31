<?php

//initialisiert Session um Anmeldeprozess zu ermöglichen
    session_start();

    if (isset($_SESSION["anmeldung"])) :

        require 'includes/datenbank.php';
        require 'includes/header.php';

        //Host und Uri der Seite werden abgerufen und in Variablen gespeichert, Slashes werden in URI ausgefiltert
        $host = htmlspecialchars($_SERVER['HTTP_HOST']);
        $uri = rtrim(dirname(htmlspecialchars($_SERVER['PHP_SELF'])), "/\\");
        $extra = 'admin.php';

        //Falls Anfrage Methode POST ist, wird ein SQL Statement prepariert
        if ($_SERVER["REQUEST_METHOD"] == "POST") :
            
            if ($dbOutput = $datenbank->prepare('INSERT INTO buecher (id, buchname, beschreibung, preis, ISBN, autor, verfuegbarkeit, aktiviert) VALUES (?, ?, ?, ?, ?, ?, ?, ?)')) :
                
                //Weist Werten aus Formular Variablen zu
                $id = $_POST["id"];
                $buchname = $_POST['buchname'];
                $beschreibung = $_POST['beschreibung'];
                $preis = str_replace(",", ".", $_POST['preis']);
                $ISBN = (string) $_POST['ISBN'];
                $autor = $_POST['autor'];
                $verfuegbarkeit = $_POST['verfuegbarkeit'];
                $aktiviert = $_POST['aktiviert'];

                //Bindet Parameter an Fragezeichen
                $dbOutput->bind_param('issdisii', $id, $buchname, $beschreibung, $preis, $ISBN, $autor, $verfuegbarkeit, $aktiviert);

                //Führt Vorbereitetes Statement aus
                $dbOutput->execute();

                //Schließt Verbindung zu Datensatz
                $dbOutput->close();

                //Schließt Datenbankverbindung
                $datenbank->close();

                //Leitet an admin.php weiter
                header("Location: http://$host$uri/$extra");
            endif;
        endif;
    ?>

    <?php
        if ($dbOutput = $datenbank->prepare('SELECT MAX(id) FROM buecher')) :
            $dbOutput->execute();
            $dbOutput->bind_result($maxId);
            $dbOutput->fetch();
            $dbOutput->close();
            
            $maxId = $maxId + 1;
            ?>

            <!-- Formular zur Eingabe neuer Einträge, wird an selbst weitergeleitet -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <!-- Produktnummer -->
                <label for="id">Produktnummer</label>
                <input type="number" value=<?php echo $maxId; ?> name="id" id="id">

                <!-- Produktname -->
                <label for="buchname">Produktname</label>
                <input type="text" name="buchname" id="buchname" required>

                <!-- Produktbeschreibung -->
                <label for="beschreibung">Produktbeschreibung</label>
                <input type="text" name="beschreibung" id="beschreibung">

                <!-- Preis -->
                <label for="preis">Preis</label>
                <input type="text" name="preis" id="preis" required>

                <!-- ISBN -->
                <label for="ISBN">ISBN</label>
                <input type="text" name="ISBN" id="ISBN">

                <!-- Autor -->
                <label for="autor">Autor</label>
                <input type="text" name="autor" id="autor">

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
                <input type="reset" value="Zurücksetzen"> 

            </form>

            <p><a href='admin.php'>Zum Adminbereich</a></p>
    <?php
        endif;
    ?>
    <?php
    else :
        //Falls Variable anmeldung nicht gesetzt ist, wird Fehlermedlung und Link zu Indexseite ausgegeben
        echo "Fehler bei Anmeldung";
        echo "<p><a href='screen.php'>Zur Anzeige</a></p>";
    endif;

    require "includes/footer.php";
?>