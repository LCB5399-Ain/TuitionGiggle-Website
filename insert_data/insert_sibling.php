<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

require_once "../dbconf.php";

$studentID = 0;
$fullName = $date_of_birth = "";
$fullName_err = $date_of_birth_err = $studentID_err = "";

// To check if there are students
$result = mysqli_query($link,"SELECT * FROM students WHERE students.tuitionID='{$_SESSION['tuitionID']}'");
if (mysqli_num_rows($result) <= 0) {

    echo "<script>
    alert('There are no students in this tuition centre');
    window.location.href='main_insert.php';
    </script>";
}

// Use POST to process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // fullName validation
    if (empty(trim($_POST["fullName"]))) {
        $fullName_err = "Please enter sibling's full name.";
    } else {
        $fullName = trim($_POST["fullName"]);
    }

    // Date of birth validation
    if (empty(trim($_POST["date_of_birth"]))) {
        $date_of_birth_err = "Please enter sibling's date of birth.";
    } else {
        $date_of_birth = trim($_POST["date_of_birth"]);
    }

    // studentID validation
    if (empty(trim($_POST["studentID"]))) {
        $studentID_err = "Please enter the student's ID.";
    } else {
        $studentID = $_POST["studentID"];

        // To check if this student exist at this tuition centre
        $result1 = mysqli_query($link,"SELECT studentID FROM students WHERE studentID='{$studentID}' AND tuitionID ='{$_SESSION['tuitionID']}'");
        if(mysqli_num_rows($result1) <= 0) {
            $studentID_err = "Please enter the correct students's ID.";
            $studentID=0;
        }
    }

    // Checking for errors before insert data in database
    if (
        empty($fullName_err) && empty($date_of_birth_err) && empty($studentID_err)
    ) {
        $sql = "INSERT INTO sibling (fullName, date_of_birth, tuitionID, studentID) VALUES (?, ?, ?, ?)";

        if ($stmnt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmnt, "sssi", $param_fullName, $param_date_of_birth, $param_tuitionID, $param_studentID);

        $param_tuitionID = $_SESSION["tuitionID"];
        $param_studentID = $studentID;
        $param_fullName = $fullName;
        $param_date_of_birth = $date_of_birth;

        if (mysqli_stmt_execute($stmnt)) {
            header("location: insert_sibling.php");
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
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
    <title>Insert Sibling</title>
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
                <a class="dropdown-item" href="../home.php">Home</a>
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

                <div class="card-body p-4">
                <?php echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">'; ?> 

                        <div class="form-box <?php echo (!empty($fullName_err)) ? 'has-error' : ''; ?>">
                            <label>Full Name</label>
                            <input type="text" name="fullName" class="form-control" value="<?php echo $fullName; ?>" placeholder="Enter sibling's full name">
                            <!-- Display error message -->
                            <span class="text-danger" style="color:red"><?php echo $fullName_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($date_of_birth)) ? 'has-error' : ''; ?>">
                            <label>Date Of Birth</label>
                            <input type="text" name="date_of_birth" class="form-control" value="<?php echo $date_of_birth; ?>" placeholder="Enter sibling's date of birth">
                            <!-- Display error message -->
                            <span class="text-danger" style="color:red"><?php echo $date_of_birth_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($studentID_err)) ? 'has-error' : ''; ?>">
                            <label>Student ID</label>
                            <input type="text" name="studentID" class="form-control" value="<?php echo $studentID; ?>" placeholder="Enter student's ID">
                            <!-- Display error message -->
                            <span class="text-danger" style="color:red"><?php echo $studentID_err; ?></span>
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

            <div class="col-13 col-sm-6 col-md-5 mx-auto">
                <div class="form-box-cont">
                    <h2 class="text-center">Find Student's ID</h2>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Enter student's full name">
                    <div id="output"></div>
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


</body>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</html>