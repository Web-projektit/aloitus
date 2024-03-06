<?php
include "debuggeri_simple.php";
/* Huom. suojatulla sivulla on asetukset,db,rememberme.php; */
debuggeri("loggedIn header.php:n alussa: ".($loggedIn ?? "ei asetettu")); 
if (!isset($loggedIn)){
  //include "db.php";
  debuggeri("header.php käynnistää istunnon");
  include "rememberme.php";
  $loggedIn = loggedIn();
  }
debuggeri("loggedIn:$loggedIn"); 
$active = basename($_SERVER['PHP_SELF'],'.php');
function active($sivu,$active){
    return $active == $sivu ? 'active' : '';  
    }
?>
<!DOCTYPE html>
<html lang="en"> <!--En on oikein-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta description="joku kuvausteksti" author="joku tekijä">
<link rel="icon" href="favicon.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
<link rel="stylesheet" href="navbar.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
<link rel="stylesheet" href="site.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<?php
if (isset($css)) echo "<link rel='stylesheet' href='$css'>";
if (isset($js)) echo "<script defer src='$js'></script>";
?>
<title><?=$title?></title>
<script>
let poista_invalid = event => {
/* 
Oma kuuntelijafunktio, jotta kuuntelijan voi myös poistaa. 
Tässä poistetaan palvelimen asettamat virheilmoitukset.
*/
  const input = event.target
  input.classList.remove('is-invalid')
  input.removeEventListener('input', poista_invalid)
  }

window.onload = function () {'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  /* Huom. forEach-metodi toimii nodeListin kanssa v.2020 alkaen.
     Array.prototype.slice.call(forms) */
  forms.forEach(form => {
    /* Kenttään kirjoitus poistaa virheilmoituksen palvelimelta. */
    form.querySelectorAll('.is-invalid').forEach(input => {
      input.addEventListener('input', poista_invalid)
      })

    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        
        /* Oletusvirheilmoitustekstit Javascriptistä,
           syrjäyttävät mahdolliset virheilmoitustekstit palvelimelta. */           
        form.querySelectorAll('.invalid-feedback').forEach(element => {
          const input = element.previousElementSibling
          element.textContent = input.validationMessage
          })

        /* Ei lomakkeen lähetystä, jos validointi ei mene läpi. */
        event.preventDefault()
        event.stopPropagation()
        }
      form.classList.add('was-validated')
      }, false)
    })
  }
</script>
</head>
<body>
<nav>
<a class="brand-logo" href="index.php">
<img src="omniamusta_tausta.png" alt="Logo"></a>
<input type="checkbox" id="toggle-btn">
<label for="toggle-btn" class="icon open"><i class="fa fa-bars"></i></label>
<label for="toggle-btn" class="icon close"><i class="fa fa-times"></i></label>
<?php
echo "<a class='".active('index',$active). "' href='index.php'>Etusivu</a>";
echo "<a class='".active('tuotteet',$active). "' href='tuotteet.php'>Tuotteet</a>";
echo "<a class='".active('yhteydenotto',$active). "' href='yhteydenotto.php'>Ota yhteyttä</a>";
echo "<a class='".active('sivumalli',$active). "' href='sivumalli.php'>Sivumalli</a>";
echo "<a class='".active('hakulomake',$active). "' href='hakulomake.php'>Sakila-haku</a>";
echo "<a class='".active('lisayslomake',$active). "' href='lisayslomake.php'>Sakila-lisäys</a>";
/* Huom. tästä oikeaan laitaan. */
switch ($loggedIn) {
  case 'admin':
    //echo "<a class='".active('kayttajat',$active). "' href='kayttajat.php'>Käyttäjät</a>";
  case true:
    echo "<a class='".active('profiili',$active). "' href='profiili.php'>Profiili</a>";
    /* Huom. tästä oikeaan laitaan. */
    echo "<a class='nav-suojaus ".active('phpinfo',$active). "' href='phpinfo.php'>phpinfo</a>";
    //echo "<a class='".active('fake',$active). "' href='fake.php'>fake</a>";
    echo '<a href="poistu.php">Poistu</a>';
    break;
  default:
    echo "<a class='nav-suojaus ".active('login',$active)."' href='login.php'>Kirjautuminen</a>";
    break;
} 

?>
</nav>