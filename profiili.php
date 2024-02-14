<?php 
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page();
$title = 'Profiili';
$css = 'profiili.css';
include "header.php"; 
?>
<div class="container">
<!-- Kuva ja perustiedot -->
<img src="https://cdn.pixabay.com/photo/2019/07/02/03/10/highland-cattle-4311375_1280.jpg" alt="Profiilikuva" class="profile-image">
<div class="info-section">
    <div class="info-title">Nimi:</div>
    <div>Matti Meikäläinen</div>
</div>
<div class="info-section">
    <div class="info-title">Ammatti:</div>
    <div>Ohjelmistokehittäjä</div>
</div>
<!-- Yhteystiedot -->
<div class="info-section">
    <div class="info-title">Yhteystiedot:</div>
    <div>Email: matti.meikäläinen@example.com</div>
    <div>Puhelin: 040-1234567</div>
</div>
<!-- Harrastukset -->
<div class="info-section">
    <div class="info-title">Harrastukset:</div>
    <ul class="hobbies-list">
    <li>Koodaus</li>
    <li>Valokuvaus</li>
    <li>Matkustelu</li>
    <li>Lukeminen</li>
    </ul>
</div>
</div>
<?php include "footer.html"; ?>