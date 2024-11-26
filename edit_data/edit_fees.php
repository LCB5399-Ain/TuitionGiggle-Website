<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/display.css">
    <title>Edit Fee Payments</title>
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
        <div class="col-10 col-md-5 mx-auto" style="padding: 2%;">
            <div class="form-box-cont">
                <h2 class="text-center">Find Student's Fee</h2>
                <input type="text" name="search" id="search" autocomplete="off" class="form-control" placeholder="Enter student's full name">
                <div id="output" class="mt-0"></div>
            </div>
        </div>
    </div>

    <div class="container-box">
        <table class="table table-bordered table-striped table-light">
            <thead class="table-light">
                <tr>
                    <th>Amount Paid</th>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Date of Payment</th>
                </tr>
            </thead>
            <tbody>


                <tr>

                <?php

                // Retrieve studentID
                $studentID = isset($_GET['studentID']) ? (int)$_GET['studentID'] : '';

                session_start();

                // Code adapted from Yani, 2017
                // Make sure the user is logged in to the account
                if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                    echo "<br/>"."Please Login"."<br/>";
                    exit;
                }
                // End of adapted code

                include("../dbconf.php");

                $fetchQuery = "SELECT * FROM fees WHERE fees.studentID='$studentID'";
                $result = mysqli_query($link, $fetchQuery);

                $totalAmount = 0;

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {

                        if ($row['tuitionID'] == (int)$_SESSION['tuitionID']) {
                            $feeID = $row['feeID'];
                            
                            $fetchQuery2 = "SELECT fullName FROM students WHERE students.studentID={$row['studentID']}";
                            $result2 = mysqli_query($link, $fetchQuery2);
                            $row2 = mysqli_fetch_array($result2);
                            echo "<td>" . $row['amount'] . "</td>";
                            echo "<td>" . $row['feeID'] . "</td>";
                            echo "<td>" . $row2['fullName'] . "</td>";
                            echo "<td>" . $row['date_of_payment'] . "</td>";
                            echo "</tr>";

                            $totalAmount += $row['amount'];
                        }
                    }

                    echo "<tr>";
                    echo "<td><strong>Total Amount: $" . $totalAmount . "</strong></td>";  
                    echo "</tr>";
                }

                mysqli_close($link);
                
                ?>

        </table>

        <div class="row">
            <div class="col-md-12 text-center">
                <a type="submit" class="btn" href="../insert_data/insert_fee_payment.php">Add receipt</a>
            </div>
        </div>
    </div>
<!-- End of adapted code -->


<!-- Function for searching student's id -->

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("search");
        const outputDiv = document.getElementById("output");

        searchInput.addEventListener("input", function() {
            let query = searchInput.value;

            if (query) {
                fetch('../search_data/search_fee.php', {
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