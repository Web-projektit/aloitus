<?php
/* Huom. aktivointitokenin voimassaoloa ei tässä tarkisteta.*/
$email_verified = $email_already_verified = $activation_error = "";
$token = $_GET['token'] ?? "";
if ($token) {
    $token = $yhteys->real_escape_string($token); 
    $query ="SELECT users_id,is_active,s.updated FROM signup_tokens s
             LEFT JOIN users ON users_id = id WHERE s.token = '$token'";
    $result = query_oma($yhteys,$query);
    if ($result->num_rows){
        [$id,$is_active,$ika] = $result->fetch_row();
        if ($is_active == 0) {
            $query = "UPDATE users SET is_active = 1 WHERE id = $id";
            $result = query_oma($yhteys,$query);
            if ($result) {
                $email_verified = 
                  '<div class="alert alert-success">
                  Sähköpostiosoitteesi on vahvistettu.
                   </div>';
                }
            } 
        else {
            $email_already_verified = 
              '<div class="alert alert-danger">
               Sähköpostiosoitteesi on jo vahvistettu.
               </div>';
            }   
        $query = "DELETE FROM signup_tokens WHERE token = '$token'";
        $result = query_oma($yhteys,$query);
        $poistettiin = $yhteys->affected_rows;
        } 
    else {
        $activation_error = 
          '<div class="alert alert-danger">
          Virhe, sähköpostiosoitteesi saattaa olla jo vahvistettu.
          </div>';
        }
    }
?>