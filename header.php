<?php
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

window.onload = function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  // Huom. forEach-metodi toimii nodeListin kanssa v.2020 alkaen.
  // Array.prototype.slice.call(forms)
  forms.forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
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
/* Huom. tästä oikeaan laitaan. */
echo "<a class='nav-suojaus ".active('phpinfo',$active). "' href='phpinfo.php'>phpinfo</a>";
?>
</nav>