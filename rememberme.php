<?php
/* 
Huom. session tuhoutuu, kun selain tai selaimen välilehti suljetaan. 
Rememberme-evästeen ollessa voimassa session-muuttuja asetetaan uudestaan palvelua kutsuttaessa.
Session-muuttuja poistetaan ja rememberme-evästeen voimassaolo päätetään palvelusta poistuttaessa. 
*/
function generate_tokens() {
$selector = bin2hex(random_bytes(16));
$validator = bin2hex(random_bytes(32));
return [$selector, $validator, $selector . ':' . $validator];
}

function parse_token(string $token) {
$parts = explode(':', $token);
if ($parts && count($parts) == 2) return $parts;
return null;
}

function insert_rememberme_token(int $user_id, string $selector, string $hashed_validator, string $expiry) {
$yhteys = db_connect();
$query = "INSERT INTO rememberme_tokens(user_id, selector, hashed_validator, expiry) VALUES(?, ?, ?, ?)";
$stmt = $yhteys->prepare($query);
$stmt->bind_param('isss', $user_id,$selector,$hashed_validator,$expiry);
$result = $stmt->execute();
debuggeri("Lisättiin: $stmt->affected_rows rememberme_token.");
return $result;
}

function find_rememberme_token(string $selector){
$id = $hashed_validator = $user_id = $expiry = null;    
$yhteys = db_connect();   
$query = "SELECT id, selector, hashed_validator, user_id, expiry FROM rememberme_tokens
          WHERE selector = ? AND expiry >= now() LIMIT 1";
$stmt = $yhteys->prepare($query);
$stmt->bind_param('s', $selector);
$stmt->execute();
$stmt->bind_result($id,$selector,$hashed_validator,$user_id,$expiry);
$result = $stmt->fetch();
return compact('id', 'selector', 'hashed_validator' ,'user_id', 'expiry');
}

function delete_rememberme_token(int $user_id) {
$yhteys = db_connect();    
$query = "DELETE FROM rememberme_tokens WHERE user_id = ?";
$stmt = $yhteys->prepare($query);
$stmt->bind_param('i', $user_id);
$result = $stmt->execute();
debuggeri("Poistettiin: $stmt->affected_rows rememberme_token.");
return $result;
}

function find_user_by_token(string $token){    
$tokens = parse_token($token);
if (!$tokens) return null;
$users_id = $email = null;
$yhteys = db_connect();
$query = "SELECT users.id, email FROM users INNER JOIN rememberme_tokens ON user_id = users.id
          WHERE selector = ? AND expiry > now() LIMIT 1";
$stmt = $yhteys->prepare($query);
$stmt->bind_param('s', $tokens[0]);
$stmt->execute();
$stmt->bind_result($users_id, $email);
$result = $stmt->fetch();
return compact('users_id','email');
}

function token_is_valid(string $token) { 
// parse the token to get the selector and validator [$selector, $validator] = parse_token($token);
[$selector, $validator] = parse_token($token);
$tokens = find_rememberme_token($selector);
if (!$tokens) return false;
$verified_token = password_verify($validator, $tokens['hashed_validator']);
return $verified_token ? $tokens['user_id'] : false; 
}

function rememberme(int $user_id, int $day = 30){
[$selector, $validator, $token] = generate_tokens();
delete_rememberme_token($user_id);
$expiry_seconds = time() + 60 * 60 * 24 * $day;
$hash_validator = password_hash($validator, PASSWORD_DEFAULT);
$expiry = date('Y-m-d H:i:s', $expiry_seconds);
if (insert_rememberme_token($user_id, $selector, $hash_validator, $expiry)) {
    /* Huom. httponly : true */
    setcookie('rememberme', $token, $expiry_seconds, "", "", false, true);
    /* Huom. tätä tarvitaan vain tokenin poistoon rememberme_tokens-taulusta */
    // $_SESSION['user_id'] = $user_id;
    }
}


/*function secure_page() {
if (!session_id()) session_start();
$loggedIn = $_SESSION['loggedIn'] ?? false;   
if (!$loggedIn || is_int($loggedIn)) {
    $token = $_COOKIE['rememberme'] ?? '';
    if ($token) {
        $token = htmlspecialchars($token);
        if ($user_id = token_is_valid($token)){
            //if (session_regenerate_id()) {
            //$loggedIn = hae_rooli($user_id);
            $_SESSION['loggedIn'] = $user_id;
            return $user_id;            }
        }
    $_SESSION['next_page'] = $_SERVER['PHP_SELF']; 
    header("location: login.php");
    exit;  
    }
return $loggedIn;
};  */

function secure_page($role = ''){
$loggedIn = loggedIn();
if (!$loggedIn || $role && $role != $loggedIn){
    $_SESSION['next_page'] = $_SERVER['PHP_SELF']; 
    header("location: login.php");
    exit;
    }
return $loggedIn;
}

function loggedIn() {
if (!session_id()) session_start();    
$loggedIn = $_SESSION['loggedIn'] ?? false;   
/* Huom. loggedIn voi olla 'user', 'admin', jne. 
   loggedIn voi olla tässä user_id ja eväste vanhentunut.
   Ilman roolin hakua muista minut vie käyttäjän peruskäyttäjän rooliin. */
if (!$loggedIn || is_int($loggedIn)) {
    if ($token = $_COOKIE['rememberme'] ?? '') {
        $token = htmlspecialchars($token);
        if ($user_id = token_is_valid($token)) {
            // $loggedIn = hae_rooli($user_id);
            $loggedIn = $user_id;
            $_SESSION['loggedIn'] = $loggedIn;
            }
        }
    }
return $loggedIn;
}

function nayta_rememberme($kentta){
$nayta = "";    
if (!isset($GLOBALS['virheet'][$kentta]) && isset($_POST[$kentta])) $nayta = $_POST[$kentta];
elseif (loggedIn()) $nayta = 'checked';
return $nayta; 
}

function hae_rooli(int $user_id) {
$yhteys = db_connect();
$query = "SELECT name FROM users LEFT JOIN roles ON role = roles.id WHERE users.id = $user_id";
$result = $yhteys->query($query);
if ($result->num_rows) {
    [$role] = $result->fetch_row();
    return $role;
    }        
else return false;
}
?>