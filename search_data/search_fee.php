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
    $searchQuery = "SELECT * FROM students 
    WHERE fullName LIKE '" . mysqli_real_escape_string($link, $_POST['query']) . "%' 
    AND students.tuitionID = '" . mysqli_real_escape_string($link, $_SESSION['tuitionID']) . "'";

$searchResult = mysqli_query($link, $searchQuery);

    if ($searchResult && mysqli_num_rows($searchResult) > 0) {
        while ($row = mysqli_fetch_assoc($searchResult)) {
            $studentID = $row['studentID'];
            $fullName = $row['fullName'];
            $year = $row['year'];
            
            echo <<<HTML
            <br/>
            <a href="../edit_data/edit_fees.php?studentID={$studentID}" style="color: #ffffff; padding-top: 2px; overflow-y: auto;">
                ID: $studentID<br/>
                Full Name: $fullName<br/>
                Year: $year<br/>
            </a>
            <hr style="border-width: 2px; overflow-y: auto;">
            HTML;
        }
    } else {
        ?>
        <p style="color:red">Student is not found...</p>
        <?php
    }


}

?>