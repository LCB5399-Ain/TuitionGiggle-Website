<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/displayInfo.css">
    <title>Search Teachers</title>
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

    <div class="container mt-2">
        <div class="col-10 col-md-6 mx-auto" style="padding: 2%;">
            <div class="form-box-cont">
                <h2 class="text-center">Find Teacher's Name</h2>
                <input type="text" name="search" id="search" autocomplete="off" class="form-control" placeholder="Enter teacher's full name">
                <div id="output" class="mt-0"></div>
            </div>
        </div>
    </div>

    <div class="container-box mt-2">
        <table class="table table-bordered table-striped table-light">
        <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Date of Register</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>


                <tr>

                <?php

                session_start();

                // Code adapted from Yani, 2017
                // Make sure the user is logged in to the account
                if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                    echo "<br/>"."Please Login"."<br/>";
                    exit;
                }
                // End of adapted code

                include("../dbconf.php");

                $fetchQuery = "SELECT * FROM teachers ORDER BY fullName";
                $resultData = mysqli_query($link, $fetchQuery);

                if ($resultData && mysqli_num_rows($resultData) > 0) {
                    while ($row = mysqli_fetch_array($resultData)) {

                        if ((int)$row['tuitionID'] == (int)$_SESSION['tuitionID']) {
                            $teacherID = $row['teacherID'];
                            echo "<td>" . $row['teacherID'] . "</td>";
                            echo "<td>" . $row['fullName'] . "</td>";
                            echo "<td>" . $row['subject'] . "</td>";
                            echo "<td>" . $row['phoneNumber'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['address'] . "</td>";
                            echo "<td>" . $row['date_of_register'] . "</td>";
                            echo ("<td><a href=\"../edit_data/edit_teachers.php?teacherID=".$row['teacherID']."\">Edit</a></td>");
                            echo ("<td><a href=\"../delete_data/delete_teachers.php?teacherID=".$row['teacherID']."\">Delete</a></td>");
                            echo "</tr>";
                        }
                    }
                }

                mysqli_close($link);
                
                ?>

        </table>

        <div class="row">
            <div class="col-md-12 text-center">
                <a type="submit" class="btn" href="../insert_data/insert_teachers.php">Add a teacher</a>
            </div>
        </div>

    </div>


<!-- Function for searching teacher's id -->

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("search");
        const outputDiv = document.getElementById("output");

        searchInput.addEventListener("input", function() {
            let query = searchInput.value;

            if (query) {
                fetch('../search_data/search_teacher.php', {
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