<?php

require_once "../dbconf.php";

// Check if parentID is provided
if (isset($_GET['parentID']) && is_numeric($_GET['parentID'])) {
$parentID=(int)$_GET['parentID'];

// Delete parents
$query = "DELETE FROM parents WHERE parentID=?";
$stmt = $link -> prepare($query);

if ($stmt === false) {
    echo "Error: Unable to prepare statement.";
    exit;
}

// Bind the parentID and execute the query
$stmt->bind_param("i", $parentID);

if ($stmt->execute()) {
    echo "<script>
    alert('Parent has been successfully deleted');
    window.location.href='../display_data/search_parents.php';
    </script>";

} else {
    // Display the error message
    echo "Error: Unable to delete parent: " . $stmt->error;

}

// Close the statement
$stmt->close();

} else {
    echo "Invalid parent ID";
}

$link->close();

?>