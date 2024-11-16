<?php

include '../conf.php';

// Set the studentID
if (isset($_POST['studentID']) && is_numeric($_POST['studentID'])) {
  $studentID = ($_POST['studentID']);

// Retrieve the timetable data with year
$result = $connect ->query("SELECT * FROM timetable WHERE studentID='".$studentID."'");

// Initialize the empty array
$dataResult = array();

// Fetch the data
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
