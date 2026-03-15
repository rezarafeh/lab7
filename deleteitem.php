<?php
    $fileName = $_GET['fileName'];
    unlink($fileName); 
    require_once 'config.php';
    $conn = $link;
    
    if ($conn->connect_error) 
        die("Connection failed: " . $conn->connect_error);
    $sql = "DELETE FROM items WHERE Picture = ?";
    if ($stmt = $conn->prepare($sql)) 
          $stmt->bind_param("s", $fileName);
    else
    {
        $error = $conn->errno . ' ' . $conn->error;
        echo $error; 
    }
    $stmt->execute();
    echo "Item has been successfully added!";
    $conn->close();
  
  

?>