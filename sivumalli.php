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
<title>Web-ohjelmointikurssin aloitus</title>
<script>
</script>
</head>

<body>
<nav>
<input type="checkbox" id="toggle-btn">
<label for="toggle-btn" class="icon open"><i class="fa fa-bars"></i></label>
<label for="toggle-btn" class="icon close"><i class="fa fa-times"></i></label>
<?php
echo "<a class='".active('index',$active). "' href='index.php'>Kotisivu</a>";
/* Huom. tästä oikeaan laitaan. */
echo "<a class='nav-suojaus ".active('phpinfo',$active). "' href='phpinfo.php'>phpinfo</a>";
?>
</nav>


<!-- Kommentti -->
<main class="container" id="root">


<h1><i>Web-ohjelmointikurssin</i> html-sivumalli</h1>    
</main>

<footer>
<p>&copy; 2024, Web-ohjelmointikoulutus</p>
</footer>
</body>
</html>
