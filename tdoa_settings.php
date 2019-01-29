<?php
// Top-Level Connection and Session Elements
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
	refresh($dbc);
}

// Header Elements
$page_title = 'Settings';
include('tdoa_header.html');
include('tdoa_user_data.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$errors = array();
	
	// Assigns Lively Mode Choice to a variable
	if(!empty($_POST['lively_mode'])) {
		$lm = "T"; 
	}
	else {
		$lm = "F";
	}
	
	// Assigns Given Password to a variable and validates it 
	if(empty($_POST['pass'])) {
		$errors[] = "Enter your password.";
	}
	else {
		$p = mysqli_real_escape_string($dbc, trim($_POST['pass']));
	}
	
	if (empty($errors)) {
		$q = "SELECT user_id FROM tdoa_users WHERE user_id = {$_SESSION['user_id']} AND pass = SHA2('$p', 256)";
		$r = mysqli_query($dbc, $q);
		
		if (mysqli_num_rows($r) == 1) {
			$q = "UPDATE tdoa_users SET lively_mode = '$lm' WHERE user_id = {$_SESSION['user_id']} AND pass = SHA2('$p', 256)";
			$r = mysqli_query($dbc, $q);
			
			if (!mysqli_error($dbc)) {
				echo "<h1>Success!</h1>";
				echo "<p class = 'major_subheading'>You settings have been successfully saved.</p>";
				echo "<p class = 'minor_subheading'>Here's your <a href = 'tdoa_home.php'>ticket home</a>!</p>";
				
				$_SESSION['lively_mode'] = $lm;
				
				// Footer Elements
				mysqli_close($dbc);
				include('tdoa_footer.html');
				exit();
			}
			else {
				$errors[] = "Database connection failed. Please try again later.";
			}
		}
		else {
			$errors[] = "Incorrect Password. Please try again.";
		}
	}
		// Error Handler
		echo "<h1>It's an error!</h1>";
		echo "<p class = 'major_subheading'>The following error(s) occured: <br>";
		foreach($errors as $msg) {
			echo "&bull; $msg <br>";
		}
		echo "</p><h3>Please try again.</h3><br>";
		mysqli_close($dbc);
}

// MySQL Query (fetches Lively Mode preference)
$q = "SELECT lively_mode FROM tdoa_users WHERE user_id = {$_SESSION['user_id']}";
$r = mysqli_query($dbc, $q);

if (mysqli_num_rows($r) == 1) {
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$lively_mode = $row['lively_mode'];
	}
}
else {
	$errors[] = "Lively Mdoe Setting not found. Please try again later.";
	$lively_mode = "F";
}

// Header and Subheading
echo "<h1>Settings</h1>";
echo "<p class = 'major_subheading'>Here, you can change up settings to make your experience even better!</p>";

// Form for Setting Specifications
echo "<form action = 'tdoa_settings.php' method = 'POST'>";
echo "<p><input type = 'checkbox' name = 'lively_mode' ";

// Special script to determine whether Lively Mode should be checked by default
if ($lively_mode == "T") {
	echo "checked";
}

echo "> Lively Mode (Animated background)</p>";
echo "Confirm Password: <p><input type = 'password' name = 'pass' size = '32' maxlength = '256'></p>";
echo "<p><input type = 'submit' value = 'Save!'></p>";
echo "</form>";

// Footer Elements
include('tdoa_footer.html');
 ?>