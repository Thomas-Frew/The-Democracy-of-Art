<?php
// Header Elements
$page_title = 'Register';
include('tdoa_header.html');
include('tdoa_user_data.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	include_once('database_connection/connect_db.php');
	
	$errors = array();

	// Assigns First Name to a variable
	if(empty($_POST['first_name'])) {
		$errors[] = "Enter your first name."; 
	}
	else {
		$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}
	
	// Assigns Last Name to a variables
	if(empty($_POST['last_name'])) {
		$errors[] = "Enter your last name."; 
	}
	else {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	}
	
	// Assigns Email Address to a variable
	if(empty($_POST['email'])) {
		$errors[] = "Enter your email address."; 
	}
	else {
		$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}
	
	// Assigns Lively Mode Choice to a variable
	if(!empty($_POST['lively_mode'])) {
		$lm = "T"; 
	}
	else {
		$lm = "F";
	}
	
	// Assigns Password to a variable
	if(!empty($_POST['pass1'])) {
		// Compares Password with Repeat Password Field
		if($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = "Passwords do not match.";
		}
		else {
			$p = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
		}
	}
	else {
		$errors[] = "Enter a password."; 
	}
	
	// Checks For User With the Same Email
	if(empty($errors)) {
		$q = "SELECT user_id FROM tdoa_users WHERE email = '$e'";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) != 0) {
			$errors[] = "Email adress already registered. <a href = 'tdoa_login.php'>Login</a>";
		}
	}
	
	if (empty($errors)) {
	// MySQL Query (writes user into database)
	$q = "INSERT INTO tdoa_users (first_name, last_name, email, pass, reg_date, lively_mode, artworks, karma) VALUES ('$fn', '$ln', '$e', SHA2('$p', 256), NOW(), '$lm', 0, 0)";
	$r = mysqli_query($dbc, $q);
	if($r) {
		// Title and Subheadings
		echo "<h1>Registered!</h1>";
		echo "<p class = 'major_subheading'>Thanks for joining us $fn!</p>";
		echo "<p class = 'minor_subheading'>Remember your email ($e) and password, you'll need them to <a href = 'tdoa_login.php'>Login</a>!</p>";
		
		// Footer Elements
		mysqli_close($dbc);
		include('tdoa_footer.html');
		exit();
		}
	}
	
	// Error Handler
	else {
		echo "<h1>It's an error!</h1>";
		echo "<p class = 'major_subheading'>The following error(s) occured: <br>";
		foreach($errors as $msg) {
			echo "&bull; $msg <br>";
		}
		echo "</p><h3>Please try again.</h3><br>";
		mysqli_close($dbc);
	}
}

// Title
echo "<h1>Join the jury!</h1>";

// Form for Registration
echo "<form action = 'tdoa_register.php' method = 'POST'>";
echo "First Name: <p><input type = 'text' name = 'first_name' size = '32' maxlength = '20'></p>";
echo "Last Name: <p><input type = 'text' name = 'last_name' size = '32' maxlength = '40'></p>";
echo "Email Address: <p><input type = 'text' name = 'email' size = '32' maxlength = '60'></p>";
echo "Password: <p><input type = 'password' name = 'pass1' size = '32' maxlength = '256'></p>";
echo "Repeat Password: <p><input type = 'password' name = 'pass2' size = '32' maxlength = '256'></p>";
echo "<p><input type = 'checkbox' name = 'lively_mode' > Lively Mode (Animated background)</p>";
echo "<p><input type = 'submit' value = 'Register!'></p>";
echo "</form>";


// Footer Elements
include('tdoa_footer.html'); ?>