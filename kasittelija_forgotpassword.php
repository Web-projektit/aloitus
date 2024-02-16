<?php
$display = "d-none";
$message = "";
$success = "success";
$virheet = [];
$virheilmoitukset = [];
$pattern = [];
$arvot = [];
$lisays = $lisattiin_token = $lahetetty = 0;
$pattern['password'] = "/^.{12,}$/";
$pattern['password2'] = $pattern['password'];
$ilmoitukset['okMsg'] = 'Salasanan asetuslinkki on lähetetty antamaasi sähköpostiosoitteeseen. '; 
$ilmoitukset['okMsg'].= 'Tarkista sähköpostisi, ja siirry linkistä asettamaan uusi salasana.';
$virheilmoitukset['accountNotExistErr'] = "Tuntematon sähköpostiosoite"; 
$virheilmoitukset['accountExistsMsg'] = "Sähköposti on lähetetty antamaasi sähköpostiosoitteeseen";   
$virheilmoitukset['verificationRequiredErr'] = "Vahvista sähköpostiosoite ensin";
$virheilmoitukset['emailErr'] = "Sähköpostin lähetys epäonnistui, yritä myöhemmin uudelleen";


/* ALOITUS */  
if (isset($_POST['painike'])){

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
      
   if (!$virheet){
      $lisattiin_token = false;
      $email = $arvot['email'];
      $query = "SELECT id FROM users WHERE email = '$email'";
      $result = query_oma($yhteys,$query);
      if(!$result) die("Tietokantayhteys ei toimi: ".$yhteys->error);
      if (!$result->num_rows) {
         debuggeri("$email:$virheilmoitukset[accountNotExistErr]");
         $message =  $virheilmoitukset['accountNotExistErr'];
         $success = "danger";
         $display = "d-block";
         }
      else {
         list($id) = $result->fetch_row();
         $token = bin2hex(random_bytes(50));
         //$voimassa = date('Y-m-d', strtotime("+1 day"));
         $voimassa = date('Y-m-d');
         $msg = "Aseta uusi salasana alla olevasta linkistä:<br><br>";
         $msg.= "<a href='http://$PALVELIN/$PALVELU/$LINKKI_RESETPASSWORD?token=$token'>Uusi salasana</a><br>";
         $subject = "Salasanasi";
         $lahetys = posti($email,$msg,$subject);
         if ($lahetys) {
         /* Lisää resetpassword_tokens tauluun id ja token */
            $query = "INSERT INTO resetpassword_tokens (users_id,token,voimassa) VALUES ($id,'$token','$voimassa') 
                      ON DUPLICATE KEY UPDATE token = '$token',voimassa = '$voimassa'";
            debuggeri($query);          
            $result = query_oma($yhteys,$query);
            $lisattiin_token = $yhteys->affected_rows;
            debuggeri("Lisättiin $lisattiin_token token.");
            }
         else {
            $message = $virheilmoitukset['emailErr'];
            $success = "danger";
            }
         if ($lisattiin_token){
            $message = $ilmoitukset['okMsg'];
            }   
         $display = "d-block";
         }
      }  
   }   
?>