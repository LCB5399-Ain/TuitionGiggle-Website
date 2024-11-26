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
$totalAmount = 0.00;
$studentID_err = $totalAmount_err = "";

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

    // totalAmount validation
    $totalAmount = isset($_POST["totalAmount"]) ? trim($_POST["totalAmount"]) : "";
    if (empty($totalAmount)) {
        $totalAmount_err = "Please enter the totalAmount of fee payment.";
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
    if (empty($studentID_err) && empty($totalAmount_err)) {
       
        $insertQuery = "INSERT INTO fees (amount, tuitionID, studentID) VALUES (?, ?, ?)";

        if ($stmnt = mysqli_prepare($link, $insertQuery)) {
            mysqli_stmt_bind_param($stmnt, "dii", $totalAmount, $tuitionID, $studentID);

        $tuitionID = $_SESSION["tuitionID"];
        $studentID = $studentID;
        $totalAmount= $totalAmount;

        $studentResult = mysqli_stmt_execute($stmnt);
        if ($studentResult) {
            header("location: insert_fee_payment.php");
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
    <title>Insert Fee Payment</title>
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

                        <div class="form-box <?php echo (!empty($totalAmount_err)) ? 'has-error' : ''; ?>">
                            <label>Total Fee</label>
                            <input type="text" name="totalAmount" class="form-control" value="<?php echo $totalAmount; ?>" placeholder="Enter the total fee">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $totalAmount_err; ?></span>
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
                    <input type="text" name="search" id="search" autocomplete="off" class="form-control" placeholder="Enter student's full name">
                    <div id="output"></div>
                </div>
        </div>
        </div>
    </div>
<!-- End of adapted code -->
</div>



<!-- Function for searching student's id -->

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("search");
        const outputDiv = document.getElementById("output");

        searchInput.addEventListener("input", function() {
            let query = searchInput.value;

            if (query) {
                fetch('../search_data/search_student_in_parent.php', {
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
</script>


</body>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</html>