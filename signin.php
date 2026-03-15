<?php
$url = "menu.php";
// Initialize the session
 if(isset($_GET['location'])) {
    $url2 = $_GET['location'];
	$url = $url . $url2;
	
}
// Include config file
require_once "config.php";

session_start();


if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("Location: " . $url);
    exit;
}
 
$password = $email = "";
$password_err = $email_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  
    // Check if username is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    	
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
  
  // Validate credentials
  if(empty($email_err) && empty($password_err)){
    // Prepare a select statement
    $sql = "SELECT email, password, role FROM users WHERE email = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        
        // Set parameters
        $param_email = $email;
  
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Store result
            mysqli_stmt_store_result($stmt);
            
            // Check if username exists, if yes then verify password
            if(mysqli_stmt_num_rows($stmt) == 1){                    
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $email, $hashed_password, $role);
                if(mysqli_stmt_fetch($stmt)){
                    if ((password_verify($password, $hashed_password))){
                        // Password is correct, so start a new session
                        session_start();
                        
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["email"] = $email;
                        $_SESSION["role"] = $role;   
                        header("location:$url");
                        die();
                        exit();
                    } else{
                        // Password is not valid, display a generic error message
                        $login_err = "Invalid email or password.";
                  //$url=$_SERVER['HTTP_REFERER'];
                    //header("location:$url");
                  

                    }
                }
            } else{
                // Email doesn't exist, display a generic error message
                $login_err = "Invalid email or password.";
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
  mysqli_close($link);


}

  
 
       
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <h1>Sign In</h1>
            <?php 
				      if(!empty($login_err)){
					          echo '<div class="alert alert-danger">' . $login_err . '</div>';
				      }        
				    ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]). '?'.http_build_query($_GET);  ?>" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                    <span class="text-danger"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password">
                    <span class="text-danger"><?php echo $password_err; ?></span>
                </div>
                     <p>
                        Don't have an account? <a href="signup.php">Sign up now</a>.
                      </p>              
                      <button type="submit" class="btn btn-primary">Sign in</button>
                      
                  
            </form>
        </div>
        
    </body>
    <script>
        
    </script>
</html>