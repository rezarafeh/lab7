<?php
require_once 'config.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) 
  die("Connection failed: " . $conn->connect_error);

$sql = "SELECT * FROM items";
$result = $conn->query($sql);
$i = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
     $rows[$i] = $row;
     $i++;
       }
  } 
  echo json_encode($rows);
  $conn->close();
?>