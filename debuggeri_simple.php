<?php
if (!defined('DEBUG')) define('DEBUG',true);

function debuggeri($arvo){
if (defined('DEBUG') and !DEBUG) return;
$msg = is_array($arvo) ? var_export($arvo,true) : $arvo; 
$msg = date("Y-m-d H:i:s")." ".$msg;
file_put_contents("debug_log.txt", $msg."\n", FILE_APPEND);
}
?>