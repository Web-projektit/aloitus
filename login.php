<?php
$title = "Kirjautuminen";
$lomakekentat = ['email', 'password','rememberme'];
$pakolliset = ['email', 'password'];
include 'asetukset.php';
include 'header.php';
include 'db.php';
include 'lomakerutiinit.php';
include 'rememberme.php';
include 'kirjautuminen.php';
?>
<div class="container" id="root">
<form id="kirjautuminen" class="mb-3 needs-validation" novalidate method="post">
<fieldset>
<legend>Kirjautuminen</legend>
<?php   
foreach ($lomakekentat as $kentta) {
    $required[$kentta] = in_array($kentta, $pakolliset) ? true : false;
    }
debuggeri($required);    
input_kentta('email',required:$required['email']);
input_kentta('password',type:"password",required:$required['password']);
//input_checkbox('rememberme','checked','Muista minut');
?>
<div class="col-11 d-flex justify-content-end mt-4">
<button name='button' class="btn btn-primary me-4" type="submit">Kirjaudu</button>
</div>
</fieldset>
</form>

    <div id="ilmoitukset" class="alert alert-<?= $success ;?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
    <p><?= $message; ?></p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

</div>
<?php
include "footer.html";
?>

