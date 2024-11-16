<?php

require_once "../dbconf.php";

// Check if siblingID is provided
if (isset($_GET['siblingID']) && is_numeric($_GET['siblingID'])) {
    $siblingID=(int)$_GET['siblingID'];

// Delete siblings
$query = "DELETE FROM sibling WHERE siblingID=?";
$stmt = $link -> prepare($query);

if ($stmt === false) {
    echo "Error: Unable to prepare statement.";
    exit;
}

// Bind the siblingID and execute the query
$stmt->bind_param("i", $siblingID);

if ($stmt->execute()) {
    echo "<script>
    alert('Sibling has been successfully deleted');
    window.location.href='../display_data/search_siblings.php';
    </script>";

} else {
    // Display the error message
    echo "Error: Unable to delete sibling: " . $stmt->error;

}

// Close the statement
$stmt->close();

} else {
    echo "Invalid sibling ID";
}

$link->close();

?>