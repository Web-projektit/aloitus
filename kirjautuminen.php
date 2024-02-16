<?php
$display = "d-none";
$message = "";
$success = "success";
$virheet = [];
$virheilmoitukset = [];
$pattern = [];
$numeeriset = [];
$arvot = [];
$lisays = $lisattiin_token = $lahetetty = 0;
$pattern['password'] = "/^.{12,}$/";
$pattern['email'] = "/^[a-zA-Z_]+[a-zA-Z0-9_+.\-]*@[a-zA-Z_]+(\.[a-zA-Z0-9_\-]{2,})?\.[a-zA-Z]{2,}$/";
$pattern['rememberme'] = "/^checked$/";

//$virheilmoitukset['password']['patternMismatch'] = "Salasanan pitää olla vähintään 12 merkkiä pitkä";    
//$virheilmoitukset['email']['emailExistsError'] = "Sähköpostiosoite on jo käytössä";     
$virheilmoitukset['accountNotExistErr'] = "Tuntematon sähköpostiosoite"; 
$virheilmoitukset['accountExistsMsg'] = "Sähköposti on lähetetty antamaasi sähköpostiosoitteeseen";   
$virheilmoitukset['verificationRequiredErr'] = "Vahvista sähköpostiosoite ensin";
$virheilmoitukset['emailPwdErr'] = "Väärä käyttäjätunnus tai salasana";

$ilmoitukset['errorMsg'] = 'Kirjautuminen epäonnistui. '; 

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
        
    debuggeri($lomakekentat); 
    debuggeri($arvot);
      
   $rememberme = $arvot['rememberme'] ?? false;
   $email = $arvot['email'] ?? "";
   $password = $arvot['password'] ?? "";
   if ($virheet) debuggeri($virheet);
   if (!$virheet){
      $query = "SELECT users.id,password,is_active,name FROM users LEFT JOIN roles ON role = roles.id WHERE email = '$email'";
      debuggeri($query);
      $result = query_oma($yhteys,$query);
      if (!$result) die("Tietokantayhteys ei toimi: ".$yhteys->error);
      if (!$result->num_rows) {
         debuggeri("$email:$virheilmoitukset[accountNotExistErr]");
         $message =  $ilmoitukset['errorMsg'];
         $success = "danger";
         $display = "d-block";
         }
      else {
         [$id,$password_hash,$is_active,$role] = $result->fetch_row();
         if (password_verify($password, $password_hash)){
            if ($is_active){
               if (!session_id()) session_start();
               $_SESSION["loggedIn"] = $role;
               if ($rememberme) rememberme($id);
               if (isset($_SESSION['next_page'])){
                  $location = $_SESSION['next_page'];
                  unset($_SESSION['next_page']);
                  }
               else $location = OLETUSSIVU;   
               header("location: $location");
               exit;
               }      
            else {
               $virheilmoitukset['email'] = $virheilmoitukset['verificationRequiredErr'];
               $virheet['email'] = true;
               }
            }
         else {
            $virheilmoitukset['password'] = $virheilmoitukset['emailPwdErr'];
            $virheet['password'] = true;
            }
         }  
      }
    
    }
?>