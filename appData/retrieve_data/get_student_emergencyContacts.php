<?php

include '../conf.php';

// Set the studentID
if (isset($_POST['studentID']) && is_numeric($_POST['studentID'])) {
$studentID = intval($_POST['studentID']);

// Retrieve the emergency contacts data with studentID
$result = $connect -> query("SELECT * FROM emergencycontacts WHERE studentID='".$studentID."'");

// Initialize the empty array
$dataResult = array();

// Get the data
while ($row = $result ->fetch_assoc()) {
  $dataResult[] = $row;
}

// Use json to send the data
echo json_encode($dataResult);

} else {
  // Handle potential errors with prepared statement
  echo json_encode(['error' => 'Database error, please try again later.']);
}

?>