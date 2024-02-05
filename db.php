<?php
$palvelin = "localhost";
$kayttaja = "root";  
$salasana = "jukka1";
$tietokanta = "sakila";
$yhteys = new mysqli($palvelin, $kayttaja, $salasana, $tietokanta);
if ($yhteys->connect_error) {
    die("Yhteyden muodostaminen epäonnistui: " . $yhteys->connect_error);
    }
$yhteys->set_charset("utf8");

function query_oma ($yhteys, $query) {
/* Suorittaa kyselyn poimien hallitusti fatal-virheet, joita
   voisi syntyä esim. viiteavaimien estämissä kyselyissä. */
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
/* Estää SQL-injektiot. */    
    $data = strip_tags(trim($data));
    $data = $yhteys->real_escape_string($data);
    return $data;
}
?>