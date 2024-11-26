<?php

include '../conf.php';

// Code adapted from Kumar, 2023
// Reads raw input using json
$json = file_get_contents('php://input');
$obj = json_decode($json, true);
// End of adapted code

// Extract and retrieve data from input
$tuitionID = intval($obj['tuitionID']);
$role = $obj['role'];
$fullName = $obj['fullName'];
$phoneNumber = $obj['phoneNumber'];
$title = $obj['title'];
$feedback = $obj['feedback'];

// Code adapted from Yassein, 2020
// Insert data into complaints table
$query = "INSERT INTO complaints (tuitionID, role, fullName, phoneNumber, title, feedback) VALUES ($tuitionID, '$role', '$fullName', '$phoneNumber', '$title', '$feedback')";

// Show message if query is successful
if (mysqli_query($connect, $query)) {
    $message = 'Your complaint has been submitted! We appreciate your feedback.';

    // Convert message into json format
    $json = json_encode($message);

    echo $json;

    // End of adapted code
    
} else {
    // Error handling if the prepared statement fails
    $response = ['error' => 'Database error. Please try again later.'];
    echo json_encode($response);
}

mysqli_close($connect);

?>