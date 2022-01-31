<?php

    //Verbindung aufbauen
    $verbindung = new mysqli('localhost', 'root', '');

    //Verbindung prüfen
    if ($verbindung->connect_error) :
        die("Verbindung Fehlgeschlagen" . $verbindung->connect_error);
    endif;

    //Löscht vorhandene Datenbank
    $sqlCleanSlate = "DROP DATABASE IF EXISTS bieler;";

    //Code zum Datenbank erstellen wird in Variable gespeichert
    $sqlDatenbankErstellen = "CREATE DATABASE IF NOT EXISTS bieler;";

    //SQL Statement um Buchtabelle in bieler Datenbank zu erstellen wird in variable gespeichert
    $sqlBuecherTabelleErstellen = "CREATE TABLE IF NOT EXISTS buecher (
        id int(11) NOT NULL AUTO_INCREMENT,
        buchname varchar(255) NOT NULL,
        beschreibung varchar (255),
        preis double,
        ISBN varchar(255),
        autor varchar (255),
        verfuegbarkeit boolean,
        aktiviert boolean,
        PRIMARY KEY (id)
        );";
    
    $sqlPasswortTabelleErstellen = "CREATE TABLE IF NOT EXISTS passwort (
        id int(11) NOT NULL AUTO_INCREMENT,
        nutzer varchar(255) NOT NULL,
        hash varchar (255),
        PRIMARY KEY (id)
        );";

    $sqlBilderTabelleErstellen = "CREATE TABLE IF NOT EXISTS bilder (
        id int(11) NOT NULL AUTO_INCREMENT,
        ordner varchar(255) NOT NULL,
        artikelNr int (11),
        PRIMARY KEY (id)
        );";
    
    //SQL Statement um Einträge in Tabelle einzufügen wird in Datenbank gespeichert
    $sqlBuecherEintraegeEinfuegen = "INSERT INTO buecher (id, buchname, beschreibung, preis, ISBN, autor, verfuegbarkeit, aktiviert) VALUES 
    (1, 'Tales from Space', 'Tales from Space ist ein tolles Buch', 12.66, '1234567891011', 'Kacey Turing', 1, 1),
    (2, 'More Tales from Space', 'More Tales from Space ist ein sehr tolles Buch', 19.99, '1234567891012', 'Kacey Turing', 0, 1),
    (3, 'Fewer Tales from Space', 'Fewer Tales from Space ist ein sehr, sehr tolles Buch', 15.50, '1234567891013', 'Kacey Turing', 1, 0),
    (4, 'Gesammelte Werke von John Harrison III', 'Ein Buch, welches zum Nachdenken anregt, wenn auch nicht im Sinne des Autors', 59.99, '1234567891934', 'John Harrison', 1, 1),
    (5, 'Erben des Universums', 'Ein Manifest ohnegleichen, beeindruckend gestaltet', 45.99, '1234566391012', 'Karra-Bem', 1, 1),
    (6, 'Dieser und Jene', 'Blind entgegen der Welt um uns herum, umschlungen, im Bann der Schlange', 19.99, '1234567891012', 'Metat Aun', 0, 0),
    (7, 'Eifer der Kinder', 'Ein...seltsamer Titel', 0.01, '0000000000000', 'RA', 1, 1);";

    $sqlPasswortEintraegeEinfuegen = "INSERT INTO passwort (nutzer, hash) VALUES ('admin', '$2y$10$6t1ja4gcThOQ1f1PhzpqUud5I2zZLgXkREMEWdsEwUVJ1KcHsIrmG');";

    $sqlBildEintraegeEinfuegen = "INSERT INTO bilder (ordner, artikelNr) VALUES
    ('bilder/1.jpg', 1),
    ('bilder/2.jpg', 2),
    ('bilder/3.jpg', 3),
    ('bilder/4.jpg', 4),
    ('bilder/5.jpg', 5),
    ('bilder/6.jpg', 6),
    ('bilder/7.jpg', 7)
    ;";

    //Bestehende Datenbank wird gelöscht
    if ($verbindung->query($sqlCleanSlate) === TRUE) :
        echo "Bestehende Einträge gelöscht";

        //Datenbankerstellung, wird ausgeführt und Rückmeldung wird gegeben
        if ($verbindung->query($sqlDatenbankErstellen) === TRUE) :
            echo "<p>Datenbank erfolgreich erstellt</p>";

            //Verbindung wird geschlossen, und eine neue Verbindung zur gerade erstellten Datenbank wird aufgebaut
            $verbindung->close();
            $verbindung = new mysqli('localhost', 'root', '', 'bieler');
            
            //Die Tabellenerstellung, wird ausgeführt und Rückmeldung wird gegeben
            if ($verbindung->query($sqlBuecherTabelleErstellen) === TRUE && $verbindung->query($sqlPasswortTabelleErstellen) === TRUE && $verbindung->query($sqlBilderTabelleErstellen)) :
                echo "<p>Tabellen erfolgreich erstellt</p>";

                //Einfügen von Datensätzen, wird ausgeführt, und Rückmeldung wird gegeben
                if ($verbindung->query($sqlBuecherEintraegeEinfuegen) === TRUE && $verbindung->query($sqlPasswortEintraegeEinfuegen) === TRUE && $verbindung->query($sqlBildEintraegeEinfuegen)) :
                    echo "<p>Einträge eingefügt</p>";
                endif;
            endif;
        endif;
    endif;

    //Link zo Hauptansicht
    echo "<p><a href='screen.php'>Zur Ansicht</a></p>";
?>
