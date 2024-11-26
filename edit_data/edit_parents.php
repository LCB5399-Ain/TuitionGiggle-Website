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

//GET parentID
$parentID = isset($_GET['parentID']) ? (int)$_GET['parentID'] : ''; 

$fullName = $phoneNumber = $email = $address = "";
$fullName_err = $phoneNumber_err = $email_err = $address_err = "";

// Use POST to process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parentID = trim($_POST["parentID"]);
    $fullName = trim($_POST["fullName"]);
    $phoneNumber = trim($_POST["phoneNumber"]);
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);

    // Full name validation
    if (empty($fullName)) {
        $fullName_err = "Please enter parent's full name.";
    }

    // phoneNumber validation
    if (empty($phoneNumber)) {
        $phoneNumber_err = "Please enter parent's phoneNumber.";
    }

    // Email validation
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter parent's email.";
    } 

    // Address validation
    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter parent's address.";
    }


    // Checking for errors before insert data in database
    if (empty($fullName_err) && empty($phoneNumber_err) && empty($email_err) && empty($address_err)) {
        $updateQuery = "UPDATE parents SET fullName=?, phoneNumber=?, email=?, address=? WHERE parentID=?";

        if ($stmnt = mysqli_prepare($link, $updateQuery)) {
            mysqli_stmt_bind_param($stmnt, "ssssi", $fullName, $phoneNumber, $email, $address, $parentID);

            // After successfully execute the query, redirect to previous page.
            if (mysqli_stmt_execute($stmnt)) {
                echo "<script>
                alert('Success');
                window.location.href='../display_data/search_parents.php';
                </script>";
                
            } else {
            echo "Oops! Could not update the parent.";
        }
        mysqli_stmt_close($stmnt);
   }

}


} else {

    // Retrieve old data and show before submitting data
    $fetchQuery = "SELECT * FROM parents WHERE parentID=?";
    if ($stmnt = mysqli_prepare($link, $fetchQuery)) {
        mysqli_stmt_bind_param($stmnt, "i", $parentID);
        mysqli_stmt_execute($stmnt);
        $result = mysqli_stmt_get_result($stmnt);

        if ($row = mysqli_fetch_array($result)) {

            if ((int)$row['tuitionID'] == (int)$_SESSION['tuitionID']) {
                $parentID = $row['parentID'];
                $fullName = $row['fullName'];
                $phoneNumber = $row['phoneNumber'];
                $email = $row['email'];
                $address = $row['address'];

            } else {
                // Display error message
                echo "<script>
                alert('There is an issue with the inputted data.');
                window.location.href='../display_data/main_search.php';
                </script>";
            }
        }
        mysqli_stmt_close($stmnt);
        
    }
}
mysqli_close($link);

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/register.css">
    <title>Edit Parents</title>
    
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
        <div class="col-md-5">
            <div class="box">
                <div class="card bg-light mb-6">

                <div class="card-body p-4">
                    <!-- Form start -->
                    <?php echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">'; ?> 
                        <!-- Hidden field for parentID -->
                        <input type="hidden" name="parentID" value="<?php echo $parentID;?>"/>

                        <div class="form-box <?php echo (!empty($fullName_err)) ? 'has-error' : ''; ?>">
                            <label>Full Name</label>
                            <input type="text" name="fullName" class="form-control" value="<?php echo $fullName; ?>" placeholder="Enter parent's full name">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $fullName_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($phoneNumber)) ? 'has-error' : ''; ?>">
                            <label>Phone Number</label>
                            <input type="text" name="phoneNumber" class="form-control" value="<?php echo $phoneNumber; ?>" placeholder="Enter parent's phone number">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $phoneNumber_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($email)) ? 'has-error' : ''; ?>">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Enter parent's email">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $email_err; ?></span>
                        </div>


                        <div class="form-box <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="<?php echo $address; ?>" placeholder="Enter parent's address">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $address_err; ?></span>
                        </div>

                        <div class="form-box">
                            <div class="text-center">
                                <input type="submit" class="btn" value="Update"> 
                            </div>
                        </div>
                        <!-- Form end -->
                    </div>
                    </form>
                    <!-- End of adapted code -->
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</html>