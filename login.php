<?php

session_start();

// Code adapted from Yani, 2017
// Make sure the user is logged in to the account
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: home.php");
    exit;
}
// End of adapted code

require_once "dbconf.php"; 

$username = $password = "";
$username_err = $password_err = "";

// Use POST to process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Username Validation
    $username = trim($_POST["username"] ?? '');
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your username.";
    } 

    // Password Validation
    $password = trim($_POST["password"] ?? '');
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    }


    // CHeck for errors and execute query
    if (empty($username_err) && empty($password_err)) {
        $query = "SELECT tuitionID, username, password FROM tuition WHERE username = ?";

        if ($stmnt = mysqli_prepare($link, $query)) {
            mysqli_stmt_bind_param($stmnt, "s", $input_username);

            $input_username = $username;

            if (mysqli_stmt_execute($stmnt)) {
                mysqli_stmt_store_result($stmnt);

                // If username is found, check the password
                if (mysqli_stmt_num_rows($stmnt) == 1) {
                    mysqli_stmt_bind_result($stmnt, $tuitionID, $username, $hashPassword);
                    
                    if(mysqli_stmt_fetch($stmnt)) {
                        if (password_verify($password, $hashPassword)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["tuitionID"] = $tuitionID;
                            $_SESSION["username"] = $username;

                            header("location: home.php");
                        } else {
                            $password_err = "The password is incorrect";
                        }
                    }

                } else {
                    $username_err = "Username is not recognized. Please check and try again.";
                }
            
            } else {
                echo "An error has occured. Please try again.";
            }
    }

    mysqli_stmt_close($stmnt);

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
    <link rel="stylesheet" href="style/login.css">
    <title>Login</title>

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
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">

        <div class="container">
            <a class="navbar-brand" href="home.php">
                <h1 class="text-center navtitle">Tuition Management</h1>
            </a>
        </div>

</nav>
<!-- End of adapted code -->

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-10 col-md-5 mx-auto">
            <div class="box">
                <div class="card bg-light mb-6">

                <div class="card-body p-4">
                    <h1 class="text-center">Welcome!</h1>
                    <p class="text-center font-weight-bold">Please enter your login details.</p>
                   
                    <?php echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">'; ?> 

                        <div class="form-box <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                            <!-- Display error message -->
                            <span class="help-block" style="color:red"><?php echo $username_err; ?></span> <!-- If there is an error, it will be shown to the user -->
                        </div>

                        <div class="form-box <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control">
                            <!-- Display error message -->
                            <span class="help-block" style="color:red"><?php echo $password_err; ?></span>
                        </div>

                        <div class="form-box">
                            <div class="text-center">
                                <input type="submit" class="btn" value="Login"> 
                            </div>
                        </div>


                        <div class="links">
                            <p>Don't have an account? <a href="register.php">Signup Now</a></p>
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