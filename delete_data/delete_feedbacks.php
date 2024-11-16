<?php

require_once "../dbconf.php";

// Check if feedbackID is provided
if (isset($_GET['feedbackID']) && is_numeric($_GET['feedbackID'])) {
    $feedbackID=(int)$_GET['feedbackID'];

// Delete feedback
$query = "DELETE FROM feedback WHERE feedbackID = ?";
$stmt = $link -> prepare($query);

if ($stmt === false) {
    echo "Error: Unable to prepare statement.";
    exit;
}

// Bind the feedbackID and execute the query
$stmt->bind_param("i", $feedbackID);

if ($stmt->execute()) {
    echo "<script>
    alert('Feedback has been successfully deleted');
    window.location.href='../display_data/search_feedbacks.php';
    </script>";

} else {
    // Display the error message
    echo "Error: Unable to delete feedback: " . $stmt->error;

}

// Close the statement
$stmt->close();

} else {
    echo "Invalid feedback ID";
}

$link->close();

?>