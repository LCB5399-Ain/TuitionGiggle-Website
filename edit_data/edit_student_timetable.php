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

//GET teacherID
$timetableID = isset($_GET['timetableID']) ? (int)$_GET['timetableID'] : ''; 

$year = $classroom = $subject = $day = $time_of_room = "";
$year_err = $classroom_err = $subject_err = $day_err = $time_of_room_err = "";

// Use POST to process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $timetableID = trim($_POST["timetableID"]);
    $year = trim($_POST["year"]);
    $classroom = trim($_POST["classroom"]);
    $subject = trim($_POST["subject"]);
    $day = trim($_POST["day"]);
    $time_of_room = trim($_POST["time_of_room"]);

    // year validation
    if (empty($year)) {
        $year_err = "Please enter the student's year.";
    } 

    // classroom name validation
    if (empty($classroom)) {
        $classroom_err = "Please enter the classroom name.";
    }

    // Subject validation
    if (empty($subject)) {
        $subject_err = "Please enter the class subject.";
    }

    // Day validation
    if (empty($day)) {
        $day_err = "Please enter the day of the class.";
    }

    // Time of room validation
    if (empty($time_of_room)) {
        $time_of_room_err = "Please enter the class time.";
    }


    // Checking for errors before insert data in database
    if (empty($year_err) && empty($classroom_err) && empty($subject_err) && empty($day_err) && empty($time_of_room_err)) {
        $updateQuery = "UPDATE timetable SET year=?, classroom=?, subject=?, day=?, time_of_room=? WHERE timetableID=?";

        if ($stmnt = mysqli_prepare($link, $updateQuery)) {
            mysqli_stmt_bind_param($stmnt, "sssssi", $year, $classroom, $subject, $day, $time_of_room, $timetableID);

            // After successfully execute the query, redirect to previous page.
            if (mysqli_stmt_execute($stmnt)) {
                echo "<script>
                alert('Success');
                window.location.href='../display_data/search_timetable.php';
                </script>";
                
            } else {
            echo "Oops! Could not update the timetable.";
        }
        mysqli_stmt_close($stmnt);
   }
}

} else {

    // Retrieve old data and show before submitting data
    $fetchQuery = "SELECT * FROM timetable WHERE timetableID=?";
    if ($stmnt = mysqli_prepare($link, $fetchQuery)) {
        mysqli_stmt_bind_param($stmnt, "i", $timetableID);
        mysqli_stmt_execute($stmnt);
        $result = mysqli_stmt_get_result($stmnt);

        if ($row = mysqli_fetch_array($result)) {

            if ((int)$row['tuitionID'] == (int)$_SESSION['tuitionID']) {
                $timetableID = $row['timetableID'];
                $year = $row['year'];
                $classroom = $row['classroom'];
                $subject = $row['subject'];
                $day = $row['day'];
                $time_of_room = $row['time_of_room'];

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
    <title>Edit Timetable</title>
    
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
                         <!-- Hidden field for timetableID -->
                        <input type="hidden" name="timetableID" value="<?php echo $timetableID;?>"/>

                        <div class="form-box <?php echo (!empty($year_err)) ? 'has-error' : ''; ?>">
                            <label>Year</label>
                            <input type="text" name="year" class="form-control" value="<?php echo $year; ?>" placeholder="Enter student's year">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $year_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($classroom_err)) ? 'has-error' : ''; ?>">
                            <label>Classroom</label>
                            <input type="text" name="classroom" class="form-control" value="<?php echo $classroom; ?>" placeholder="Enter classroom name">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $classroom_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($subject_err)) ? 'has-error' : ''; ?>">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control" value="<?php echo $subject; ?>" placeholder="Enter class subject">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $subject_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($day_err)) ? 'has-error' : ''; ?>">
                            <label>Day</label>
                            <input type="text" name="day" class="form-control" value="<?php echo $day; ?>" placeholder="Enter the day of the class">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $day_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($time_of_room_err)) ? 'has-error' : ''; ?>">
                            <label>Time</label>
                            <input type="text" name="time_of_room" class="form-control" value="<?php echo $time_of_room; ?>" placeholder="Enter class time">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $time_of_room_err; ?></span>
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