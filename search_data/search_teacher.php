<?php

session_start();

// Code adapted from Yani, 2017
// Make sure the user is logged in to the account
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<br/>"."Please Login"."<br/>";
    exit;
}
// End of adapted code

require_once "../dbconf.php";

// Search the query and display result
if (!empty($_POST['query'])) {
    $searchQuery = "SELECT * FROM teachers 
    WHERE fullName LIKE '" . mysqli_real_escape_string($link, $_POST['query']) . "%' 
    AND teachers.tuitionID = '" . mysqli_real_escape_string($link, $_SESSION['tuitionID']) . "'";

    $searchResult = mysqli_query($link, $searchQuery);

    if ($searchResult && mysqli_num_rows($searchResult) > 0) {
        while ($row = mysqli_fetch_assoc($searchResult)) {
            $teacherID = $row['teacherID'];
            $fullName = $row['fullName'];
            $subject = $row['subject'];

            echo <<<HTML
            <br/>
            <a href="../edit_data/edit_teachers.php?teacherID={$teacherID}" style="color: #ffffff; padding-top: 2px; overflow-y: auto;">
                ID: $teacherID<br/>
                Full Name: $fullName<br/>
                Subject: $subject<br/>
            </a>
            <hr style="border-width: 2px; overflow-y: auto;">
            HTML;
        }
    } else {
        ?>
        <p style="color:red">Teacher is not found...</p>
        <?php
    }
}

?>