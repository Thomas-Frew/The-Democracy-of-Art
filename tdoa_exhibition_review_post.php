<?php
// Top-Level Connection and Session Elements (non-update variation)
if(!isset($_SESSION)) { 
    session_start(); 
}

if(!isset($_SESSION['user_id'])) {
	include_once('tdoa_database_tools.php');
	load();
}
else {
	include_once('tdoa_database_tools.php');
	include_once('database_connection/connect_db.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$errors = array();

	// Assigns Review Content to a variable
	if(empty($_POST['review'])) {
		$errors[] = "Please enter a name for this artwork.";
	}
	else {
		$rev = mysqli_real_escape_string($dbc, trim($_POST['review']));
	}

	// Assigns Artwork ID to a variable
	if(isset($_GET['artwork_id'])) {
		$artwork_id = $_GET['artwork_id'];
	}
	else {
		$errors[] = "Artwork not found. Please try again later";
	}
	
	if(empty($errors)) {
		// MySQL Query (updates artwork review)
		$q = "UPDATE tdoa_artworks SET review_desc = '$rev' WHERE artwork_id = $artwork_id";
		$r = mysqli_query($dbc, $q);
		
		if(mysqli_affected_rows($dbc) == 1) {
			load('tdoa_exhibition.php');
		}
		else {
			$errors[] = "Database connection failed. Please free to try again later." . mysqli_error($dbc);
			include('tdoa_exhibition.php');
		}
		mysqli_close($dbc);
	}
	else {
		include('tdoa_exhibition.php');
	}
}
else {
	load('tdoa_exhibition.php');
}
?>