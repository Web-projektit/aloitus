<?php
$ohjelmointikielet = ["PHP","Javascript","Python","Java","C#","C++","C","Ruby","Perl","R","Swift","Scala","Go","Visual Basic","Kotlin","Rust","Haskell","Delphi","Clojure","Erlang","Julia","TypeScript","PowerShell","Dart","Bash","Lisp","Prolog","COBOL","Fortran","Ada","RPG","Pascal","Rexx","Smalltalk","Visual Basic","ActionScript","BASIC"]; 
?>  
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Web-ohjelmointikurssin aloitus</title>
<meta name="description" content="Web-ohjelmointikurssin HTML-aloitus.">
<meta name="author" content="Web-ohjelmoija">
<meta property="og:title" content="Web-ohjelmointikurssin aloitus">
<meta property="og:type" content="web-sivu">
<!--<meta property="og:url" content="http://127.0.0.1/aloitus/aloitus.html">-->
<meta property="og:description" content="HTML-malli.">
<!--<meta property="og:image" content="image.png">-->
<!--<link rel="stylesheet" href="site.css">-->
<!--<script src="scripts.js"></script>-->
<style>
div {font-family:Arial;}
label {font-weight:bold;display:inline-block;width:200px;}
.perusteksti {font-weight:normal;}
#root {font-weight:bold;}
#ohjelmointikieli {display:block;}
@media only screen and (min-width: 576px) {
#ohjelmointikieli {display:inline;}
}
</style>
<script>
function napsautus(){
alert("Napsautit siis minua.");
}

function filterDatalist() {
    var input = document.getElementById('ohjelmointikieli');
    var filter = input.value.toUpperCase();
    var datalist = document.getElementById('ohjelmointikielet');
    var options = datalist.options;
    //console.log(options);
    for (var i = 0; i < options.length; i++) {
        var option = options[i];
        if (option.value.toUpperCase().startsWith(filter)) {
            console.log("osuma: ",option.value);
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    }
}

</script>
</head>
<body>
<div id="root">
<p class="perusteksti" onclick="napsautus();" style="color:blue">Hello world!</p>
<label for="ohjelmointikieli">Valitse ohjelmointikieli</label>
<input list="ohjelmointikielet" name="ohjelmointikielet" id="ohjelmointikieli" onInput="filterDatalist()">
<datalist id="ohjelmointikielet">
<?php   
foreach($ohjelmointikielet as $kieli) {
echo "<option value='$kieli'>$kieli</option>";
}
?>
</datalist>

<?php
/* Tässä PHP-koodia */
foreach($ohjelmointikielet as $kieli) {
echo "<option value='$kieli'>$kieli</option>";
}
?>
</select>
</div>
</body>
</html>