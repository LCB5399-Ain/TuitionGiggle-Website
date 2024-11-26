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
$fullName = $phoneNumber = $parentEmail = $parentAddress = "";
$fullName_err = $phoneNumber_err = $parentEmail_err = $parentAddress_err = $studentID_err = "";


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

    // Full name validation
    $fullName = isset($_POST["fullName"]) ? trim($_POST["fullName"]) : "";
    if (empty($fullName)) {
        $fullName_err = "Please enter the full name.";
    } 

    // phoneNumber validation
    $phoneNumber = isset($_POST["phoneNumber"]) ? trim($_POST["phoneNumber"]) : "";
    if (empty($phoneNumber)) {
        $phoneNumber_err = "Please enter parent's phoneNumber.";
    } 

    // Email validation
    $parentEmail = isset($_POST["parentEmail"]) ? trim($_POST["parentEmail"]) : "";
    if (empty($parentEmail)) {
        $parentEmail_err = "Please enter the parent's email.";
    } 

    // Address validation
    $parentAddress = isset($_POST["parentAddress"]) ? trim($_POST["parentAddress"]) : "";
    if (empty($parentAddress)) {
        $parentAddress_err = "Please enter the parent's address.";
    } 

    // StudentID validation
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
if (empty($fullName_err) && empty($phoneNumber_err) && empty($parentEmail_err) && empty($parentAddress_err) && empty($studentID_err)) {

    $insertQuery = "INSERT INTO parents (tuitionID, fullName, phoneNumber, email, address, studentID) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmnt = mysqli_prepare($link, $insertQuery)) {
        mysqli_stmt_bind_param($stmnt, "sssssi", $tuitionID, $fullName, $phoneNumber, $parentEmail, $parentAddress, $studentID);

        $tuitionID = $_SESSION["tuitionID"];
        $studentID = $studentID;
        $fullName = $fullName;
        $phoneNumber = $phoneNumber;
        $parentEmail = $parentEmail;
        $parentAddress = $parentAddress;

        $parentResult = mysqli_stmt_execute($stmnt);
        if ($parentResult) {
            header("location: insert_parents.php");
        } else {
            echo "Oops! Unable to add this parent. Please try again.";
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
    <title>Insert Parents</title>
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

                <div class="card-body p-4">
                   
                <?php echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">'; ?> 
                        <div class="form-box <?php echo (!empty($fullName_err)) ? 'has-error' : ''; ?>">
                            <label>Full Name</label>
                            <input type="text" name="fullName" class="form-control" value="<?php echo $fullName; ?>" placeholder="Enter parent's full name">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $fullName_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($studentID_err)) ? 'has-error' : ''; ?>">
                            <label>Student ID</label>
                            <input type="text" name="studentID" class="form-control" value="<?php echo $studentID; ?>" placeholder="Enter student's ID">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $studentID_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($phoneNumber)) ? 'has-error' : ''; ?>">
                            <label>Phone Number</label>
                            <input type="text" name="phoneNumber" class="form-control" value="<?php echo $phoneNumber; ?>" placeholder="Enter parent's phone number">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $phoneNumber_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($parentEmail)) ? 'has-error' : ''; ?>">
                            <label>Email</label>
                            <input type="text" name="parentEmail" class="form-control" value="<?php echo $parentEmail; ?>" placeholder="Enter parent's email">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $parentEmail_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($parentAddress_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <input type="text" name="parentAddress" class="form-control" value="<?php echo $parentAddress; ?>" placeholder="Enter parent's address">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $parentAddress_err; ?></span>
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
                <input type="text" name="search" id="search"  class="form-control" placeholder="Enter student's full name">
                <div id="output"></div>

            </div>
        </div>
<!-- End of adapted code -->
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