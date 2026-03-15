<?php
    session_start();
    $_SESSION["loggedin"] = false;
	// Include config file
    require_once "config.php";
    // Define variables and initialize with empty values
    $password_err = $confirm_password_err = $email_err = "";
    
    global $DB_Connect;
    $DB_Connect = $link;
    global $submitted;
    $submitted = False;
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        $submitted = True;
        // Validate email
        if (empty($_POST["email"])) {
            $email_err = "Email is required";
            } else {
                $email = trim($_POST["email"]);
                // check if e-mail address is well-formed
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $email_err = "Invalid email format";
                }
        
        // Validate password
              else  if(empty(trim($_POST["password"]))){
                    
                        $password_err = "Please enter a password";     
                    } 
                    elseif(strlen(trim($_POST["password"])) < 6){
                        $password_err = "Password must have atleast 6 characters.";
                    } else{
                        $password = trim($_POST["password"]);
                    // Validate confirm password
                    if(empty(trim($_POST["confirm_password"]))){
                        $confirm_password_err = "Please confirm password.";     
                    } else{
                        $confirm_password = trim($_POST["confirm_password"]);
                        if(empty($password_err) && ($password != $confirm_password)){
                            $confirm_password_err = "Password did not match.";
                        }
                       else{
                        $sql = "SELECT * FROM users WHERE email = ?";
                        if($stmt = mysqli_prepare($link, $sql)){
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "s", $param_email);
                        // Set parameters
                        $param_email = trim($_POST["email"]);
                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt)){
                        /* store result */
                        mysqli_stmt_store_result($stmt);
                        if(mysqli_stmt_num_rows($stmt) == 1){
                           $email_err = "This email is already taken.";
                        } else{
                            $email = trim($_POST["email"]);
                            // No error, lets add the user (role by defauls is customer)
                            $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
                            if($stmt = mysqli_prepare($link, $sql)){
                                  // Bind variables to the prepared statement as parameters
                                mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_password);
                                // Set parameters
                                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                                $param_email =  $email;
                                // Attempt to execute the prepared statement
                                if(mysqli_stmt_execute($stmt)){
                                    echo "You successfully signed up!";
                                } else{
                                    echo "Oops! Something went wrong. Please try again later.";
                                
                                    }
                
                            // Close statement
                            mysqli_stmt_close($stmt);
                        }
                        else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                    }
            
            
            // Close connection
            mysqli_close($link);
           }
           else{
            echo "Oops! Something went wrong. Please try again later.";
                }

        }
    }
    
                
    }
}
            }
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
            <h1>Sign Up</h1>
             
				      
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
                <div class="form-group">
                    <label for="pwd2">Password (Re-type):</label>
                    <input type="password" class="form-control" id="confirm_pwd" placeholder="Confirm password" name="confirm_password">
                    <span class="text-danger"><?php echo $confirm_password_err; ?></span>
                </div>
                     <p>
                        Have you already registered? <a href="signin.php">Sign in now</a>.
                      </p>              
                      <button type="submit" class="btn btn-success">Sign in</button>
                      
                  
            </form>
        </div>
        
    </body>
    <script>
        
    </script>
</html>