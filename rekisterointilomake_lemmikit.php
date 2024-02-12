<?php
$title = "Rekisteröintilomake";
$virheilmoitukset = [];
$virheilmoitukset['nimi'] = "Nimi saa sisältää vain kirjaimia, välilyöntejä, tavuviivoja ja heittomerkkejä.";  
$virheilmoitukset['salasana'] = "Salasanassa tulee olla vähintään 12 merkkiä.";  
$virheilmoitukset['sahkoposti'] = "Sähköpostiosoite ei ole oikeassa muodossa.";  
$lomakekentat = ['nimi','sahkoposti','salasana','maakunta','lemmikit','kuvaus'];
$pakolliset = ['nimi','sahkoposti','salasana','maakunta','lemmikit'];
include "header.php";
include "db.php";
include "lomakerutiinit.php";
$pattern = [];
$virheet = [];

$pattern['nimi'] = "/^[a-zA-ZåäöÅÄÖ'\- ]{1,50}$/";
$pattern['salasana'] = "/^[^\s]{12,255}$/";
/* Huom. HTML5-validointi ei tunne \w-lyhennysmerkkiä. */   
$pattern['sahkoposti'] = "/^[a-zA-Z_]+[a-zA-Z0-9_\.+-]*@[a-zA-Z_]+(\.[a-zA-Z0-9_-]{2,})?\.[a-zA-Z]{2,}$/";

//$virheet['nimi'] = true;


$maakunnat = ['Uusimaa','Varsinais-Suomi','Satakunta','Kanta-Häme','Pirkanmaa','Päijät-Häme','Kymenlaakso','Etelä-Karjala','Etelä-Savo','Pohjois-Savo','Pohjois-Karjala','Keski-Suomi','Etelä-Pohjanmaa','Pohjanmaa','Keski-Pohjanmaa','Pohjois-Pohjanmaa','Kainuu','Lappi','Ahvenanmaa'];
sort($maakunnat);
$lemmikit = ['koira','kissa','matelija','jyrsijä','kala','muu'];

/* Lomakekäsittelijä */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($pakolliset as $kentta) {
        if (!isset($_POST[$kentta]) || empty($_POST[$kentta])) {
            $virheet[$kentta] = true;
            $virheilmoitukset[$kentta] = "$kentta puuttuu.";
            }
    foreach ($lomakekentat as $kentta) {
        if (!$virheet[$kentta]){
            $arvo = $_POST[$kentta] ?? "";
            // ..jatkuu tästä..
            if (!is_array($arvo))
                $$kentta = puhdista($yhteys, $arvo);
                if (!validoi($kentta)) {
                    $virheet[$kentta] = true;
                    }    
            else {
                /* Huom. tässä ei ole validointia taulukolle. */
                $$kentta = puhdista($yhteys, implode(",",$arvo));
                }
            }
        }
        /*
        $nimi = $_POST['nimi'];
        $salasana = $_POST['salasana'];
        $sahkoposti = $_POST['sahkoposti'];
        $maakunta = $_POST['maakunta'];
        $lemmikit = $_POST['lemmikit'];
        $kuvaus = $_POST['kuvaus'];
        $lemmikit = implode(", ", $lemmikit);
        $lemmikit = puhdista($yhteys, $lemmikit); 
        $kuvaus = puhdista($yhteys, $kuvaus);
        $maakunta = puhdista($yhteys, $maakunta);
        $sahkoposti = puhdista($yhteys, $sahkoposti);
        $nimi = puhdista($yhteys, $nimi);
        $salasana = puhdista($yhteys, $salasana);
        $salasana = password_hash($salasana, PASSWORD_DEFAULT);
       */

    if (!$virheet) {
        /* Salasanan hashaus, sijoita tämä arvot-taulukkoon oikein. */
        $salasana_hash = password_hash($salasana, PASSWORD_DEFAULT);            
        $kenttanimet = implode(",",$lomakekentat);
        $kenttaarvot = implode("','",$arvot);
        $arvot = "'$nimi','$sahkoposti','$salasana_hash','$maakunta','$lemmikit','$kuvaus'";
        $query = "INSERT INTO kayttajat ($kenttanimet) VALUES ('$kenttaarvot')";
        $result = query_oma($yhteys, $query);
        if ($result === true) echo "Käyttäjä lisätty.";
        else echo "Virhe: $query <br> $yhteys->error";
        }
    }
}
?>
<!-- Kommentti -->
<div class="container" id="root">
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
<fieldset>
<legend>Rekisteröidy</legend>
<?php
/*
<div class="row mb-2">
<label class="form-label col-sm-4" for="nimi">Nimi</label>
<div class="col-sm-8">
<input class="form-control<?= is_invalid('nimi'); ?>" type="text" name="nimi" id="nimi" <?= pattern('nimi'); ?>autofocus required <?= arvo('nimi'); ?>>
<div class="invalid-feedback"><?= virheilmoitus('nimi'); ?></div>
</div>
</div>
*/
input_kentta('nimi',autofocus:true);
input_kentta('sahkoposti');
input_kentta('salasana','password');
?>
<div class="row mb-2">
<label class="form-label col-sm-3" for="maakunta">Maakunta</label>
<div class="col-sm-8">
<select class="form-select<?= is_invalid('maakunta'); ?>" 
       name="maakunta" id="maakunta">
<option value="">Valitse maakunta</option>
<?php
foreach ($maakunnat as $maakunta) {
    $selected = ($maakunta == arvo('maakunta')) ? " selected" : "";
    echo "<option value=\"$maakunta\"$selected>$maakunta</option>";
    }
?>    
</select>
<div class="invalid-feedback"><?= virheilmoitus('nimi'); ?></div>
</div>
</div>

<div class="row mb-2">
<label class="form-label col-sm-3" for="lemmikit">Lemmikit</label>
<div class="col-sm-8">
<?php
foreach ($lemmikit as $lemmikki) {
    input_checkbox('lemmikit',$lemmikki,$lemmikki);
    }
?>
</div>
</div>

<?php
input_kentta('kuvaus','textarea',false);
?>

<div class="col-11 d-flex justify-content-end mt-4">
<button class="btn btn-primary me-4" type="submit">Lähetä</button>
</div>
</fieldset>
</form>

</div>
<?php
include "footer.html";
?>