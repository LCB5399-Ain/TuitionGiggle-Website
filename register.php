<?php

require_once "dbconf.php";

// Code adapted from TutorialRepublic, nd
$tuitionUsername = $password = $confirm_password = $name = $tutionEmail = $tuitionAddress = $phoneNumber = "";
$tuitionUsername_err = $password_err = $confirm_password_err = $name_err = $tutionEmail_err = $tuitionAddress_err = $phoneNumber_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["tuitionUsername"]))) {
        $tuitionUsername_err = "Username is required.";
    } else {
        // Check if the tuitionUsername already exists in the database
        $query  = "SELECT tuitionID FROM tuition WHERE username = ?";

        if ($stmnt = mysqli_prepare($link, $query)) {
            mysqli_stmt_bind_param($stmnt, "s", $input_tuitionUsername);

            // Get and trim the input tuitionUsername
            $input_tuitionUsername = trim($_POST["tuitionUsername"]);

            if (mysqli_stmt_execute($stmnt)) {
                mysqli_stmt_store_result($stmnt);

                // If the tuitionUsername already exists, display the error message
                if (mysqli_stmt_num_rows($stmnt) == 1) {
                    $tuitionUsername_err = "This tuitionUsername is already taken. Please choose a different one.";
                } else {
                    $tuitionUsername = trim($_POST["tuitionUsername"]);
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
        $name_err = "Please enter the name of the tuition center.";
    }


    // Email validation
    $tutionEmail = isset($_POST["tutionEmail"]) ? trim($_POST["tutionEmail"]) : "";
    if (empty($tutionEmail)) {
        $tutionEmail_err = "Please enter tuition centre's email.";
    }

    // Address validation
    $tuitionAddress = isset($_POST["tuitionAddress"]) ? trim($_POST["tuitionAddress"]) : "";
    if (empty($tuitionAddress)) {
        $tuitionAddress_err = "Please enter the tuition centre's address.";
    }

    // phoneNumber validation
    $phoneNumber = isset($_POST["phoneNumber"]) ? trim($_POST["phoneNumber"]) : "";
    if (empty($phoneNumber)) {
        $phoneNumber_err = "Please enter tuition centre's phone number.";
    }


    // Checking for errors before insert data in database
    if (
        empty($tuitionUsername_err) && empty($password_err) &&
        empty($confirm_password_err) && empty($name_err) && empty($tutionEmail_err) && empty($tuitionAddress_err) && empty($phoneNumber_err)
    ) {

        $insertQuery = "INSERT INTO tuition (username, password, name, email, address, phoneNumber) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmnt = mysqli_prepare($link, $insertQuery)) {
            mysqli_stmt_bind_param($stmnt, "ssssss", $tuitionUsername, $password, $name, $tutionEmail, $tuitionAddress, $phoneNumber);

            $tuitionUsername = $tuitionUsername;
            $password = password_hash($password, PASSWORD_DEFAULT);
            $name = $name;
            $tutionEmail = $tutionEmail;
            $tuitionAddress = $tuitionAddress;
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
// End of adapted code
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
                            <div class="form-box <?php echo (!empty($tuitionUsername_err)) ? 'has-error' : ''; ?>">
                                <label>Username</label>
                                <input type="text" name="tuitionUsername" class="form-control" value="<?php echo $tuitionUsername; ?>" aria-describedby="Username" placeholder="Enter tuition centre's username">
                                <!-- Display error message -->
                                <span class="text-danger"><?php echo $tuitionUsername_err; ?></span>
                            </div>

                            <div class="form-box <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="Enter Password">
                                <!-- Display error message -->
                                <span class="text-danger"><?php echo $password_err; ?></span>
                            </div>

                            <div class="form-box <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="Confirm Password">
                                <!-- Display error message -->
                                <span class="text-danger"><?php echo $confirm_password_err; ?></span>
                            </div>

                            <div class="form-box <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                                <label>Tuition Centre Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" placeholder="Enter tuition centre's name">
                                <!-- Display error message -->
                                <span class="text-danger"><?php echo $name_err; ?></span>
                            </div>

                            <div class="form-box <?php echo (!empty($tutionEmail_err)) ? 'has-error' : ''; ?>">
                                <label>Email</label>
                                <input type="text" name="tutionEmail" class="form-control" value="<?php echo $tutionEmail; ?>" placeholder="Enter tuition centre's email">
                                <!-- Display error message -->
                                <span class="text-danger"><?php echo $tutionEmail_err; ?></span>
                            </div>

                            <div class="form-box <?php echo (!empty($tuitionAddress_err)) ? 'has-error' : ''; ?>">
                                <label>Address</label>
                                <input type="text" name="tuitionAddress" class="form-control" value="<?php echo $tuitionAddress; ?>" placeholder="Enter tuition centre's address">
                                <!-- Display error message -->
                                <span class="text-danger"><?php echo $tuitionAddress_err; ?></span>
                            </div>

                            <div class="form-box <?php echo (!empty($phoneNumber_err)) ? 'has-error' : ''; ?>">
                                <label>Phone Number</label>
                                <input type="text" name="phoneNumber" class="form-control" value="<?php echo $phoneNumber; ?>" placeholder="Enter tuition centre's phone number">
                                <!-- Display error message -->
                                <span class="text-danger"><?php echo $phoneNumber_err; ?></span>
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