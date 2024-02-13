<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';
//$to = $EMAIL_ADMIN;

function posti($emailTo,$msg,$subject){
$emailFrom = "wohjelmointi@gmail.com";
$emailFromName = "Ohjelmointikurssi";
$emailToName = "";
/* Generoidaan fatal error shutdown function-testaamiseksi. */
/* throw new Error('Tästä seuraisi fatal error, sillä kutsu on try-catchin ulkopuolella.'); */
try {
    $mail = new PHPMailer();
    $mail->Timeout = 10;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Port = EMAIL_PORT;
    $mail->Host = EMAIL_HOST;
    $mail->Username = EMAIL_USERNAME;
    $mail->Password = EMAIL_PASSWORD;
    $mail->CharSet = 'UTF-8';
    $mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
    $mail->SMTPSecure = 'tls'; 
    $mail->setFrom($emailFrom, $emailFromName);
    $mail->addAddress($emailTo, $emailToName);
    $mail->Subject = $subject;
    $mail->msgHTML($msg); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
    $mail->AltBody = 'HTML messaging not supported';
    // $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
    // $max_execution_time = ini_get('max_execution_time'); 
    // set_time_limit(0);    
    // ini_set('max_execution_time',10); 
    if (!$tulos = $mail->send()){
        //$tulos = false;
        debuggeri("Viestiä ei lähetetty: ".$mail->ErrorInfo);
        }    
    else {
        //$tulos = true;
        debuggeri("Viesti lähetetty: $emailTo!");
        }   
    // set_time_limit($max_execution_time); 
    }

catch (Exception $e) {
    $tulos = false;
    debuggeri(__FUNCTION__.','.$e->errorMessage()); 
    debuggeri(__FUNCTION__.',',$e->getMessage()); 
   } 

return $tulos;
}


?>