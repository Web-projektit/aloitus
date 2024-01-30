<?php
$palvelin = "localhost";
$kayttaja = "root";  
$salasana = "jukka1";
$tietokanta = "autot";
$yhteys = new mysqli($palvelin, $kayttaja, $salasana, $tietokanta);
if ($yhteys->connect_error) {
    die("Yhteyden muodostaminen epäonnistui: " . $yhteys->connect_error);
    }
$yhteys->set_charset("utf8");

function query_oma ($yhteys, $query) {
    try {
        $result = $yhteys->query($query);
        return $result;
        } 
    catch (Throwable $e) {
        //echo "Virhe tai poikkeus nappattu: " . $e->getMessage();
        return false;
    }
}

function puhdista ($yhteys, $data) {
    $data = strip_tags(trim($data));
    $data = $yhteys->real_escape_string($data);
    return $data;
}
?>