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

// Code adapted from TutorialRepublic, nd
// Initialize the variables
$newPassword = $confirmPassword = "";
$newPassword_error = $confirmPassword_error = "";


// Use POST to process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // new_password validation
    $newPassword = trim($_POST["newPassword"] ?? '');
    if (!$newPassword) {
        $newPassword_error = "New password is required.";
    } elseif (strlen($newPassword) < 8) {
        $newPassword_error = "The password must have more than 8 characters.";
    } 
    
    // Confirm password validation
    $confirmPassword = trim($_POST["confirmPassword"] ?? '');
    if (!$confirmPassword) {
        $confirmPassword_error = "Please confirm your password.";
    } else {
        if ($newPassword !== $confirmPassword) {
            $confirmPassword_error = "Sorry, password did not match.";
        }
    }

    // Checking for errors before insert data in database
    if(!$newPassword_error && !$confirmPassword_error) {
        $queryPass = "UPDATE tuition SET password = ? WHERE tuitionID = ?";
        $stmnt = $link-> prepare($queryPass);

        if($stmnt) {
            $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
            $tuitionID = $_SESSION["tuitionID"];

            mysqli_stmt_bind_param($stmnt, "si", $hashed_password, $tuitionID);

            if(mysqli_stmt_execute($stmnt)){
                echo "The password is successfully updated";
                // Destroy session
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                // Display the error
                echo "An error has occured. Please try inputting the password again.";
            }
    }

    mysqli_stmt_close($stmnt);

} else {
    // Error: failed to prepare statement
    echo "Database error: Could not prepare the update query.";
}

mysqli_close($link);

}
// End of adapted code

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/pwd.css">
    <title>Change Password</title>

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
<!-- Code adapted from Yassein, 2020 -->
<div class="container py-5">
    <div class="row">
        <div class="col-10 col-md-5 mx-auto">

        <div class="box">
            <div class="card bg-light mb-5">
                    
                <div class="card-body">
                
                <?php echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">'; ?> 

                    <div class="form-box <?php echo (!empty($newPassword_error)) ? 'has-error' : ''; ?>">
                        <label>New Password</label>
                        <input type="password" name="newPassword" class="form-control" value="<?php echo $newPassword; ?>">
                        <span class="text-danger"><?php echo $newPassword_error; ?></span>
                    </div>

                    <div class="form-box <?php echo (!empty($confirmPassword_error)) ? 'has-error' : ''; ?>">
                        <label>Confirm Password</label>
                        <input type="password" name="confirmPassword" class="form-control">
                        <span class="text-danger"><?php echo $confirmPassword_error; ?></span>
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
<!-- End of adapted code -->

</body>

</html>