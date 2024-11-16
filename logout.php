<?php

session_start();

// Clear all session variables
foreach ($_SESSION as $key => $value) {
    unset($_SESSION[$key]);
}

// Check if the session is active before destroy session
if (session_status() == PHP_SESSION_ACTIVE) {
    session_destroy();
}

// Lead to Login page
header("location: login.php");

die();

?>