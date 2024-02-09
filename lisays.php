<?php
$display = "d-none";
$message = "";
$success = "success";
$virheet = [];
$virheilmoitukset = [];
$pattern = [];
$numeeriset = [];
$arvot = [];

$lomakekentat = ['title', 'description', 'release_year', 'language_id', 'rental_duration', 'rental_rate', 'length', 'replacement_cost', 'rating', 'special_features'];
$pakolliset = ['title', 'release_year', 'language_id', 'rental_duration', 'rental_rate', 'length', 'replacement_cost', 'rating', 'special_features'];
$numeeriset = ['rental_duration', 'rental_rate', 'length', 'replacement_cost']; 
$pattern['title'] = "/^[a-zåäöA-ZÅÄÖ0-9\s\-\.\,\!\?]{1,50}$/";
$pattern['description'] = "/^[a-zåäöA-ZÅÄÖ0-9\s\-\.\,\!\?]{0,255}$/";
$pattern['release_year'] = "/^[0-9]{4}$/";
$pattern['language_id'] = "/^[a-zA-Z]{1,20}$/";
//$pattern['language_id'] = "/^[0-9]{1,3}$/";
$pattern['rental_duration'] = "/^[0-9]{1,3}$/";
$pattern['rental_rate'] = "/^(0|[1-9]\d*)([\.,]\d+)?$/";
$pattern['length'] = "/^[0-9]{1,3}$/";
$pattern['replacement_cost'] = $pattern['rental_rate'];
$pattern['rating'] = "/^[a-zA-Z0-9\s\-]{1,5}$/";
$pattern['special_features'] = "/^[a-zA-Z0-9\s\-\.\,\!\?]{1,50}$/";


if (isset($_POST['button'])) {
    foreach ($lomakekentat as $kentta) {
        if (in_array($kentta, $pakolliset) && (!isset($_POST[$kentta]) || empty($_POST[$kentta]))) {
            $virheet[$kentta] = true;
            $virheilmoitukset[$kentta] = "Kenttä ".ucfirst($kentta)." on pakollinen";
            }
        }
 
    if (!$virheet) {
        foreach ($lomakekentat as $kentta) {
            if (isset($_POST[$kentta])) {
                if (validoi($kentta,$_POST[$kentta])) {
                    $arvot[$kentta] = "'".puhdista($yhteys, $_POST[$kentta])."'";
                    }
                else {
                    $virheet[$kentta] = true;
                    $virheilmoitukset[$kentta] = "Kenttä ".ucfirst($kentta)." on virheellinen";
                    }
                }
            }
        }    

    debuggeri($arvot);
    if (empty($virheet)) {
        $language_id = "";
        $language = $arvot['language_id'];
        $query = "INSERT INTO language (name) VALUES ($language) 
                  ON DUPLICATE KEY UPDATE language_id=LAST_INSERT_ID(language_id)";
        debuggeri($query);
        $result = query_oma($yhteys, $query);
        if ($result) {
            $language_id = $yhteys->insert_id;
            $arvot['language_id'] = $language_id;
            } 
        else {
            $virheilmoitukset['language_id'] = "Kielen lisäys epäonnistui";
            $virheet['language_id'] = true;
            }
        }    
    
    if (empty($virheet)){    
        $title = $arvot['title'];
        $language_id = $arvot['language_id'];
        $query = "SELECT 1 FROM film WHERE title = $title AND language_id = $language_id LIMIT 1";
        debuggeri($query);
        $result = query_oma($yhteys,$query);
        if ($result && $result->num_rows > 0) {
            $virheilmoitukset['title'] = "Elokuva on jo tietokannassa";
            $virheet['title'] = true;
            }
        }

    // Tarkista, onko tiedosto lähetetty
    if(isset($_FILES['image'])) {
        $errors = array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

        $extensions = array("jpeg", "jpg", "png");

        // Tarkista tiedostotyyppi
        if(in_array($file_ext, $extensions) === false){
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        // Tarkista tiedoston koko (2MB)
        if($file_size > 2097152){
            $errors[] = 'File size must be less than 2 MB';
        }

        // Jos ei ole virheitä, siirrä tiedosto ja päivitä tietokanta
        if(empty($errors) == true) {
            move_uploaded_file($file_tmp, "images/".$file_name);
            echo "Success";

            // Päivitä tietokanta
            /*
            $query = "UPDATE film SET image = '$file_name' WHERE film_id = $film_id";
            $result = mysqli_query($yhteys, $query);

            if (!$result) {
                die('Invalid query: ' . mysqli_error($yhteys));
            }
            } else {
            print_r($errors);
            }
            */
        }


    if (empty($virheet)) {
        foreach ($numeeriset as $kentta) {
            if (isset($arvot[$kentta]))
                $arvot[$kentta] = desimaali($arvot[$kentta]);   
            }
        $kentat = implode(",",$lomakekentat);
        $arvot = implode(",",$arvot);
        $query = "INSERT INTO film ($kentat) VALUES ($arvot)";
        debuggeri($query);
        $result = query_oma($yhteys,$query);
        debuggeri("result:$result, affected_rows:".$yhteys->affected_rows);
        if ($result) {
            $message = "Elokuvan $title tiedot lisättiin tietokantaan";
            $success = "success";
            }  
        else {
            $message = "Elokuvan $title lisäys epäonnistui. Tarkista tiedot ja yritä uudelleen.";   
            $success = "danger";
            }
        $display = "";
        header("Location: ./lisayslomake.php?message=$message&success=$success");
        }
    }
}
?>