<?php

require_once "../dbconf.php";

// Check if studentID is provided
if (isset($_GET['studentID']) && is_numeric($_GET['studentID'])) {
    $studentID=(int)$_GET['studentID'];

// Delete students
$query = "DELETE FROM students WHERE studentID=?";
$stmt = $link -> prepare($query);

if ($stmt === false) {
    echo "Error: Unable to prepare statement.";
    exit;
}

// Bind the studentID and execute the query
$stmt->bind_param("i", $studentID);

if ($stmt->execute()) {
    echo "<script>
    alert('Student has been successfully deleted');
    window.location.href='../display_data/search_students.php';
    </script>";

} else {
    // Display the error message
    echo "Error: Unable to delete student: " . $stmt->error;

}

// Close the statement
$stmt->close();

} else {
    echo "Invalid student ID";
}

$link->close();

?>