<?php

require_once "../dbconf.php";

// Check if classID is provided
if (isset($_GET['classID']) && is_numeric($_GET['classID'])) {
    $classID=(int)$_GET['classID'];

// Delete classes
$query = "DELETE FROM class WHERE classID = ?";
$stmt = $link -> prepare($query);

if ($stmt === false) {
    echo "Error: Unable to prepare statement.";
    exit;
}

// Bind the classID and execute the query
$stmt->bind_param("i", $classID);

if ($stmt->execute()) {
    echo "<script>
    alert('Class has been successfully deleted');
    window.location.href='../display_data/search_classes.php';
    </script>";

} else {
    // Display the error message
    echo "Error: Unable to delete class: " . $stmt->error;

}

// Close the statement
$stmt->close();

} else {
    echo "Invalid class ID";
}

$link->close();

?>