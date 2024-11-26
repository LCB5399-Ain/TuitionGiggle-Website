<?php

require_once "../dbconf.php";

// Check if complaintsID is provided
if (isset($_GET['complaintsID']) && is_numeric($_GET['complaintsID'])) {
    $complaintsID=(int)$_GET['complaintsID'];

// Delete complaint
$query = "DELETE FROM complaints WHERE complaintsID=?";
$stmt = $link -> prepare($query);

if ($stmt === false) {
    echo "Error: Unable to prepare statement.";
    exit;
}

// Bind the complaintsID and execute the query
$stmt->bind_param("i", $complaintsID);

if ($stmt->execute()) {
    echo "<script>
    alert('Complaint successfully deleted');
    window.location.href='../display_data/search_complaints.php';
    </script>";

} else {
    // Display the error message
    echo "Error: Unable to delete complaints: " . $stmt->error;

}

// Close the statement
$stmt->close();

} else {
    echo "Invalid complaints ID";
}

$link->close();

?>