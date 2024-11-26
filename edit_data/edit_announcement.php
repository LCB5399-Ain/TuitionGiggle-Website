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

//GET announcementID
$announcementID= isset($_GET['announcementID']) ? (int)$_GET['announcementID'] : ''; 
$title = $announcement = "";
$title_err = $announcement_err = "";

// Use POST to process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $announcementID = (int)trim($_POST["announcementID"]);
    $title = trim($_POST["title"]);
    $announcement = trim($_POST["announcement"]);

    // Title validation
    if (empty($title)) {
        $title_err = "Please enter the title.";
    } 

    // Announcement validation
    if (empty($announcement)) {
        $announcement_err = "Please enter the announcement.";
    } 


    // Checking for errors before insert data in database
    if (empty($title_err) && empty($announcement_err)) {
        $updateQuery = "UPDATE announcement SET title=?, announcement=? WHERE announcementID=?";

        if ($stmnt = mysqli_prepare($link, $updateQuery)) {
            mysqli_stmt_bind_param($stmnt, "ssi", $title, $announcement, $announcementID);

            // After successfully execute the query, redirect to previous page.
            if (mysqli_stmt_execute($stmnt)) {
                echo "<script>
                alert('Success');
                window.location.href='../display_data/search_announcement.php';
                </script>";
                
            } else {
            echo "Oops! Could not update the announcement.";
        }

        mysqli_stmt_close($stmnt);
   }

}


} else {

    // Retrieve old data and show before submitting data
    $fetchQuery = "SELECT * FROM announcement WHERE announcementID=?";
    if ($stmnt = mysqli_prepare($link, $fetchQuery)) {
        mysqli_stmt_bind_param($stmnt, "i", $announcementID);
        mysqli_stmt_execute($stmnt);
        $result = mysqli_stmt_get_result($stmnt);

    if ($user = mysqli_fetch_array($result)) {

            if ((int)$user['tuitionID'] == (int)$_SESSION['tuitionID']) {
                $announcementID = $user['announcementID'];
                $title = $user['title'];
                $announcement = $user['announcement'];

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
    <title>Edit Announcement</title>
    
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
                
                <div class="dropdown-menu">
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
 
                        <!-- Hidden field for announcementID -->
                        <input type="hidden" name="announcementID" value="<?php echo $announcementID;?>"/>

                        <div class="form-box <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" placeholder="Enter the title">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $title_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($announcement_err)) ? 'has-error' : ''; ?>">
                            <label for="announcement">Announcement</label>
                            <input type="text" name="announcement" class="form-control" value="<?php echo $announcement; ?>" placeholder="Enter announcement">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $announcement_err; ?></span>
                        </div>

                        <div class="form-box">
                            <div class="col-md-12 text-center">
                                <input type="submit" class="btn" value="Update"> 
                            </div>
                        </div>
                        <!-- Form end -->
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