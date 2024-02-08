<?php
$display = "d-none";
$message = "";
$success = "success";
$virheet = [];
$virheilmoitukset = [];
$pattern = [];
$numeeriset = [];

$lomakekentat = ['title', 'description', 'release_year', 'language_id', 'rental_duration', 'rental_rate', 'length', 'replacement_cost', 'rating', 'special_features'];
$pakolliset = ['title', 'release_year', 'language_id', 'rental_duration', 'rental_rate', 'length', 'replacement_cost', 'rating', 'special_features'];
$numeeriset = ['rental_duration', 'rental_rate', 'length', 'replacement_cost']; 
$pattern['title'] = "/^[a-zåäöA-ZÅÄÖ0-9\s\-\.\,\!\?]{1,50}$/";
$pattern['description'] = "/^[a-zåäöA-ZÅÄÖ0-9\s\-\.\,\!\?]{0,255}$/";
$pattern['release_year'] = "/^[0-9]{4}$/";
$pattern['language_id'] = "/^[0-9]{1,3}$/";
$pattern['rental_duration'] = "/^[0-9]{1,3}$/";
$pattern['rental_rate'] = "/^(0|[1-9]\d*)([\.,]\d+)?$/";
$pattern['length'] = "/^[0-9]{1,3}$/";
$pattern['replacement_cost'] = $pattern['rental_rate'];
$pattern['rating'] = "/^[a-zA-Z0-9\s\-]{1,5}$/";
$pattern['special_features'] = "/^[a-zA-Z0-9\s\-\.\,\!\?]{1,50}$/";

if (isset($_POST['button'])) {
    foreach ($lomakekentat as $kentta) {
        if (in_array($kentta, $pakolliset) && (!isset($_POST[$kentta]) || empty($_POST[$kentta]))) {
            $virheet[$kentta] = true;
            $virheilmoitukset[$kentta] = "Kenttä ".ucfirst($kentta)." on pakollinen";
            }
        }
 
    if (!$virheet) {
        foreach ($lomakekentat as $kentta) {
            if (isset($_POST[$kentta])) {
                if (validoi($kentta,$_POST[$kentta])) {
                    $arvot[$kentta] = "'".puhdista($yhteys, $_POST[$kentta])."'";
                    }
                else {
                    $virheet[$kentta] = true;
                    $virheilmoitukset[$kentta] = "Kenttä ".ucfirst($kentta)." on virheellinen";
                }
            }
        }
    
    if (empty($virheet)){    
        $title = $arvot['title'];
        $language_id = $arvot['language_id'];
        $query = "SELECT 1 FROM film WHERE title = $title AND language_id = $language_id LIMIT 1";
        debuggeri($query);
        $result = query_oma($yhteys,$query);
        if ($result && $result->num_rows > 0) {
            $virheilmoitukset['title'] = "Elokuva on jo tietokannassa";
            $virheet['title'] = true;
            }
        }

    if (empty($virheet)) {
        foreach ($numeeriset as $kentta) {
            if (isset($arvot[$kentta]))
                $arvot[$kentta] = desimaali($arvot[$kentta]);   
            }
        $kentat = implode(",",$lomakekentat);
        $arvot = implode(",",$arvot);
        $query = "INSERT INTO film ($kentat) VALUES ($arvot)";
        debuggeri($query);
        $result = query_oma($yhteys,$query);
        debuggeri("result:$result, affected_rows:".$yhteys->affected_rows);
        if ($result) {
            $message = "Elokuvan $title tiedot lisättiin tietokantaan";
            $success = "success";
            }  
        else {
            $message = "Elokuvan $title lisäys epäonnistui. Tarkista tiedot ja yritä uudelleen.";   
            $success = "danger";
            }
        $display = "";
        header("Location: ./lisayslomake.php?message=$message&success=$success");
        }
    }
}