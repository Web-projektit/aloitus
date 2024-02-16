<?php
$title = "Rekisteröityminen";
$js = "salasanatarkistus.js";
$lomakekentat = ['firstname', 'lastname', 'email', 'password', 'mobilenumber'];
$pakolliset = ['firstname', 'lastname', 'email', 'password'];
include 'asetukset.php';
include 'db.php';
include 'header.php';
include 'lomakerutiinit.php';
include 'posti.php';
include 'rekisterointi.php';
?>
<div class="container" id="root">
<form id="rekisterointilomake" class="mb-3 needs-validation salasanat" novalidate action="rekisterointilomake.php" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Rekisteröityminen</legend>
<?php   
foreach ($lomakekentat as $kentta) $required[$kentta] = in_array($kentta, $pakolliset);
debuggeri($required);    
input_kentta('firstname',required:$required['firstname'],autofocus:true);
input_kentta('lastname',required:$required['lastname']);
input_kentta('email',required:$required['email']);
input_kentta('mobilenumber',required:$required['mobilenumber']);
input_kentta('password',type:"password",required:$required['password']);
input_kentta('password2',type:"password",required:$required['password'],label:"Salasana uudestaan");
?>
<div class="col-11 d-flex justify-content-end mt-4">
<button name='button' class="btn btn-primary me-4" type="submit">Rekisteröidy</button>
</div>
</fieldset>
</form>

<?php   
if (isset($_GET['message']) && isset($_GET['success'])) {
    $display = "";
    $message = $_GET['message'];
    $success = $_GET['success'];
    ?>
    <div id="ilmoitukset" class="alert alert-<?= $success ;?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
    <p><?= $message; ?></p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php   
}
?>
</div>
<?php
include "footer.html";
?>

