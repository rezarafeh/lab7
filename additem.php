<?php
 require_once 'config.php';
  if(!empty($_FILES['files'])){
    $targetDir = "uploads/";
    $targetFile = $targetDir.$_POST["Picture"];
      if (move_uploaded_file($_FILES["files"]["tmp_name"][0], $targetFile)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["files"]["name"][0])). " has been uploaded.";
       
        $conn = $link;
        $Name =  $_POST["Name"];
        $Description = $_POST["Description"];
        $Price = $_POST["Price"];
        $Picture = $targetDir . $_POST["Picture"];

        if ($conn->connect_error) 
            die("Connection failed: " . $conn->connect_error);
        $sql = "INSERT INTO items (Name, Description, Price, Picture)
           VALUES (?,?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) 
              $stmt->bind_param("ssss", $Name, $Description, $Price, $Picture);
        else
        {
            $error = $conn->errno . ' ' . $conn->error;
            echo $error; 
        }
        $stmt->execute();
        echo "Item has been successfully added!";
        $conn->close();
      
      
      
      
      
      } else {
        echo "Sorry, there was an error uploading your file.";
        die();
      }

    }
?>