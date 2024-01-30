<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nimi = $_POST["nimi"];
    $sahkoposti = $_POST["sahkoposti"];
    if (empty($sahkoposti)) {
        echo "Anna sähköpostiosoite";
    }
    else {
        echo "Hei $nimi, sähköpostiosoitteesi on $sahkoposti";
    }
    header("Location: ./moodle.php?nimi=$nimi");
    }
elseif (isset($_GET['nimi'])) {
    $nimi = $_GET["nimi"];
    echo "<br>Hei $nimi<br>";
}
?>