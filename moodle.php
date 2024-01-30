<?php
include "db.php";
$pattern = [];
$virheet = [];
$pattern['rekisterinro'] = "/^[A-ZÅÄÖ]{3}-[0-9]{3}$/";
$pattern['merkki'] = "/^[a-zA-ZåäöÅÄÖ0-9 ]{1,50}$/";
$pattern['vari'] = "/^[a-zA-ZåäöÅÄÖ0-9 ]{1,30}$/";
$pattern['hetu'] = "/^(0[1-9]|[12]\d|3[01])(0[1-9]|1[0-2])(\d{2})";
$pattern['hetu'].= "([-+A])(\d{3})([0-9A-Y])$/";
$pattern['omistaja'] = $pattern['hetu'];

function validoi($kentta) {
if (!preg_match($GLOBALS['pattern'][$kentta], $$kentta)) {
    $GLOBALS['virheet'][$kentta] = true;
    } 
return;
}

if ($yhteys) {
    $query = "SELECT * FROM auto";
    $result = $yhteys->query($query);
    if ($result->num_rows > 0) {
        echo "<table><tr><th>Rekisterinumero</th><th>Merkki</th><th>Vuosimalli</th></tr>";
        while($row = $result->fetch_array()) {
            echo "<tr><td>".$row["rekisterinro"]."</td><td>".$row["merkki"]."</td><td>".$row["vuosimalli"]."</td><td></td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    //$yhteys->close();
}


$query = "SELECT * FROM auto";
$result = $yhteys->query($query);
if ($result->num_rows > 0) {
   // hae joka silmukan kierroksella uusi rivi
   while ($row = $result->fetch_assoc()) {
      // taulukon avaimet (hakasuluissa olevat nimet) ovat TIETOKANNAN KENTTIÄ (sarakkeita)
      echo "Rekisterinumero: " . $row["rekisterinro"]. " - Merkki: " . $row["merkki"]. " - Väri: " . $row["vari"]. "<br>";
   }
} else {
   echo "Ei tuloksia";
}

//'CES-267', 'Volvo', 'sininen','010100-123A'
$rekisterinro = puhdista($yhteys,$_POST["rekisterinro"]);
$merkki = puhdista($yhteys,$_POST["merkki"]);
$vari = puhdista($yhteys,$_POST["vari"]);
$omistaja = puhdista($yhteys,$_POST["omistaja"]);

validoi('omistaja');
validoi('rekisterinro');    
validoi('merkki');
validoi('vari');
 
if (!$virheet) {
  $query = "INSERT INTO auto (rekisterinro, merkki, vari, omistaja) VALUES ('$rekisterinro', '$merkki', '$vari','$omistaja')";
  $result = query_oma($yhteys, $query);
  if ($result === true) echo "Auto lisätty.";
  else echo "Virhe: $query <br> $yhteys->error";
  }

$taulukko = [
"Etusivu" => "index.php",
"Tuotteet" => "tuotteet.php",
"Ota yhteyttä" => "yhteydenotto.php",
"Sivumalli" => "sivumalli.php",
"phpinfo" => "phpinfo.php"
];

foreach ($taulukko as $avain => $arvo) {
echo "<a href='$arvo'>$avain</a><br>";
}

array_walk($taulukko, function ($v, $k) { echo "$k : $v", "<br>"; });

for($i=1; $i <= 10; $i++) {
    echo "$i ";
 }


 function summajaerotus($luku1, $luku2) {
    $summaluku = $luku1 + $luku2;
    $erotusluku = $luku1 - $luku2;
    return [$summaluku, $erotusluku];
 }

    [$summa,$erotus] = summajaerotus(10, 5);
    echo "Summa on $summa ja erotus on $erotus";

/* Lomakekäsittelijä */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nimi = $yhteys->real_escape_string(strip_tags($_POST["nimi"]));

    header("Location: ./moodle.php?nimi=$nimi");
    }
elseif (isset($_GET['nimi'])) {
    $nimi = $_GET["nimi"];
    echo "<br>Hei $nimi<br>";
}

?>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">     
<title>PHP</title>
</head>   
<body>
<form method="post" action="">
  <input type="text" name="nimi" value="<?php $nimi;?>" required>
  <input type="text" name="sahkoposti" value="<?php $sahkoposti;?>" required>
  <input type="file" name="kuva" value="<?php $kuva;?>">
  <input type="submit">
</form>
</body>
</html>
