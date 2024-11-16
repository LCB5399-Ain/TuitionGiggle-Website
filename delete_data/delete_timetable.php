<?php

require_once "../dbconf.php";

// Check if feedbackID is provided
if (isset($_GET['timetableID']) && is_numeric($_GET['timetableID'])) {
$timetableID=(int)$_GET['timetableID'];

// Delete timetable
$query = "DELETE FROM timetable WHERE timetableID=?";
$stmt = $link -> prepare($query);

if ($stmt === false) {
    echo "Error: Unable to prepare statement.";
    exit;
}

// Bind the timetableID and execute the query
$stmt->bind_param("i", $timetableID);

if ($stmt->execute()) {
    echo "<script>
    alert('Timetable has been successfully deleted');
    window.location.href='../edit_data/edit_timetable.php';
    </script>";

} else {
    // Display the error message
    echo "Error: Unable to delete timetable: " . $stmt->error;

}

// Close the statement
$stmt->close();

} else {
    echo "Invalid timetable ID";
}

$link->close();

?>