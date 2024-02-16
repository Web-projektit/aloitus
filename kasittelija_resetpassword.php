<?php
$display = "d-none";
$message = "";
$success = "success";
$muutettu = $poistettu_token = false;
$virheet_palvelin['invalidLink'] = "Salasanan aktivointilinkki ei ole voimassa.";
$virheet_palvelin['invalidToken'] = "Linkki on virheellinen.";

$virheet = [];
$virheilmoitukset = [];
$pattern = [];
$arvot = [];
$lisays = $lisattiin_token = $lahetetty = 0;
$pattern['password'] = "/^.{12,}$/";
$pattern['password2'] = $pattern['password'];

$token = $_GET['token'] ?? '';
if ($token) {
   /* Haetaan email */
   $date = date('Y-m-d');
   $token = puhdista($yhteys, $token);
   $query = "SELECT users_id FROM resetpassword_tokens WHERE token = '$token' AND voimassa >= '$date'";
   debuggeri($query);
   $result = query_oma($yhteys,$query);
   if (![$users_id] = $result->fetch_row()){
      debuggeri("Virheellinen token.");  
      $message = $virheet_palvelin['invalidLink'];
      $display = "d-block";
      $success = "danger";
      }
    } 
else {
    $message = $virheet_palvelin['invalidToken'];
    $display = "d-block";
    $success = "danger";
    }   

if (isset($_POST['painike']) and !$message){
    
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
                    $arvot[$kentta] = puhdista($yhteys, $_POST[$kentta]);
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

debuggeri($virheet);    
if (empty($virheet)) {
    $password = $arvot['password'];
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET password = '$password' WHERE id = $users_id";
    $result = query_oma($yhteys,$query);
    $muutettu = $yhteys->affected_rows;
    }

if ($muutettu) {
    $query = "DELETE FROM resetpassword_tokens WHERE users_id = $users_id";
    debuggeri($query);
    $result = $yhteys->query($query);
    $poistettu_token = $yhteys->affected_rows;
    debuggeri("Poistettiin $poistettu_token token.");
    /* Huom. tässä siirrytään suoraan kirjautumissivulle. */
    header("location: login.php");
    exit;
    }
}
?>