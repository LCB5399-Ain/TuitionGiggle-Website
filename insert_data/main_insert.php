<?php

session_start();

// Code adapted from Yani, 2017
// Make sure the user is logged in to the account
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
// End of adapted code
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/mainInsert2.css">
    <title>Insert</title>
    
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
                <a class="navbar-toggler-icon" id="navbardrop" role="button" data-toggle="dropdown"></a>
                
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="../display_data/main_search.php">Search Data</a>
                <a class="dropdown-item" href="../reset_pwd.php">Change Password</a>
                <a class="dropdown-item" href="../logout.php">Logout</a>
                </div>

            </li>
        </ul>
        </div>
</nav>

<div class="container-card">

    <div class="row justify-content-center">
        <div class="card">
            <div class="card-body">
                <img src="../style/images/student_icon.png" class="centered-image" alt="add_student icon" style="width: 50%; height: 50%;">
                    <div class="text-center">
                        <h5 class="title">Students</h5>
                        <p class="text">Add students or new students here.</p>
                        <a href="insert_students.php" class="btn btn-primary">Add Students</a>
                    </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <img src="../style/images/teacher_icon.png" class="centered-image" alt="add_teacher icon" style="width: 50%; height: 50%;">
                    <div class="text-center">
                        <h5 class="title">Teachers</h5>
                        <p class="text">Add teachers that works at the tuition center here.</p>
                        <a href="insert_teachers.php" class="btn btn-primary">Add Teachers</a>
                    </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <img src="../style/images/parent_icon.png" class="centered-image" alt="add_parent icon" style="width: 50%; height: 50%;">
                    <div class="text-center">
                        <h5 class="title">Parents</h5>
                        <p class="text">Add parents/guardians of the student here.</p>
                        <a href="insert_parents.php" class="btn btn-primary">Add Parents</a>
                    </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">

    <div class="card">
            <div class="card-body">
                <img src="../style/images/announcement.jpeg" class="centered-image" alt="add_announcement icon">
                    <div class="text-center">
                        <h5 class="title">Announcement</h5>
                        <p class="text">Add important announcement or updates here.</p>
                        <a href="insert_announcement.php" class="btn btn-primary">Add Announcement</a>
                    </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <img src="../style/images/class_icon.png" class="centered-image" alt="add_class icon">
                    <div class="col-md-12 text-center">
                        <h5 class="title">Class</h5>
                        <p class="text">Add any class information here.</p>
                        <a href="insert_class.php" class="btn btn-primary">Add Class</a>
                    </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <img src="../style/images/feedback_icon.png" class="centered-image" alt="add_feedback icon">
                    <div class="text-center">
                        <h5 class="title">Feedback</h5>
                        <p class="text">Add feedback needed for students here.</p>
                        <a href="insert_feedback.php" class="btn btn-primary">Add Feedback</a>
                    </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <img src="../style/images/timetable_icon.png" class="centered-image" alt="add_timetable icon">
                    <div class="text-center">
                        <h5 class="title">Timetable</h5>
                        <p class="text">Add timetable for students' class here.</p>
                        <a href="insert_timetable.php" class="btn btn-primary">Add Timetable</a>
                    </div>
            </div>
        </div>

<!-- End of adapted code -->
 
        <div class="row justify-content-center-1">
        <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <img src="../style/images/emergency_icon.png" class="centered-image" alt="add_emergency icon">
                    <div class="text-center">
                        <h5 class="title">Emergency Contact</h5>
                        <p class="text">Add emergency people contacts of the student here.</p>
                        <a href="insert_emergencyContact.php" class="btn btn-primary">Add Emergency Contact</a>
                    </div>
            </div>
        </div>
</div>
        <div class="col-md-6 col-lg-4-">
        <div class="card">
            <div class="card-body">
                <img src="../style/images/fee_icon.png" class="centered-image" alt="add_fee icon">
                    <div class="text-center">
                        <h5 class="title">Payment Receipt</h5>
                        <p class="text">Confirm the parent's payment made for the current year.</p>
                        <a href="insert_fee_payment.php" class="btn btn-primary">Add Payment Receipt</a>
                    </div>
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