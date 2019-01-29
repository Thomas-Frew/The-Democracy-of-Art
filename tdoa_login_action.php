<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// Top-Level Connection Elements
	include_once('database_connection/connect_db.php');
	include_once('tdoa_database_tools.php');
	
	$errors = array();
	
	// Validates Login Attempt
	list($check, $data) = login_validate($dbc, $_POST['email'], $_POST['pass']);
	
	if ($check) {
		session_start();
	
		// Writes Various User Variables as Session Variables
		$_SESSION['user_id'] = $data['user_id'];
		$_SESSION['first_name'] = $data['first_name'];
		$_SESSION['last_name'] = $data['last_name'];
		$_SESSION['reg_date'] = $data['reg_date'];
		
		$_SESSION['lively_mode'] = $data['lively_mode'];
		$_SESSION['artworks'] = $data['artworks'];
		$_SESSION['karma'] = $data['karma'];
		
		
		load('tdoa_home.php');
	}
	else {
		$errors = $data;
	}
	mysqli_close($dbc);
	include('tdoa_login.php');
}
?>
