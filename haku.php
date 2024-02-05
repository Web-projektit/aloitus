<?php
$hakutulokset = [];
$lomakekasittely = false;
if (isset($_GET['title'])) {
    $lomakekasittely = true;
    $title = puhdista($yhteys,$_GET['title']);
    $query = "SELECT * FROM film WHERE title LIKE '%$title%'";
    $result = query_oma($yhteys, $query);
    if ($result and $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            $hakutulokset[] = $row;
        }
    }
}
