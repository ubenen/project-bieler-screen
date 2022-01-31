<?php
    //Initialisiert PHP Session um Variable "anmeldung" abfragen zu können
    session_start();

    //Prüft, ob Nutzer in der Session angemeldet ist
    if (isset($_SESSION["anmeldung"])) :

        //Verbindung zur Datenbank
        require 'includes/datenbank.php';

        //Header einbinden
        require 'includes/header.php';

        //Links zur Bildverwaltung und Ansicht
        echo "<p><a href='bildUpload.php'>Neues Bild einfügen</a></p>";
        echo "<p><a href='screen.php'>Zurück zur Ansicht</a></p>";
        echo "<p><a href='admin.php'>Zum Adminbereich</a></p>";

        //Falls möglich, SQL statement vorbereiten
        if ($dbOutput = $datenbank->prepare('SELECT id, ordner, artikelNr FROM bilder ORDER BY artikelNr ASC')) :
            
            //SQL Statement asuführen
            $dbOutput->execute();

            //Variablen zum Speichern der Datenbankabfrage bereitstellen
            $dbOutput->bind_result($id, $ordner, $artikelNr);

            //Abfrageergebnisse zwischenspeichern
            $dbOutput->store_result();

            //Wird ausgeführt, falls das Ergebnis des präperierten Statements mehr als 0 Zeilen zurückliefert
            if ($dbOutput->num_rows > 0) :
                ?>

                <table>
                    <?php
                        //Speichert Ergebnisse des SQL Statements in vorbereiteten Variablen
                        ?>

                        <!-- Erstellt Headerzeile für Tabelle -->
                        <tr>
                            <th>Bild</th>
                            <th>ID</th>
                            <th>Ordner</th>
                            <th>Buch ID</th>
                        </tr>

                        <?php
                        //Zieht zeilenweise Einträge der Datenbank und speichert diese in Variablen
                        while ($dbOutput->fetch()) :

                            //Sonderzeichen werden HTML-fruendlich umgewandelt
                            $ordner = htmlspecialchars($ordner);
                            $id = (int) $id;
                            $aktiviert = (int) $artikelNr;
                                ?>

                                <!-- Erstellt Tabelle mit Einträgen -->
                                <tr>
                                    <td style="width: 20%"><img src="<?php echo $ordner ?>" style="width: 100%"></td>
                                    <td><?php echo $id; ?></td> 
                                    <td><?php echo $ordner; ?></td>
                                    <td><?php echo $artikelNr; ?></td>

                                    <!-- erstellt "Bearbeiten" Link, hängt id an -->
                                    <td>
                                        <a href="bildBearbeiten.php?id=<?php echo $id; ?>" >bearbeiten</a>
                                    </td>

                                    <!-- Fügt "Löschen" Link ein, link wird durch Id ergänzt -->
                                    <td>
                                        <a href="bildLoeschen.php?id=<?php echo $id; ?>"onclick="return confirm('Wollen Sie den Eintrag wirklich löschen?')">löschen</a>
                                    </td>
                                </tr>
                            <?php
                        
                        endwhile;

                        //Schließt vorher aufgebaute Verbindung zu Datensatz
                        $dbOutput->close();
                    ?>
                </table>

            <?php
            endif;
        endif;

        // Datenbankverbindung schließen
        $datenbank->close();

        //Footer für HTML wird eingebunden
        require "includes/footer.php";
    else:

        //Fehlermeldung falls nicht angemeldet, Link zurück auf Ansicht (screen.php)
        echo "Anmeldung Fehlgeschlagen";
        echo "<p><a href='screen.php'>Zurück zur Ansicht</a></p>";
    endif;
?>