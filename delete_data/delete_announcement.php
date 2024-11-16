<?php

require_once "../dbconf.php";

// Check if announcementID is provided
if (isset($_GET['announcementID']) && is_numeric($_GET['announcementID'])) {
    $announcementID=(int)$_GET['announcementID'];

// Delete announcement
$query = "DELETE FROM announcement WHERE announcementID = ?";
$stmt = $link -> prepare($query);

if ($stmt === false) {
    echo "Error: Unable to prepare statement.";
    exit;
}

// Bind the announcementID and execute the query
$stmt->bind_param("i", $announcementID);

if ($stmt->execute()) {
    echo "<script>
    alert('Announcement has been successfully deleted');
    window.location.href='../display_data/search_announcement.php';
    </script>";

} else {
    // Display the error message
    echo "Error: Unable to delete announcement: " . $stmt->error;

}

// Close the statement
$stmt->close();

} else {
    echo "Invalid announcement ID";
}

$link->close();

?>