<?php

include '../conf.php';

// Set the classID
if (isset($_POST['classID']) && is_numeric($_POST['classID'])) {
  $classID = intval($_POST['classID']);

// Retrieve the class data with classID
$result = $connect -> query("SELECT * FROM task WHERE classID='".$classID."' ORDER BY date_of_task DESC");

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