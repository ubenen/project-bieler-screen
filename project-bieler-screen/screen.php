<?php
    //Initialisiert Session um Seitenübergreifenden Anmeldestatus zu ermöglichen
    session_start();

    //Fügt Header Ein, definiert Datenbankverbindung
    require "includes/datenbank.php";
    
    require "includes/slide.php";

    ?>
    <!DOCTYPE html>
        <html>
        <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>

        marquee {
        width: 100%;
        background: lightblue;
        /*text-transform: uppercase;*/
        position: relative;
        padding: 18px 0;
        overflow: hidden;
        margin: 1rem 0;
        }

        /*marquee {
        display: relative;
        width: 100%;
        padding: 18px 0;
        background-color: lightblue;
        }*/

        * {box-sizing: border-box;}
        body {font-family: Verdana, sans-serif;
        background-color: rgb(189, 192, 192);
        }
        
        .mySlides {display: none;}
        img {vertical-align: middle;}

        /* Slideshow container */
        .slideshow-container {
        max-width: 1000px;
        position: relative;
        margin: auto;
       
        }

        /* kleiner text im bild */
        .text {
        color: #ffffff;
        font-size: 40px;
        padding: 12px 12px;
        position: absolute;
        bottom: 8px;
        width: 50%;
        text-align: center;
        border: 1px solid #f2f2f2;
        /*background: red;*/
        background-color: red;
        opacity: 0.6;
        }

        /* Nummertext (1/3 etc) */
        .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        top: 0;
        }

        /* die Punkte */
        /*.dot {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        transition: background-color 0.6s ease;
        }*/

        .active {
        background-color: #717171;
        }

        /* Fading animation */
        .fade {
        -webkit-animation-name: fade;
        -webkit-animation-duration: 1.5s;
        animation-name: fade;
        animation-duration: 1.5s;
        }
        


        @-webkit-keyframes fade {
        from {opacity: .4} 
        to {opacity: 1}
        }

        @keyframes fade {
        from {opacity: .4} 
        to {opacity: 1}
        }


        /* auf kleineren bildschirmen muss die schriftgröße angepasst werden */
        @media only screen and (max-width: 300px) {
        .text {font-size: 11px}
        
        }
        </style>
        </head>
<?php
    require 'includes/slideGenerator.php';

            //Falls anmeldung noch nicht TRUE ist, wird das Login Feld angezeigt
            if (!isset($_SESSION['anmeldung'])) :
            ?>
                <!-- Passwortformular -->
                <form action="login.php" method="POST">

                <p><label for="passwort">Passwort eingeben</label></p>
                <p><input type="password" id="passwort" name="passwort"></p>
                <p><button type="submit">Bestätigen</button></p>
                

            <?php

            //Falls Nutzer bereits angemeldet ist, wird statt dem Anmeldebereich ein Link zum Adminbereich angezeigt
            elseif (isset($_SESSION['anmeldung'])) :
                echo "<p><a href='admin.php'>Zum Adminbereich</a></p>";
            endif;

    //Footer mit HTML code wird importiert
    require "includes/footer.php";
?>