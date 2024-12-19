<?php

echo 'test';
phpinfo();


$servername = "127.0.0.1";
$username = "u466674432_core_innerpece";
$password = "wH6$#nDZm";
$dbname = "u466674432_core_innerpece";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  
}
echo "Connected successfully";

?>
