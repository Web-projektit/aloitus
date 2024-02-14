<?php
$admin_mail = "";
$smtpUsername = "";
$smtpPassword = "";
/* SendGrid */      
$password_sendgrid = "";    
$username_sendgrid = "apikey";
/* Mailtrap */
$username_mailtrap = '';
$password_mailtrap = '';
/* Database local */
$db_username_local = '';
$db_password_local = '';
$db_server_local = "127.0.0.1";
$site_local = "http://localhost";
/* Database azure */
if (strpos($_SERVER['HTTP_HOST'],"azurewebsites") !== false){
  $db_username_remote = "";
  $db_password_remote = "";
/* Database server port number */
  $db_server_remote = "localhost:xxxxx";
/* Site remote: ownwebapp.azurewebsites.net */  
  $site_remote = 'https://xxxxxx.azurewebsites.net';
  }

