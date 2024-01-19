<?php
$title = "Ota yhteyttä";
include "header.php";
/*
- nimi
- sähköposti
- aihe: kysymys tuotteista / tilaus / yhteydenottopyyntö / muu (pudotusvalikko)
- viesti (tekstialue)
- "Haluan tilata Puutarhaliike Neilikan uutiskirjeen" (kyllä/ei -valintaruutu)
*/
?>
<div class="container" id="root">
<h1>Yhteydenotto</h1>   
<form class="mb-3 needs-validation" novalidate action="yhteydenotto.php" method="post">

<div class="row">
<label class="form-label col-sm-4" for="nimi">Nimi</label>
<div class="col-sm-8">
<input class="form-control" type="text" name="nimi" id="nimi" autofocus required>
<div class="invalid-feedback">
Please choose a username.
</div>
</div>
</div>

<div class="row">
<label class="form-label col-sm-4" for="email">Sähköposti</label>
<div class="col-sm-8">
<input class="form-control" type="email" name="email" id="email" required>
<div class="invalid-feedback">
Please give your email address.
</div>
</div>
</div>

<div class="row">
<label class="form-label col-sm-4" for="aihe">Aihe</label>
<div class="col-sm-8">
<select class="form-select" name="aihe" id="aihe">
<option value="kysymys">Kysymys tuotteista</option>
<option value="tilaus">Tilaus</option>
<option value="yhteydenottopyynto">Yhteydenottopyyntö</option>
<option value="muu">Muu</option>
</select>
</div>
</div>

<div class="row">
<label class="form-label col-sm-4" for="viesti">Viesti</label>
<div class="col-sm-8">
<textarea class="form-control" name="viesti" id="viesti" rows="5"></textarea>
</div>
</div>

<div class="row">
<div class="col-sm-4"></div>
<div class="form-check col-sm-8">
<input class="form-check-input" type="checkbox" name="uutiskirje" id="uutiskirje">
<label class="form-check-label" for="uutiskirje">Haluan tilata Puutarhaliike Neilikan uutiskirjeen</label>
</div>
</div>

<div class="col-12">
<button class="btn btn-primary" type="submit">Lähetä</button>
</div>

</form>
</div>
<?php
include "footer.html";
?>
