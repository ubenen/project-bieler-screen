<?php    

    //Bereitet SQL Statement vor um Daten abzurufen und nach Produktnummer zu sortieren
    if ($dbOutput = $datenbank->prepare('SELECT buecher.id, buecher.buchname, buecher.beschreibung, buecher.preis, buecher.ISBN, buecher.autor, buecher.verfuegbarkeit, buecher.aktiviert, MIN(bilder.ordner) FROM buecher LEFT JOIN bilder ON buecher.id=bilder.artikelNr WHERE buecher.id=?;')) :

        $dbOutput->bind_param('i', $i);

        //Führt vorbereitetes Statement aus
        $dbOutput->execute();

        //Gibt Variablen für Fetch an
        $dbOutput->bind_result($id, $buchname, $beschreibung, $preis, $ISBN, $autor, $verfuegbarkeit, $aktiviert, $ordner);
        
        //Speichert Ergebnisse, notwendig bei mehrzeiliger Ausgabe
        $dbOutput->store_result();

        if ($dbOutput->num_rows > 0) :

            //Fetcht Ausgabe 
            $dbOutput->fetch();
            
            // Einträge werden lediglich verarbeitet, wenn die Variable $aktiviert TRUE ist, also der Eintrag angezeigt werden soll
            if ($aktiviert == 1) :

                $buchname = htmlspecialchars($buchname);
                $beschreibung = htmlspecialchars($beschreibung);
                $preis = (double) $preis;
                $ISBN = (int) $ISBN;
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
                <div class="mySlides fade">
                <div class="numbertext"><?php echo $i; ?> / <?php echo $eintragAnzahl; ?></div>
                <img src="<?php echo $ordner; ?>" style="height:60%">
                <div class="text">
                    <?php echo<<<EOT
                    $buchname <br>
                    "$beschreibung"
                    EOT;?></div>
                </div>

                </div>
                <?php 
            endif;
            $dbOutput->close();
        endif;
    endif;
?>