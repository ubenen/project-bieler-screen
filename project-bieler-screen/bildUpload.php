<?php
    //Initialisiert PHP Session um Variable "anmeldung" abfragen zu können
    session_start();

    //Prüft, ob Nutzer in der Session angemeldet ist
    if (isset($_SESSION["anmeldung"])) :

        require 'includes/datenbank.php';
        require 'includes/header.php'
    ?>

    <?php
        //Prüft, ob Formular abgesendet wurde
        if (isset($_POST['upload'])) :

            
            //Weist Variablen Werte aus Formular zu
            $dateiname = $_FILES['uploadDatei']['name'];
            $tempName = $_FILES['uploadDatei']['tmp_name'];
            $artikelNr = $_POST['artikelNr'];

            //Speichert Speicherort für Bild in variable
            $ordner = "bilder/" . $dateiname;
            
            //Gibt Name aus
            //echo mime_content_type($tempName);

            //Prüft Inhaltstyp des Bildes im Zwischenspeicher, checkt ob der vordersten Teil "image" enthält
            if (substr(mime_content_type($tempName), 0, 5) == "image") :

                //Bereitet Statement vor
                $abfrage = 'DELETE FROM bilder WHERE artikelNr=?;';
                $abfrage2 = 'INSERT INTO bilder (ordner, artikelNr) VALUES (?, ?);';

                // $abfrage = 'INSERT INTO bilder (ordner, artikelNr) VALUES (?, ?) WHERE NOT EXISTS (SELECT id FROM bilder WHERE artikelNr=?);
                // UPDATE bilder SET ordner = ? WHERE EXISTS (SELECT artikelNr FROM bilder WHERE artikelNr = ?);';
                // $abfrage = 'INSERT INTO bilder (ordner, artikelNr) VALUES (?, ?) ON DUPLICATE artikelNr UPDATE ordner;';

                //Führt Abfrage 1 aus
                $dbOutput = $datenbank->prepare($abfrage);

                $dbOutput->bind_param('i', $artikelNr);

                $dbOutput->execute();

                //Führt Abfrage 2 aus
                $dbOutput = $datenbank->prepare($abfrage2);

                $dbOutput->bind_param('si', $ordner, $artikelNr);

                $dbOutput->execute();

                //Speichert Bild
                if (move_uploaded_file($tempName, $ordner)) :
                    echo "Upload Erfolgreich";
                else :
                    echo "Upload Fehlgeschlagen";
                endif;
            else :
                echo "Ungültiger Dateityp!";
            endif;
        endif;
    ?>

    <body>
        <div id="content">
    
            <form method="POST" action="" enctype="multipart/form-data">

                <!-- Input für Datei -->
                <label for="uploadDatei">Datei</label>
                <input type="file" name="uploadDatei" id="uploadDatei" value="" />

                <!-- Input für Artikelnummer -->
                <label for="artikelNr">Artikelnummer</label>
                <input type="number" name="artikelNr" id="artikelNr" />
    
                <div>
                    <button type="submit" name="upload">Hochladen </button>
                </div>
            </form>

            <a href="bildAdmin.php">Zurück zur Bildverwaltung</a>
        </div>
<?php
    else :
        //Fehlermeldung falls nicht angemeldet, Link zurück auf Ansicht (screen.php)
        echo "Anmeldung Fehlgeschlagen";
        echo "<p><a href='screen.php'>Zurück zur Ansicht</a></p>";
    endif;
    require 'includes/footer.php'
?>