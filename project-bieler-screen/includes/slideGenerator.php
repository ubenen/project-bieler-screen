<?php
    require 'headerScreen.php';
    require 'datenbank.php';

    //Statement zählt Einträge in buecher
    $abfrage = "SELECT MAX(id) FROM buecher";
    
    //Bereitet Abfrage vor
    $countOutput = $datenbank->prepare($abfrage);

    //Führt Abfrage aus
    $countOutput->execute();

    //Bindet Ausgabe an Variable
    $countOutput->bind_result($eintragAnzahl);

    //Fetcht Ausgabe 
    $countOutput-> fetch();

    $countOutput->close();

    // require 'datenbank.php';

        for ($i = 0; $i <= $eintragAnzahl; $i++) :
            require 'slide.php';
        endfor;

        echo<<< EOT
        <marquee width="100%"  heigth="50px";>Antiquariat Bieler --- Beispielstraße 123 --- Geöffnet Mo-Fr 8:00-19:00 --- Seit 40 Jahren im Familienbesitz</marquee>
        
        EOT;
?>
    <script>
        var slideIndex = 0;
        showSlides();

        function showSlides() {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        // var dots = document.getElementsByClassName("dot");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";  
        }
        slideIndex++;
        if (slideIndex > slides.length) {slideIndex = 1}    
        // for (i = 0; i < dots.length; i++) {
        //     dots[i].className = dots[i].className.replace(" active", "");
        // }
        slides[slideIndex-1].style.display = "block";  
        // dots[slideIndex-1].className += " active";
        setTimeout(showSlides, 6000);} // alle 6 sekunden
    </script>

<?php
    require 'footer.php';
    ?>