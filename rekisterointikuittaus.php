<?php 
$title = 'Kuvagalleria';
$css = 'kuvagalleria.css';
include "header.php"; 
?>
<div class="container"> 
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
<?php } ?>
</div>
<?php include "footer.html"; ?>