<?php
    //Initialisiert PHP Session um Variable "anmeldung" abfragen zu können
    session_start();

    //Prüft, ob Nutzer in der Session angemeldet ist
    if (isset($_SESSION["anmeldung"])) :

        //Verbindung zur Datenbank
        require 'includes/datenbank.php';

        //Header einbinden
        require 'includes/header.php';
?>
        <!-- Link zur Seite für Neueintragserfassung -->
        <p><a href="neu.php">Neuen Eintrag verfassen</a></p>

<?php

        //Links zur Ansicht und Abmeldungsseite
        echo "<p><a href='screen.php'>Zurück zur Ansicht</a></p>";
        echo "<p><a href='bildAdmin.php'>Bilder verwalten</a></p>";
        echo "<p><a href='passwort.php'>Passwort Ändern</a></p>";
        echo "<p><a href='logout.php'>Abmelden</a></p>";

        //Falls möglich, SQL statement vorbereiten
        if ($dbOutput = $datenbank->prepare('SELECT id, buchname, beschreibung, preis, ISBN, autor, verfuegbarkeit, aktiviert FROM buecher ORDER BY id ASC')) :
            
            //SQL Statement asuführen
            $dbOutput->execute();

            //Variablen zum Speichern der Datenbankabfrage bereitstellen
            $dbOutput->bind_result($id, $buchname, $beschreibung, $preis, $ISBN, $autor, $verfuegbarkeit, $aktiviert);

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
                            <th>ID</th>
                            <th>Buchtitel</th>
                            <th>Beschreibung</th>
                            <th>Preis</th>
                            <th>ISBN</th>
                            <th>Autor</th>
                            <th>Verfügbarkeit</th>
                            <th>Aktiviert?</th>
                        </tr>

                        <?php
                        //Zieht zeilenweise Einträge der Datenbank und speichert diese in Variablen
                        while ($dbOutput->fetch()) :

                            //sonderzeichen werden HTML-fruendlich umgewandelt
                            $buchname = htmlspecialchars($buchname);
                            $beschreibung = htmlspecialchars($beschreibung);
                            $preis = (double) $preis;
                            $ISBN = (string) $ISBN;
                            $autor = htmlspecialchars($autor);
                            $id = (int) $id;
                            $aktiviert = (int) $aktiviert;

                            //Durch Switch wird der Boolsche Verfügbarkeitswert (0/1) in einen entsprechenden String umgewandelt
                            switch ($verfuegbarkeit) :
                                case 0:
                                    $verfuegbarkeit = "Nicht Verfügbar";
                                    break;
                                case 1:
                                    $verfuegbarkeit = "Verfügbar";
                                    break;
                                default:
                                    echo "Fehler bei Verfügbarkeitsprüfung";
                                endswitch;


                            //Wie vorheriges, Aktivierungsstatus wird von Bool in text umgewandelt
                            switch ($aktiviert) :
                                case 0:
                                    $aktiviert = "Nicht Aktiviert";
                                    break;
                                case 1:
                                    $aktiviert = "Aktiviert";
                                    break;
                                default:
                                    echo "Fehler bei Aktivierungsprüfung";
                                endswitch;

                                ?>

                                <!-- Erstellt Tabelle mit Einträgen -->
                                <tr>
                                    <th><?php echo $id; ?></th>
                                    <td><?php echo $buchname; ?></td>
                                    <td><?php echo $beschreibung; ?></td>
                                    <td><?php echo $preis; ?>€</td>
                                    <td><?php echo $ISBN; ?></td> 
                                    <td><?php echo $autor; ?></td> 
                                    <td><?php echo $verfuegbarkeit; ?></td>
                                    <td><?php echo $aktiviert; ?></td>

                                    <!-- erstellt "Bearbeiten" Link, hängt id an -->
                                    <td>
                                        <a href="bearbeiten.php?id=<?php echo $id; ?>" >bearbeiten</a>
                                    </td>

                                    <!-- Fügt "Löschen" Link ein, link wird durch Id ergänzt -->
                                    <td>
                                        <a href="loeschen.php?id=<?php echo $id; ?>"onclick="return confirm('Wollen Sie den Eintrag wirklich löschen?')">löschen</a>
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
        ?>
<?php        
        //Footer für HTML wird eingebunden
        require "includes/footer.php";
    else:

        //Fehlermeldung falls nicht angemeldet, Link zurück auf Ansicht (screen.php)
        echo "Anmeldung Fehlgeschlagen";
        echo "<p><a href='screen.php'>Zurück zur Ansicht</a></p>";
    endif;
?>