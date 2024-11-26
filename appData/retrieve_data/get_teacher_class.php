<?php

include '../conf.php';

// Set the studentID
if (isset($_POST['teacherID']) && is_numeric($_POST['teacherID'])) {
  $teacherID = intval($_POST['teacherID']);

// Code adapted from Yassein, 2020
// Retrieve the teacher data with teacherID
$result = $connect ->query("SELECT * FROM class WHERE teacherID='".$teacherID."'");

// Initialize the empty array
$dataResult = array();

// Fetch the data
while ($row = $result ->fetch_assoc()) {
  $dataResult[] = $row;
}

// Use json to send the data
echo json_encode($dataResult);
// End of adapted code

} else {
  // Handle potential errors with prepared statement
  echo json_encode(['error' => 'Database error, please try again later.']);
}


?>