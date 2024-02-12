<?php
$display = "d-none";
$message = "";
$success = "success";
$virheet = [];
$virheilmoitukset = [];
$pattern = [];
$numeeriset = [];
$arvot = [];

$lomakekentat = ['firstname', 'lastname', 'email', 'password', 'phonenumber'];
$pakolliset = ['firstname', 'lastname', 'email', 'password'];
$pattern['firstname'] = "/^[a-zåäöA-ZÅÄÖ0'\- ]{1,25}$/";
$pattern['lastname'] = "/^[a-zåäöA-ZÅÄÖ0'\- ]{1,50}$/";
$pattern['password'] = "/^.{12,}$/";
$pattern['password2'] = $pattern['password'];
$pattern['phonenumber'] = "/^[0-9]{7,15}$/";
$pattern['email'] = "/^[a-zA-Z_]+[a-zA-Z0-9_\.+-]*@[a-zA-Z_]+(\.[a-zA-Z0-9_-]{2,})?\.[a-zA-Z]{2,}$/";

if (isset($_POST['button'])) {
    foreach ($lomakekentat as $kentta) {
        if (in_array($kentta, $pakolliset) && (!isset($_POST[$kentta]) || empty($_POST[$kentta]))) {
            $virheet[$kentta] = true;
            $virheilmoitukset[$kentta] = "Kenttä ".ucfirst($kentta)." on pakollinen";  
        }
     }
    debuggeri($virheilmoitukset);    
    if (!$virheet) {
        foreach ($lomakekentat as $indeksi => $kentta) {
            if (isset($_POST[$kentta])) {
                if (validoi($kentta,$_POST[$kentta])) {
                    $arvot[$kentta] = "'".puhdista($yhteys, $_POST[$kentta])."'";
                    }
                else {
                    $virheet[$kentta] = true;
                    $virheilmoitukset[$kentta] = "Kenttä ".ucfirst($kentta)." on virheellinen";
                    }
                }
            else {
               //debuggeri("Puuttuu:$kentta"); 
               unset($lomakekentat[$indeksi]);
               }
            }
        }    

    if (empty($virheet) && empty($virheet['password2']) and empty($virheet['password'])) {
        if ($_POST['password'] != $_POST['password2']) {
            $virheilmoitukset['password2'] = "Salasanat eivät täsmää";
            $virheet['password2'] = true;
            }
        }
        
    debuggeri($lomakekentat); 
    debuggeri($arvot);
      
    if (empty($virheet)){    
        $email = $arvot['email'];
        $query = "SELECT 1 FROM users WHERE email = $mail LIMIT 1";
        debuggeri($query);
        $result = query_oma($yhteys,$query);
        if ($result && $result->num_rows > 0) {
            $virheilmoitukset['email'] = "Sähköpostiosoite on varattu.";
            $virheet['email'] = true;
            }
        }

    if (empty($virheet)) {
        $kentat = implode(",",$lomakekentat);
        $arvot = implode(",",$arvot);
        $query = "INSERT INTO users ($kentat) VALUES ($arvot)";
        debuggeri($query);
        $result = query_oma($yhteys,$query);
        debuggeri("result:$result, affected_rows:".$yhteys->affected_rows);
        if ($result) {
            $message = "Käyttäjän $etunimi $sukunimi tiedot on tallennettu. Vahvista
                        sähköpostiosoitteesi sinulle lähetetystä linkistä.";
            $success = "success";
            header("Location: ./lisayslomake.php?message=$message&success=$success");
            }  
        else {
            $message = "Käyttäjän $etunimi $sukunimi lisäys epäonnistui. Tarkista tiedot ja yritä uudelleen.";   
            $success = "danger";
            }
        $display = "";
        }
    }


?>