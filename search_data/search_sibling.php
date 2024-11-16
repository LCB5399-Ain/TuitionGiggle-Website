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
    $searchQuery = "SELECT * FROM sibling 
    WHERE fullName LIKE '" . mysqli_real_escape_string($link, $_POST['query']) . "%' 
    AND sibling.tuitionID = '" . mysqli_real_escape_string($link, $_SESSION['tuitionID']) . "'";
   
$searchResult = mysqli_query($link, $searchQuery);

    if ($searchResult && mysqli_num_rows($searchResult) > 0) {
        while ($row = mysqli_fetch_assoc($searchResult)) {
            $siblingID = $row['siblingID'];
            $fullName = $row['fullName'];
            $date_of_birth = $row['date_of_birth'];

            echo <<<HTML
            <br/>
            <a href="../edit_data/edit_siblings.php?siblingID={$siblingID}" style="color: #ffffff; padding-top: 2px; overflow-y: auto;">
                ID: $siblingID<br/>
                Full Name: $fullName<br/>
                Date of Birth: $date_of_birth<br/>
            </a>
            <hr style="border-width: 2px; overflow-y: auto;">
            HTML;
        }
    } else {
        ?>
        <p style="color:red">Sibling is not found...</p>
        <?php
    }
}

?>