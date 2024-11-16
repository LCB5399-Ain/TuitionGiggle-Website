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
    $searchQuery = "SELECT * FROM class 
                    WHERE classroom LIKE '" . mysqli_real_escape_string($link, $_POST['query']) . "%' 
                    AND class.tuitionID= '" . mysqli_real_escape_string($link, $_SESSION['tuitionID']) . "'";
    $searchResult = mysqli_query($link, $searchQuery);

    if ($searchResult && mysqli_num_rows($searchResult) > 0) {
        while ($row = mysqli_fetch_assoc($searchResult)) {
            $classID = $row['classID'];
            $classroom = $row['classroom'];
            $subject = $row['subject'];

            echo <<<HTML
            <br/>
            <a href="../display_data/display_students_class.php?studentGroupsID={$classID}" style="color: #ffffff; padding-top: 2px; overflow-y: auto;">
                ID: $classID<br/>
                Classroom: $classroom<br/>
                Subject: $subject<br/>
            </a>
            <hr style="border-width: 2px; overflow-y: auto;">
            HTML;
        }
    } else {
        ?>
        <p style="color:red">Classroom is not found...</p>
        <?php
    }
}

?>