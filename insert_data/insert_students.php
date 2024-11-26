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
$studentUsername = $password = $confirm_password = $fullName = $studentYear = $student_dateOfBirth = $studentAddress = "";
$studentUsername_err = $password_err = $confirm_password_err = $fullName_err = $studentYear_err = $student_dateOfBirth_err = $studentAddress_err = "";


// Use POST to process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Username validation
    $submitted_studentUsername = trim($_POST["studentUsername"] ?? '');
    if (empty($submitted_studentUsername)) {
        $studentUsername_err = "Please enter the student's username";
    } else {
        $studentUsernameSql = "SELECT studentID FROM students WHERE username = ?";

        if ($stmnt = mysqli_prepare($link, $studentUsernameSql)) {
            mysqli_stmt_bind_param($stmnt, "s", $submitted_studentUsername);

            if (mysqli_stmt_execute($stmnt)) {
                mysqli_stmt_store_result($stmnt);

                if (mysqli_stmt_num_rows($stmnt) > 0) {
                    $studentUsername_err = "Oops! This studentUsername is already taken by someone.";
                } else {
                    $studentUsername = $submitted_studentUsername;
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
    $confirm_password_err = "Please confirm the password.";
} elseif (empty($password_err) && $submitted_confirmPassword !== $submitted_confirmPassword) {
    $confirm_password_err = "Sorry, passwords do not match.";
}



// Full name validation
$fullName = isset($_POST["fullName"]) ? trim($_POST["fullName"]) : "";
if (empty($fullName)) {
    $fullName_err = "Please enter the student's name.";
} 

// Year validation
$studentYear = isset($_POST["studentYear"]) ? trim($_POST["studentYear"]) : "";
if (empty($studentYear)) {
    $studentYear_err = "Please enter student's year.";
} 

// Date of birth validation
$student_dateOfBirth = isset($_POST["student_dateOfBirth"]) ? trim($_POST["student_dateOfBirth"]) : "";
if (empty($student_dateOfBirth)) {
    $student_dateOfBirth_err = "Please enter student's date of birth.";
} 

// Address validation
$studentAddress = isset($_POST["studentAddress"]) ? trim($_POST["studentAddress"]) : "";
if (empty($studentAddress)) {
    $studentAddress_err = "Please enter the student's address.";
} 


// Checking for errors before insert data in database
if (
    empty($studentUsername_err) && empty($password_err) &&
    empty($confirm_password_err) && empty($fullName_err) && empty($studentYear_err) && empty($studentAddress_err) && empty($student_dateOfBirth_err)
) {

    $insertQuery = "INSERT INTO students (tuitionID, username, password, fullName, year, date_of_birth, address) VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmnt = mysqli_prepare($link, $insertQuery)) {
        mysqli_stmt_bind_param($stmnt, "sssssss", $tuitionID, $studentUsername, $password, $fullName, $studentYear, $student_dateOfBirth, $studentAddress);

        $tuitionID = $_SESSION["tuitionID"];
        $studentUsername = $studentUsername;
        $password = password_hash($password, PASSWORD_DEFAULT);
        $fullName = $fullName;
        $studentYear = $studentYear;
        $student_dateOfBirth = $student_dateOfBirth;
        $studentAddress = $studentAddress;

        $studentResult = mysqli_stmt_execute($stmnt);
        if ($studentResult) {
            header("location: insert_students.php");
        } else {
            echo "Oops! Unable to add this student. Please try again later.";
        }
    } else {
        // Handling errors
        echo "Error preparing the query. Please try again later.";
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
    <link rel="stylesheet" href="../style/register.css">
    <title>Insert Students</title>
    
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

                <div class="card-body">
                   
                <?php echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">'; ?> 

                        <div class="form-box <?php echo (!empty($studentUsername_err)) ? 'has-error' : ''; ?>">
                            <label>Username</label>
                            <input type="text" name="studentUsername" class="form-control" value="<?php echo $studentUsername; ?>" aria-describedby="Username" placeholder="Enter student's username">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $studentUsername_err; ?></span>
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
                            <input type="text" name="fullName" class="form-control" value="<?php echo $fullName; ?>" placeholder="Enter student's full name">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $fullName_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($studentYear_err)) ? 'has-error' : ''; ?>">
                            <label>Year</label>
                            <input type="text" name="studentYear" class="form-control" value="<?php echo $studentYear; ?>" placeholder="Enter student's year">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $studentYear_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($student_dateOfBirth)) ? 'has-error' : ''; ?>">
                            <label>Date Of Birth</label>
                            <input type="date" name="student_dateOfBirth" class="form-control" value="<?php echo $student_dateOfBirth; ?>" placeholder="Enter student's date of birth">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $student_dateOfBirth_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($studentAddress_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <input type="text" name="studentAddress" class="form-control" value="<?php echo $studentAddress; ?>" placeholder="Enter student's address">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $studentAddress_err; ?></span>
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