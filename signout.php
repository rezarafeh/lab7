<?php
session_start();
unset($_SESSION["email"]);
unset($_SESSION["role"]);
unset($_SESSION["loggedin"]);
header("Location:signin.php");
?>