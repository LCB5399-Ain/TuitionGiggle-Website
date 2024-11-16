<?php

include '../conf.php';

// Set the studentID
if (isset($_POST['studentID']) && is_numeric($_POST['studentID'])) {
$studentID = intval($_POST['studentID']);

// Retrieve the class data with studentID
$classResult1 = $connect ->query("SELECT * FROM studentgroups WHERE studentID='".$studentID."'");

// Initialize the empty arrays
$dataResult1 = array();
$dataResult2 = array();

// Fetch the data
while ($row= $classResult1 ->fetch_assoc()) {
  $dataResult1[] = $row;
  $classID = $row['classID'];

  // Retrieve the class data with classID
  $classResult2 = $connect->query("SELECT * FROM class WHERE classID='".$classID."'");

  // Fetch the data
  while ($row2 = $classResult2->fetch_assoc()) {
      $dataResult2[] = $row2;
  }

  }

// Use json to send the data
echo json_encode($dataResult2);

} else {
  // Handle potential errors with prepared statement
  echo json_encode(['error' => 'Database error, please try again later.']);
}

?>