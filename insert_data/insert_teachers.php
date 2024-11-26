<?php

session_start();

// Code adapted from Yani, 2017
// Make sure the user is logged in to the account
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
// End of adapted code

require_once "../dbconf.php";

// Initialize the variables
$teacherUsername = $password = $confirm_password = $fullName = $teacherSubject = $phoneNumber = $teacherEmail = $teacherAddress = "";
$teacherUsername_err = $password_err = $confirm_password_err = $fullName_err = $teacherSubject_err = $phoneNumber_err = $teacherEmail_err = $teacherAddress_err = "";


// Use POST to process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Code adapted from Yassein, 2020
    // Username validation
    $submitted_teacherUsername = trim($_POST["teacherUsername"] ?? '');
    if (empty($submitted_teacherUsername)) {
        $teacherUsername_err = "Please enter the teacher's username";
    } else {
        $teacherUsernameSql = "SELECT teacherID FROM teachers WHERE username = ?";

        if ($stmnt = mysqli_prepare($link, $teacherUsernameSql)) {
            mysqli_stmt_bind_param($stmnt, "s", $teacherUsernameSql);

            $param_teacherUsername = trim($_POST["teacherUsername"]);

            if (mysqli_stmt_execute($stmnt)) {
                mysqli_stmt_store_result($stmnt);

                if (mysqli_stmt_num_rows($stmnt) > 0) {
                    $teacherUsername_err = "Oops! This username is already taken.";
                } else {
                    $teacherUsername = $submitted_teacherUsername;
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

// Full name validation
$fullName = isset($_POST["fullName"]) ? trim($_POST["fullName"]) : "";
if (empty($fullName)) {
    $fullName_err = "Please enter the teacher's name.";
} 

// Subject validation
$teacherSubject = isset($_POST["teacherSubject"]) ? trim($_POST["teacherSubject"]) : "";
if (empty($teacherSubject)) {
    $teacherSubject_err = "Please enter teacher's subject.";
} 

// phoneNumber validation
$phoneNumber = isset($_POST["phoneNumber"]) ? trim($_POST["phoneNumber"]) : "";
if (empty($phoneNumber)) {
    $phoneNumber_err = "Please enter teacher's phoneNumber.";
} 

// Email validation
$teacherEmail = isset($_POST["teacherEmail"]) ? trim($_POST["teacherEmail"]) : "";
if (empty($teacherEmail)) {
    $teacherEmail_err = "Please enter the teacher's email.";
} 

// Address validation
$teacherAddress = isset($_POST["teacherAddress"]) ? trim($_POST["teacherAddress"]) : "";
if (empty($teacherAddress)) {
    $teacherAddress_err = "Please enter the teacher's address.";
} 


// Checking for errors before insert data in database
if (empty($teacherUsername_err) && empty($password_err) && empty($confirm_password_err) && 
    empty($fullName_err) && empty($teacherSubject_err) && empty($phoneNumber_err) && empty($teacherEmail_err) && empty($teacherAddress_err) ) {

    $insertQuery = "INSERT INTO teachers (tuitionID, username, password, fullName, subject, phoneNumber, email, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmnt = mysqli_prepare($link, $insertQuery)) {
        mysqli_stmt_bind_param($stmnt, "ssssssss", $tuitionID, $teacherUsername, $password, $fullName, $teacherSubject, $phoneNumber, $teacherEmail, $teacherAddress);

        $tuitionID = $_SESSION["tuitionID"];
        $teacherUsername = $teacherUsername;
        $password = password_hash($password, PASSWORD_DEFAULT);
        $fullName = $fullName;
        $teacherSubject = $teacherSubject;
        $phoneNumber = $phoneNumber;
        $teacherEmail = $teacherEmail;
        $teacherAddress = $teacherAddress;

        $teacherResult = mysqli_stmt_execute($stmnt);
        if ($teacherResult) {
            header("location: insert_teachers.php");
        } else {
            echo "Oops! Unable to add this parent. Please try again later.";
        }
    } else {
        // Handling errors
        echo "Error preparing the query. Please try again later.";
    }
    // End of adapted code
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
    <link rel="stylesheet" href="../style/register.css">
    <title>Insert Teachers</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Coustard|Lato&display=swap" rel="stylesheet">

    <style> 
        body {
            background-image: url(../style/images/back_img1.jpg);  
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
            <a class="navbar-brand" href="../home.php">
                <h1 class="text-center navtitle">Tuition Management</h1>
            </a>

        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="navbar-toggler-icon" id="navbardrop" data-toggle="dropdown"></a>
                
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="../insert_data/main_insert.php">Add Data</a>
                <a class="dropdown-item" href="../display_data/main_search.php">Search Data</a>
                <a class="dropdown-item" href="../reset_pwd.php">Change Password</a>
                <a class="dropdown-item" href="../logout.php">Logout</a>
                </div>

            </li>
        </ul>
        </div>
</nav>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-10 col-md-5 mx-auto">
            <div class="box">
                <div class="card bg-light mb-6">

                <div class="card-body p-4">
                   
                <?php echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">'; ?> 

                        <div class="form-box <?php echo (!empty($teacherUsername_err)) ? 'has-error' : ''; ?>">
                            <label>Username</label>
                            <input type="text" name="teacherUsername" class="form-control" value="<?php echo $teacherUsername; ?>" aria-describedby="Username" placeholder="Enter teacher's teacherUsername">
                             <!-- Display error message -->
                            <span class="text-danger"><?php echo $teacherUsername_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="Enter password">
                             <!-- Display error message -->
                            <span class="text-danger"><?php echo $password_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="Confirm password">
                             <!-- Display error message -->
                            <span class="text-danger"><?php echo $confirm_password_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($fullName_err)) ? 'has-error' : ''; ?>">
                            <label>Full Name</label>
                            <input type="text" name="fullName" class="form-control" value="<?php echo $fullName; ?>" placeholder="Enter teachers's full name">
                             <!-- Display error message -->
                            <span class="text-danger"><?php echo $fullName_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($teacherSubject_err)) ? 'has-error' : ''; ?>">
                            <label>Subject</label>
                            <input type="text" name="teacherSubject" class="form-control" value="<?php echo $teacherSubject; ?>" placeholder="Enter teacher's subject">
                             <!-- Display error message -->
                            <span class="text-danger"><?php echo $teacherSubject_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($phoneNumber)) ? 'has-error' : ''; ?>">
                            <label>Phone Number</label>
                            <input type="text" name="phoneNumber" class="form-control" value="<?php echo $phoneNumber; ?>" placeholder="Enter teacher's phone number">
                             <!-- Display error message -->
                            <span class="text-danger"><?php echo $phoneNumber_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($teacherEmail)) ? 'has-error' : ''; ?>">
                            <label>Email</label>
                            <input type="text" name="teacherEmail" class="form-control" value="<?php echo $teacherEmail; ?>" placeholder="Enter teacher's email">
                             <!-- Display error message -->
                            <span class="text-danger"><?php echo $teacherEmail_err; ?></span>
                        </div>


                        <div class="form-box <?php echo (!empty($teacherAddress_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <input type="text" name="teacherAddress" class="form-control" value="<?php echo $teacherAddress; ?>" placeholder="Enter teacher's address">
                             <!-- Display error message -->
                            <span class="text-danger"><?php echo $teacherAddress_err; ?></span>
                        </div>

                        <div class="form-box">
                            <div class="col-md-12 text-center">
                                <input type="submit" class="btn" value="Submit"> 
                            </div>
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

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</html>