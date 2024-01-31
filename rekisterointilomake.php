<?php
$title = "Rekisteröintilomake";
$virheilmoitukset = [];
$virheilmoitukset['nimi'] = "Nimi saa sisältää vain kirjaimia, välilyöntejä, tavuviivoja ja heittomerkkejä.";  
$virheilmoitukset['sahkoposti'] = "Sähköpostiosoite ei ole oikeassa muodossa.";  
include "header.php";
include "db.php";
$pattern = [];
$virheet = [];

$pattern['nimi'] = "/^[a-zA-ZåäöÅÄÖ'\- ]{1,50}$/";
$pattern['sahkoposti'] = "/^[\w]+[\w.+-]*@[\w-]+(\.[\w-]{2,})?\.[a-zA-Z]{2,}$/";


function virhe($kentta) {
return (isset($GLOBALS['virheet'][$kentta])) ? "is-invalid" : "";
}

function virheilmoitus($kentta) {
return $GLOBALS['virheilmoitukset'][$kentta];
}

function pattern($kentta) {
return (isset($GLOBALS['pattern'][$kentta])) ?  
    "pattern=\"" . trim($GLOBALS['pattern'][$kentta],"/") . "\" " :
    "";
}

function validoi($kentta) {
if (!preg_match($GLOBALS['pattern'][$kentta], $$kentta)) {
    $GLOBALS['virheet'][$kentta] = true;
    } 
return;
}

?>
<!-- Kommentti -->
<div class="container" id="root">
<h1>Rekisteröintilomake</h1>   
<?php 
/*nimi (tekstikenttä) *
sähköposti (tekstikenttä) *
salasana (salasanakenttä) *
sukupuoli (radionappi mies, nainen, muu)
maakunta (pudotusvalikko) - luettelo maakunnista löytyy wikipediasta.
lemmikit (checkboxeja, koira, kissa, matelija, jyrsijä, kala, muu)
kuvaus (tekstialue)*/
?>
<form class="mb-3 needs-validation" novalidate action="rekisterointilomake.php" method="post">

<div class="row mb-2">
<label class="form-label col-sm-4" for="nimi">Nimi</label>
<div class="col-sm-8">
<input class="form-control <?= virhe('nimi'); ?>" type="text" name="nimi" id="nimi" <?= pattern('nimi'); ?>autofocus required>
<div class="invalid-feedback"><?= virheilmoitus('nimi'); ?></div>
</div>
</div>

<div class="row mb-2">
<label class="form-label col-sm-4" for="sahkoposti">Sähköpostiosoite</label>
<div class="col-sm-8">
<input class="form-control <?= virhe('sahkoposti'); ?>" type="text" name="sahkoposti" id="sahkoposti" <?= pattern('sahkoposti'); ?>autofocus required>
<div class="invalid-feedback"><?= virheilmoitus('sahkoposti'); ?></div>
</div>
</div>

<div class="col-12 d-flex justify-content-end">
<button class="btn btn-primary me-4" type="submit">Lähetä</button>
</div>
</form>

</div>
<?php
include "footer.html";
?>