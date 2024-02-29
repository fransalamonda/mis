<?php
define("PLANTID", 'R01JAT-801');//you can change this
//GLOBAL $plantid = 'R01JAT-801';
$dbhost_name = "localhost";
$database = "bash";
$username = "root";
$password = "";

//////// Do not Edit below /////////
try {
$dbo = new PDO('mysql:host=localhost;dbname='.$database, $username, $password);
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();
}
?> 