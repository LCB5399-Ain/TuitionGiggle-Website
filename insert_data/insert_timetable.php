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
$studentID = 0;
$year = $classroom = $subject = $day = $time_of_room = "";
$year_err = $classroom_err = $subject_err = $day_err = $time_of_room_err = $studentID_err = "";


// Retrieve the students
$queryStudents = "SELECT * FROM students WHERE students.tuitionID = '{$_SESSION['tuitionID']}'";
$studentsResult = mysqli_query($link, $queryStudents);

if (mysqli_num_rows($studentsResult) <= 0) {
    // Display message if the student does not exist
    echo "<script>
    alert('This student does not exist');
    window.location.href='main_insert.php';
    </script>";
}

// Use POST to process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $studentID = 0;
    $year = $classroom = $subject = $day = $time_of_room = "";
    $year_err = $classroom_err = $subject_err = $day_err = $time_of_room_err = $studentID_err = "";


    // year validation
    $year = isset($_POST["year"]) ? trim($_POST["year"]) : "";
    if (empty($year)) {
        $year_err = "Please enter student's year.";
    } 

    // Name validation
    $classroom = isset($_POST["classroom"]) ? trim($_POST["classroom"]) : "";
    if (empty($classroom)) {
        $classroom_err = "Please enter the classroom name.";
    } 

    // Subject validation
    $subject = isset($_POST["subject"]) ? trim($_POST["subject"]) : "";
    if (empty($subject)) {
        $subject_err = "Please enter class subject.";
    }

    // Day validation
    $day = isset($_POST["day"]) ? trim($_POST["day"]) : "";
    if (empty($day)) {
        $day_err = "Please enter the day of the class.";
    } 

    // Time of room validation
    $time_of_room = isset($_POST["time_of_room"]) ? trim($_POST["time_of_room"]) : "";
    if (empty($time_of_room)) {
        $time_of_room_err = "Please enter the class time.";
    } 

    // studentID validation
    $studentID = isset($_POST["studentID"]) ? trim($_POST["studentID"]) : 0;
    if (empty($studentID)) {
        $studentID_err = "Please enter the student's ID.";
    } else {

        $query = "SELECT studentID FROM students WHERE studentID = ? AND tuitionID = ?";
        $stmnt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmnt, "ii", $studentID, $_SESSION['tuitionID']);
        mysqli_stmt_execute($stmnt);
        mysqli_stmt_store_result($stmnt);

        // CHeck if the student exist
        if(mysqli_stmt_num_rows($stmnt) <= 0) {
            $studentID_err = "Invalid student ID. Please enter the correct students's ID.";
            $studentID=0;
        }
        mysqli_stmt_close($stmnt);
    }


    // Checking for errors before insert data in database
    if (empty($studentID_err) && empty($year_err) && empty($classroom_err) && empty($subject_err) && empty($day_err) && empty($time_of_room_err)) {
       
        $insertQuery = "INSERT INTO timetable (tuitionID, year, classroom, subject, day, time_of_room, studentID) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmnt = mysqli_prepare($link, $insertQuery)) {
            mysqli_stmt_bind_param($stmnt, "ssssssi", $tuitionID, $year, $classroom, $subject, $day, $time_of_room, $studentID);

            $tuitionID = $_SESSION["tuitionID"];
            $studentID = $studentID;
            $year = $year;
            $classroom = $classroom;
            $subject = $subject;
            $day = $day;
            $time_of_room = $time_of_room;


        $timetableResult = mysqli_stmt_execute($stmnt);
        if ($timetableResult) {
            header("location: insert_timetable.php");
        } else {
            echo "Oops! Unable to add timetable. Please try again.";
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
    <title>Insert Timetable</title>
    <link rel="stylesheet" href="../style/insertMain.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Coustard|Lato&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>

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
<!-- End of adapted code -->

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-10 col-md-5 mx-auto">
            <div class="box">
                <div class="card bg-light mb-6">

                <div class="card-body">
                <?php echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">'; ?> 

                        <div class="form-box <?php echo (!empty($studentID_err)) ? 'has-error' : ''; ?>">
                            <label>Student ID</label>
                            <input type="text" name="studentID" class="form-control" value="<?php echo $studentID; ?>" placeholder="Enter student's ID">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $studentID_err; ?></span>
                        </div>

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
                            <select name="day" class="form-control">
                                <option value="" disabled selected>Select class day</option>
                                <option value="Monday" <?php echo ($day == 'Monday') ? 'selected' : ''; ?>>Monday</option>
                                <option value="Tuesday" <?php echo ($day == 'Tuesday') ? 'selected' : ''; ?>>Tuesday</option>
                                <option value="Wednesday" <?php echo ($day == 'Wednesday') ? 'selected' : ''; ?>>Wednesday</option>
                                <option value="Thursday" <?php echo ($day == 'Thursday') ? 'selected' : ''; ?>>Thursday</option>
                                <option value="Friday" <?php echo ($day == 'Friday') ? 'selected' : ''; ?>>Friday</option>
                                <option value="Saturday" <?php echo ($day == 'Saturday') ? 'selected' : ''; ?>>Saturday</option>
                                <option value="Sunday" <?php echo ($day == 'Sunday') ? 'selected' : ''; ?>>Sunday</option>
                            </select>
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $day_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($time_of_room)) ? 'has-error' : ''; ?>">
                            <label>Time</label>
                            <select name="time_of_room" class="form-control">
                                <option value="" disabled selected>Select class time</option>
                                <option value="09:00AM - 10:30AM" <?php echo ($time_of_room == '09:00AM - 10:30AM') ? 'selected' : ''; ?>>09:00AM - 10:30AM</option>
                                <option value="10:00AM - 11:30AM" <?php echo ($time_of_room == '10:00AM - 11:30AM') ? 'selected' : ''; ?>>10:00AM - 11:30AM</option>
                                <option value="11:00AM - 12:30PM" <?php echo ($time_of_room == '11:00AM - 12:30PM') ? 'selected' : ''; ?>>11:00AM - 12:30PM</option>
                                <option value="12:00PM - 1:30PM" <?php echo ($time_of_room == '12:00PM - 1:30PM') ? 'selected' : ''; ?>>12:00PM - 1:30PM</option>
                                <option value="1:00PM - 2:30PM" <?php echo ($time_of_room == '1:00PM - 2:30PM') ? 'selected' : ''; ?>>1:00PM - 2:30PM</option>
                                <option value="2:00PM - 3:30PM" <?php echo ($time_of_room == '2:00PM - 3:30PM') ? 'selected' : ''; ?>>2:00PM - 3:30PM</option>
                                <option value="3:00PM - 4:30PM" <?php echo ($time_of_room == '3:00PM - 4:30PM') ? 'selected' : ''; ?>>3:00PM - 4:30PM</option>
                                <option value="4:00PM - 5:30PM" <?php echo ($time_of_room == '4:00PM - 5:30PM') ? 'selected' : ''; ?>>4:00PM - 5:30PM</option>
                                <option value="5:00PM - 6:30PM" <?php echo ($time_of_room == '5:00PM - 6:30PM') ? 'selected' : ''; ?>>5:00PM - 6:30PM</option>
                                <option value="6:00PM - 7:30PM" <?php echo ($time_of_room == '6:00PM - 7:30PM') ? 'selected' : ''; ?>>6:00PM - 7:30PM</option>
                                <option value="7:00PM - 8:30PM" <?php echo ($time_of_room == '7:00PM - 8:30PM') ? 'selected' : ''; ?>>7:00PM - 8:30PM</option>
                                <option value="8:00PM - 9:30PM" <?php echo ($time_of_room == '8:00PM - 9:30PM') ? 'selected' : ''; ?>>8:00PM - 9:30PM</option>
                            </select>
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $time_of_room_err; ?></span>
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

            <div class="col-10 col-md-5 mx-auto">
                <div class="form-box-cont">
                    <h2 class="text-center">Find Student's ID</h2>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Enter student's full name">
                    <div id="output" class="mt-0"></div>
                </div>
        </div>
        </div>
    </div>
</div>



<!-- Function for searching student's id -->

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("search");
        const outputDiv = document.getElementById("output");

        searchInput.addEventListener("input", function() {
            let query = searchInput.value;

            if (query) {
                fetch('../search_data/search_student.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ query: query })
                })
                .then(response => response.text())
                .then(data => {
                    outputDiv.innerHTML = data;
                    outputDiv.style.display = 'block';
                })
                .catch(error => console.error('Error:', error));

                searchInput.addEventListener("focus", function() {
                    outputDiv.style.display = 'block';
                });
            } else {
                outputDiv.style.display = 'none';
            }
        });
    });
</script>


</body>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</html>