<?php
// Top-Level Connection and Session Elements (non-update variation)
if(!isset($_SESSION)) { 
    session_start(); 
}

if(!isset($_SESSION['user_id'])) {
	include_once('tdoa_database_tools.php');
	load();
}

// Destroys All Session Variables
$_SESSION = array();
session_destroy();

// Header Elements
$page_title = 'Logout';
include('tdoa_header.html');
include('tdoa_user_data.php');

// Title and Subheading
echo "<h1>Arrivederci!</h1>";
echo "<p class = 'major_subheading'>You have successfully been logged out.</p>";

// Footer Elements
include('tdoa_footer.html');
?>