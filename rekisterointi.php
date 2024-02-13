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
$pattern['firstname'] = "/^[a-zåäöA-ZÅÄÖ0'\- ]{1,25}$/";
$pattern['lastname'] = "/^[a-zåäöA-ZÅÄÖ0'\- ]{1,50}$/";
$pattern['password'] = "/^.{12,}$/";
$pattern['password2'] = $pattern['password'];
$pattern['mobilenumber'] = "/^[0-9]{7,15}$/";
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
                    if ($kentta == 'password') {
                        $arvot[$kentta] = "'".password_hash(puhdista($yhteys, $_POST[$kentta]), PASSWORD_DEFAULT)."'";
                        }
                    else {
                        $arvot[$kentta] = "'".puhdista($yhteys, $_POST[$kentta])."'";
                        }
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
        $query = "SELECT 1 FROM users WHERE email = $email LIMIT 1";
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
        $lisays = $yhteys->affected_rows;
        if ($lisays > 0) {
            /*
            CREATE TABLE signup_tokens (
            token VARCHAR(255) NOT NULL,
            users_id INT NOT NULL,
            updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (token),
            FOREIGN KEY (users_id) REFERENCES users(id)
            )
            */
            $id = $yhteys->insert_id;
            $token = md5(rand().time());
            $query = "INSERT INTO signup_tokens (users_id,token) VALUES ($id,'$token')";
            debuggeri($query);
            $result = query_oma($yhteys,$query);
            $lisattiin_token = $yhteys->affected_rows;
            }
        if ($lisattiin_token > 0) {
            $msg = "Vahvista sähköpostiosoitteesi alla olevasta linkistä:<br><br>";
            $msg.= "<a href='http://$PALVELIN/$PALVELU/$LINKKI_VERIFICATION?token=$token'>Vahvista sähköpostiosoite</a>";
            $msg.= "<br><br>t. $PALVELUOSOITE";
            $subject = "Vahvista sähköpostiosoite";
            $lahetetty = posti(trim($email,"'"),$msg,$subject);
            }   
        if ($lahetetty){
            $message = "Tiedot on tallennettu. Sinulle on lähetty antamaasi sähköpostiosoitteeseen ";
            $message.= "vahvistuspyyntö. Vahvista siinä olevasta linkistä sähköpostiosoitteesi.";
            $success = "success";
            header("Location: ./lisayslomake.php?message=$message&success=$success");
            exit;
            }
        elseif ($lisays) {
            /* Huom. oikeammin ohjataan vahvistuspyyntöön */
            $message = "Tallennus onnistui!";
            $success = "success";
            }
        else {
            $message = "Tallennus epäonnistui!";
            $success = "danger";
            }
        $display = "d-block";
        }
    }
?>