<?php
$yhteys = new mysqli($db_server, $db_username, $db_password, $DB);
if ($yhteys->connect_error) {
    die("Yhteyden muodostaminen epäonnistui: " . $yhteys->connect_error);
    }
$yhteys->set_charset("utf8");

function db_connect(){
return $GLOBALS['yhteys'];   
}

function query_oma($yhteys, $query) {
/* Suorittaa kyselyn poimien hallitusti fatal-virheet, joita
   voisi syntyä esim. viiteavaimien estämissä kyselyissä. */
    try {
        $result = $yhteys->query($query);
        return $result;
        } 
    catch (Throwable $e) {
        if ($yhteys->errno === 1062) {
            // Handle the duplicate entry scenario
            echo "Samat tiedot ovat jo olemassa. Yritä uudelleen.<br>";
            debuggeri("Virhe kyselyssä $query:\n".$e->getMessage());
            }
        else {
           echo "Virhe tai poikkeus napattu: " . $e->getMessage() . "<br";
           debuggeri("Virhe kyselyssä $query:\n".$e->getMessage());
            }
        return false;
        }   
    }

function puhdista($yhteys, $data) {
/* Estää SQL-injektiot. */ 
    if (is_array($data)) $data = implode(",",$data);    
    $data = strip_tags(trim($data));
    $data = $yhteys->real_escape_string($data);
    return $data;
}
?>