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

function sendAnnouncementEmail($announcement) {
    global $link;

$result = mysqli_query($link, "SELECT email FROM parents WHERE email IS NOT NULL");
$emails = [];

// $userQuery = "SELECT email FROM parents WHERE email IS NOT NULL";
// $userResult = mysqli_query($link, $userQuery);

while ($row = mysqli_fetch_assoc($result)) {
    $emails[] = $row['email'];
}

foreach ($emails as $email) {
    $subject = "New Announcement: " . $announcement['title'];
    $message = $announcement['content'];
    $headers = "From: no-reply@yourapp.com";

    mail($email, $subject, $message, $headers);
    }
}

// Initialize the variables
$title = $announcement = "";
$title_err = $announcement_err = "";


// Use POST to process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $announcement = "";
    $title_err = $announcement_err = "";

    // Title validation
    $title = isset($_POST["title"]) ? trim($_POST["title"]) : "";
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter the title.";
    }

    // Announcement validation
    $announcement = isset($_POST["announcement"]) ? trim($_POST["announcement"]) : "";
    if (empty(trim($_POST["announcement"]))) {
        $announcement_err = "Please enter the announcement.";
    }

    // Checking for errors before insert data in database
    if (empty($title_err) && empty($announcement_err)) {
        $insertQuery = "INSERT INTO announcement (title, announcement, tuitionID) VALUES (?, ?, ?)";

        if ($stmnt = mysqli_prepare($link, $insertQuery)) {
            mysqli_stmt_bind_param($stmnt, "sss", $title, $announcement, $tuitionID);

        $tuitionID = $_SESSION["tuitionID"];
        $title = $title;
        $announcement = $announcement;

        $announcementResult = mysqli_stmt_execute($stmnt);
        if ($announcementResult) {

            $announcement = [
                'title' => $param_title,
                'content' => $param_announcement
            ];

            sendAnnouncementEmail($announcement);

            //sendNotification("New Announcement!", "A new announcement has been added: " . $param_title);
            // sendAnnouncementEmail($param_title, $announcementID);

            // $updateQuery = "UPDATE announcement SET notified = 1 WHERE announcementID = ?";
            // if ($updateStmnt = mysqli_prepare($link, $updateQuery)) {
            //     mysqli_stmt_bind_param($updateStmnt, "i", $announcementID);
            //     mysqli_stmt_execute($updateStmnt);
            //     mysqli_stmt_close($updateStmnt);
            // }

            header("location: insert_announcement.php");

            echo json_encode(["success" => "Announcement added and notification sent."]);

        } else {
            echo json_encode(["error" => "Error adding announcement: " . mysqli_error($link)]);
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
    <title>Insert Announcement</title>
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

                        <div class="form-box <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" placeholder="Enter the title">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $title_err; ?></span>
                        </div>

                        <div class="form-box <?php echo (!empty($announcement_err)) ? 'has-error' : ''; ?>">
                            <label>Announcement</label>
                            <input type="text" name="announcement" class="form-control" value="<?php echo $announcement; ?>" placeholder="Enter the announcement">
                            <!-- Display error message -->
                            <span class="text-danger"><?php echo $announcement_err; ?></span>
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

        </div>
    </div>
</div>
<!-- End of adapted code -->

</body>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</html>
