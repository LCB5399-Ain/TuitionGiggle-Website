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
    $searchQuery = "SELECT * FROM parents 
                    WHERE fullName LIKE '" . mysqli_real_escape_string($link, $_POST['query']) . "%' 
                    AND parents.tuitionID = '" . mysqli_real_escape_string($link, $_SESSION['tuitionID']) . "'";
    $searchResult = mysqli_query($link, $searchQuery);

    if ($searchResult && mysqli_num_rows($searchResult) > 0) {
        while ($row = mysqli_fetch_assoc($searchResult)) {
            $parentID = $row['parentID'];
            $fullName = $row['fullName'];
            $phoneNumber = $row['phoneNumber'];
            $email = $row['email'];
            $address = $row['address'];
            $date_of_register = $row['date_of_register'];

            echo <<<HTML
            <br/>
            <a href="../edit_data/edit_parents.php?parentID={$parentID}" style="color: #ffffff; padding-top: 2px; overflow-y: auto;">
                ID: $parentID<br/>
                Full Name: $fullName<br/>
                Phone Number: $phoneNumber<br/>
                Email: $email<br/>
                Address: $address<br/>
                Date of Register: $date_of_register<br/>
            </a>
            <hr style="border-width: 2px; overflow-y: auto;">
            HTML;
        }
    } else {
        echo "<p style='color:red'>Parent is not found...</p>";
    }
}

?>