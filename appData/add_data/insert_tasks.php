<?php

include '../conf.php';

// Code adapted from Kumar, 2023
// Reads raw input using json
$json = file_get_contents('php://input');
$obj = json_decode($json, true);
// End of adapted code

// Extract and retrieve data from input
$tuitionID = intval($obj['tuitionID']);
$classID = intval($obj['classID']);
$subject = $obj['subject'];
$task = $obj['task'];

// Insert data into the task table
$query = "INSERT INTO task (tuitionID, classID, subject, task) VALUES ($tuitionID, $classID, '$subject', '$task')";

// Show message if query is successful
if (mysqli_query($connect, $query)) {
    $message = 'The task was submitted successfully';
    header('Content-Type: application/json');

    // Convert message into json format
    $json = json_encode($message);

    echo $json;

} else {
    // Handle the sql error
    $error = mysqli_error($connect);
    echo json_encode("Error: " . $error);
}

mysqli_close($connect);

?>