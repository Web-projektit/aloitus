<?php
include 'db.php';
include 'header.php';
include 'haku.php';

?>
<div class="container" id="root">
<form class="row g-3 needs-validation" novalidate>
<fieldset>
<legend>Elokuvan haku</legend>

<div class="row mb-2">
<label for="title" class="form-label col-sm-3">Elokuvan nimi</label>
<div class="col-sm-8">
<input type="text" class="form-control" id="title" name="title" required>
<div class="invalid-feedback"></div>
</div>
</div>

<div class="col-11 d-flex justify-content-end mt-4">
<button class="btn btn-primary me-4" name="button" value="button" type="submit">Hae</button>
</div>
</fieldset>
</form>
</div>
<?php
if ($lomakekasittely && !$hakutulokset) {
    echo "<div class='contain'>";
    echo "<div class='alert alert-warning' role='alert'>";
    echo "Elokuvaa ei löytynyt.";
    echo "</div>";
    echo "</div>";
    }

if ($hakutulokset) {
    echo "<div class='container'>";
    echo "<h2>Hakutulokset</h2>";
    echo "<div class='row'>";
    foreach ($hakutulokset as $elokuva) {
        echo "<div class='flex-container'>";
        echo "<div class='card'>";
        //echo "<img src='img/$elokuva->img' class='card-img-top' alt='$elokuva->title'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title
        '>$elokuva->title</h5>";
        echo "<p class='card-text'>$elokuva->description</p>";
        echo "<a href='elokuva.php?id=$elokuva->film_id' class='btn btn-primary'>Lue lisää</a>"; 
        echo "</div>";
        echo "</div>";
        echo "</div>";
        }
    }
include "footer.html";
?>

