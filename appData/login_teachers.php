<?php

include 'conf.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    // Trim the input values
    $username = $_POST['username'];
    $password = $_POST['password'];

// Select teachers with the specific username
// Code adapted from Yassein, 2020
$result = $connect -> query("SELECT * FROM teachers WHERE username='".$username."'");

$dataResult = array();

// Loop through the results and check password
while ($row = $result -> fetch_assoc()) {

    // Check password that matches with the hashed password
    if (password_verify($password, $row['password'])) {
        $dataResult[] = $row;
    }
}

// Use json to send the data
echo json_encode($dataResult);
// End of adapted code

} else {
    // Handle potential errors with prepared statement
    echo json_encode(['error' => 'Database error, please try again later.']);
}

?>