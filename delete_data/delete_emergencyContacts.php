<?php

require_once "../dbconf.php";

// Check if emergencyContactID is provided
if (isset($_GET['emergencyContactID']) && is_numeric($_GET['emergencyContactID'])) {
    $emergencyContactID=(int)$_GET['emergencyContactID'];

// Delete contacts
$query = "DELETE FROM emergencyContacts WHERE emergencyContactID=?";
$stmt = $link -> prepare($query);

if ($stmt === false) {
    echo "Error: Unable to prepare statement.";
    exit;
}

// Bind the emergencyContactID and execute the query
$stmt->bind_param("i", $emergencyContactID);

if ($stmt->execute()) {
    echo "<script>
    alert('Contact has been successfully deleted');
    window.location.href='../display_data/search_emergencyContacts.php';
    </script>";

} else {
    // Display the error message
    echo "Error: Unable to delete contacts: " . $stmt->error;

}

// Close the statement
$stmt->close();

} else {
    echo "Invalid emergencyContacts ID";
}

$link->close();

?>