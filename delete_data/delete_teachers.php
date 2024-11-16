<?php

require_once "../dbconf.php";

// Check if teacherID is provided
if (isset($_GET['teacherID']) && is_numeric($_GET['teacherID'])) {
    $teacherID=(int)$_GET['teacherID'];

// Delete students
$query = "DELETE FROM teachers WHERE teacherID=?";
$stmt = $link -> prepare($query);

if ($stmt === false) {
    echo "Error: Unable to prepare statement.";
    exit;
}

// Bind the teacherID and execute the query
$stmt->bind_param("i", $teacherID);

if ($stmt->execute()) {
    echo "<script>
    alert('Teacher has been successfully deleted');
    window.location.href='../display_data/search_teachers.php';
    </script>";

} else {
    // Display the error message
    echo "Error: Unable to delete teacher: " . $stmt->error;

}

// Close the statement
$stmt->close();

} else {
    echo "Invalid teacher ID";
}

$link->close();

?>