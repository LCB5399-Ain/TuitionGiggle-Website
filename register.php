<?php

require_once "dbconf.php"; 

$username = $password = $confirm_password = $name = $email = $address = $phoneNumber = "";
$username_err = $password_err = $confirm_password_err = $name_err = $email_err = $address_err = $phoneNumber_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Username is required.";
    } else {
        // Check if the username already exists in the database
        $query  = "SELECT tuitionID FROM tuition WHERE username = ?";

        if ($stmnt = mysqli_prepare($link, $query )) {
            mysqli_stmt_bind_param($stmnt, "s", $input_username);

            // Get and trim the input username
            $input_username = trim($_POST["username"]);

            if (mysqli_stmt_execute($stmnt)) {
                mysqli_stmt_store_result($stmnt);

                // If the username already exists, display the error message
                if (mysqli_stmt_num_rows($stmnt) == 1) {
                    $username_err = "This username is already taken. Please choose a different one.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "An error occurred. Please try again.";
            }
        }
    

    mysqli_stmt_close($stmnt);

}

// Password validation
$submitted_password = trim($_POST["password"] ?? '');
if (empty($submitted_password)) {
    $password_err = "Please enter a password.";
} elseif (strlen($submitted_password) < 8) {
    $password_err = "Password must have at least 8 characters.";
} else {
    $password = $submitted_password;
}



// Confirm password validation
$submitted_confirmPassword = trim($_POST["confirm_password"] ?? '');
if (empty($submitted_confirmPassword)) {
    $confirm_password_err = "Please confirm your password.";
} elseif (empty($password_err) && $submitted_confirmPassword !== $submitted_confirmPassword) {
    $confirm_password_err = "Sorry, passwords do not match.";
}

// Name validation
$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
if (empty($name)) {
    $name_err = "Please enter tuition centre's  name.";
} 


// Email validation
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
if (empty($email)) {
    $email_err = "Please enter tuition centre's email.";
} 

// Address validation
$address = isset($_POST["address"]) ? trim($_POST["address"]) : "";
if (empty($address)) {
    $address_err = "Please enter the tuition centre's address.";
} 

// phoneNumber validation
$phoneNumber = isset($_POST["phoneNumber"]) ? trim($_POST["phoneNumber"]) : "";
if (empty($phoneNumber)) {
    $phoneNumber_err = "Please enter tuition centre's phoneNumber.";
} 


// Checking for errors before insert data in database
if (empty($username_err) && empty($password_err) &&
    empty($confirm_password_err) && empty($name_err) && empty($email_err) && empty($address_err) && empty($phoneNumber_err)
) {

    $insertQuery = "INSERT INTO tuition (username, password, name, email, address, phoneNumber) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmnt = mysqli_prepare($link, $insertQuery)) {
        mysqli_stmt_bind_param($stmnt, "ssssss", $username, $password, $name, $email, $address, $phoneNumber);

        $username = $username;
        $password = password_hash($password, PASSWORD_DEFAULT);
        $name = $name;
        $email = $email;
        $address = $address;
        $phoneNumber = $phoneNumber;

        if (mysqli_stmt_execute($stmnt)) {
            header("location: login.php");
        } else {
            echo "Oops! Something went wrong. Please try again later.";
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
    <link rel="stylesheet" href="style/register.css">
    <title>Register</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style> 
        body {
            background-image: url(style/images/back_img1.jpg);  
            background-position: center;
            background-attachment: fixed;
            background-size: cover;
            background-repeat: no-repeat;
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

            <?php echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">'; ?> 
            <div class="form-box <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" aria-describedby="Username" placeholder="Enter tuition centre's username">
                        <!-- Display error message -->
                        <span class="text-danger" style="color:red"><?php echo $username_err; ?></span>
                    </div>

                    <div class="form-box <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="Enter Password">
                        <!-- Display error message -->
                        <span class="text-danger" style="color:red"><?php echo $password_err; ?></span>
                    </div>

                    <div class="form-box <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="Confirm Password">
                        <!-- Display error message -->
                        <span class="text-danger" style="color:red"><?php echo $confirm_password_err; ?></span>
                    </div>

                    <div class="form-box <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                        <label>Tuition Centre Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" placeholder="Enter tuition centre's name">
                        <!-- Display error message -->
                        <span class="text-danger" style="color:red"><?php echo $name_err; ?></span>
                    </div>

                    <div class="form-box <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Enter tuition centre's email">
                        <!-- Display error message -->
                        <span class="text-danger" style="color:red"><?php echo $email_err; ?></span>
                    </div>

                    <div class="form-box <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="<?php echo $address; ?>" placeholder="Enter tuition centre's address">
                        <!-- Display error message -->
                        <span class="text-danger" style="color:red"><?php echo $address_err; ?></span>
                    </div>

                    <div class="form-box <?php echo (!empty($phoneNumber_err)) ? 'has-error' : ''; ?>">
                        <label>Phone Number</label>
                        <input type="text" name="phoneNumber" class="form-control" value="<?php echo $phoneNumber; ?>" placeholder="Enter tuition centre's phone number">
                        <!-- Display error message -->
                        <span class="text-danger" style="color:red"><?php echo $phoneNumber_err; ?></span>
                    </div>

                    <div class="form-box">
                            <div class="text-center">
                                <input type="submit" class="btn" value="Submit"> 
                            </div>
                        </div>

                        <div class="links">
                            <p>Already have an account? <a href="login.php">Login Now</a></p>
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
