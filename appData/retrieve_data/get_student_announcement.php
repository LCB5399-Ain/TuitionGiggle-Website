<?php

include '../conf.php';

// Set the tuitionID
if (isset($_POST['tuitionID']) && is_numeric($_POST['tuitionID'])) {
$tuitionID = intval($_POST['tuitionID']);

// Retrieve the announcement data with tuitionID
$query = $connect -> query("SELECT * FROM announcement WHERE tuitionID='".$tuitionID."'");

// Check query error
if (!$query) {
  echo json_encode(["error" => "Query failed: " . $connect->error]);
  exit();
}
// Initialize the empty array
$dataResult = array();

// Get the data
while ($row = $query ->fetch_assoc()) {
  $dataResult[] = $row;
}

// Use json to send the data
echo json_encode($dataResult);

} else {
  // Handle error if sql fails
  echo json_encode(["error" => "Failed to prepare the SQL query."]);
}

exit();

?>