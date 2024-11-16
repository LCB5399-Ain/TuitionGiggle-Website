<?php

session_start();

// Code adapted from Yani, 2017
// Make sure the user is logged in to the account
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;

}
// End of adapted code

require_once "dbconf.php"; 

// Initialize the variables
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";


// Use POST to process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // new_password validation
    $new_password = trim($_POST["new_password"] ?? '');
    if (empty($new_password)) {
        $new_password_err = "Please enter the new password.";
    } elseif (strlen($new_password) < 8) {
        $new_password_err = "Password must have at least 8 characters.";
    } 
    
    // Confirm password validation
    $confirm_password = trim($_POST["confirm_password"] ?? '');
    if (empty($confirm_password)) {
        $confirm_password_err = "Please confirm your password.";
    } else {
        if ($new_password !== $confirm_password) {
            $confirm_password_err = "Sorry, password did not match.";
        }
    }

    // Checking for errors before insert data in database
    if(empty($new_password_err) && empty($confirm_password_err)){
        $query = "UPDATE tuition SET password = ? WHERE tuitionID = ?";

        if($stmnt = mysqli_prepare($link, $query)){
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $tuitionID = $_SESSION["tuitionID"];

            mysqli_stmt_bind_param($stmnt, "si", $hashed_password, $tuitionID);

            if(mysqli_stmt_execute($stmnt)){
                echo "Password successfully updated";
                // Destroy session
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                // Display the error
                echo "An error has occured. Please try again.";
            }
    }

    mysqli_stmt_close($stmnt);

} else {
    // Error: failed to prepare statement
    echo "Database error: Could not prepare the update query.";
}

mysqli_close($link);

}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/pwd.css">
    <title>Reset Password</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style> 
        body {
            background-image: url(style/images/back_img1.jpg);   
            background-repeat: no-repeat;
            background-size: cover;
    }

    </style>

</head>

<body>

<div class="container py-5">
    <div class="row">
        <div class="col-10 col-sm-6 col-md-5 mx-auto">

        <div class="box">
            <div class="card bg-light mb-3">
                    
                <div class="card-body">
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-box <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                        <span class="help-block" style="color:red"><?php echo $new_password_err; ?></span>
                    </div>

                    <div class="form-box <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control">
                        <span class="help-block" style="color:red"><?php echo $confirm_password_err; ?></span>
                    </div>

                    <div class="form-box-container">
                        <div class="col-md-12 text-center">
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <a class="btn btn-link" href="home.php">Cancel</a>
                        </div>
                    </div>

                </form>

                </div>
            </div>
        </div>
        </div>
    </div>
</div>

</body>

</html>