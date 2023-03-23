<?php
// Database credentials
  // connect to the database
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "gestion_emprunts";
  $conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
