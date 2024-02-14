<?php
include "asetukset.php";
include "db.php";
include "rememberme.php";
/* Sessionin purkaminen */
if (!session_id()) session_start();
if ($user_id = loggedIn() === false) {
    header("location: login.php");
    exit;
    }
/*if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true){
    header("location: login.php");
    exit;
    }*/
include "debuggeri_simple.php";
debuggeri(__FUNCTION__.",session ja cookie");    
debuggeri($_SESSION);    
debuggeri($_COOKIE);    
// $user_id = $_SESSION['user_id'] ?? '';
if (is_int($user_id)) {
    include_once('db.php');
    delete_rememberme_token($user_id);
    }
if (isset($_COOKIE['rememberme'])) {
    unset($_COOKIE['rememberme']);
    setcookie('rememberme', null, -1, "", "", false, true);
    }
$_SESSION = [];
/* If it's desired to kill the session, also delete the session cookie.
    Note: This will destroy the session, and not just the session data. */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]);
    }   
session_destroy();
header('location:index.php');
?>