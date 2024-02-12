<?php
$title = "Elokuvan lisääminen";
$js = "lisays.js";
include 'db.php';
include 'header.php';
include 'lomakerutiinit.php';
include 'lisays.php';


$kielioptiot = [];
$query = "SELECT language_id, name FROM language";
$result = $yhteys->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kielioptiot[$row['language_id']] = $row['name'];
    }
}

$ratingoptiot = ['G','PG','PG-13','R','NC-17'];
$special_features = ['Trailers','Commentaries','Deleted Scenes','Behind the Scenes'];
?>

<div class="container" id="root">
<form class="mb-3 needs-validation" novalidate action="lisayslomake.php" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Elokuvan lisääminen</legend>
<?php   
foreach ($lomakekentat as $kentta) {
    $required[$kentta] = in_array($kentta, $pakolliset) ? true : false;
    }
input_kentta('title',required:$required['title'],autofocus:true);
input_kentta('description','textarea',required:$required['description']);
input_kentta('release_year');
input_datalist('language_id',$kielioptiot,$required['language_id'],'language');
input_kentta('rental_duration');
input_kentta('rental_rate');
input_kentta('length');
input_kentta('replacement_cost');
?>
<div class="row mb-2">
<label class="form-label col-sm-3" for="rating">Rating</label>
<div class="col-sm-8">    
<?php
foreach ($ratingoptiot as $rating) {
    input_radio('rating',$rating,$rating,required:$required['rating']);
    }
?>
</div>
</div>
<div class="row mb-2">
<label class="form-label col-sm-3" for="special_features">Special Features</label>
<div class="col-sm-8">    
<?php
foreach ($special_features as $feature) {
    input_checkbox('special_features',$feature,$feature);
    }
echo '<div class="invalid-feedback">'.virheilmoitus('rating').'</div>';
?>
</div>
</div>

<div class="row mb-3">
    <label for="image" class="col-sm-3 form-label">Lisää kuva</label>
    <div class="col-sm-8">
        <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
        <div class="invalid-feedback"><?= virheilmoitus('image') ?></div>
        <img class="mt-3" id="preview" src="" alt="Image preview" style="max-width: 200px; max-height: 200px; display: none;">
        <button type="button" id="clearButton" style="display: none;" onclick="clearImage()">Tyhjennä kuva</button>
    </div>
</div>


<div class="col-11 d-flex justify-content-end mt-4">
<button name='button' class="btn btn-primary me-4" type="submit">Lisää</button>
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

