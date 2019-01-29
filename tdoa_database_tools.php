<?php
// Load Function: Sends users to other pages. The default value is tdoa_login.php
function load($page = 'tdoa_login.php') {
	$url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	$url = rtrim($url, '/\\');
	$url .= '/' . $page;
	header("Location: $url");
	exit();
}

// Refresh Function: Ensures a user's karma and artwork count are are up-to-date 
function refresh($dbc) {
	// MySQL query (fetches karma and artwork_count)
	$q = "SELECT karma, artworks FROM tdoa_users WHERE user_id = {$_SESSION['user_id']}";
	$r = mysqli_query($dbc, $q);
	
	// Writes user's karma and artwork count as session variables
	if(mysqli_num_rows($r) == 1) {
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);

		$_SESSION['karma'] = $row['karma'];
		$_SESSION['artworks'] = $row['artworks'];
	}
}

// Login Validate Function: Checks a user's login details against the database
function login_validate($dbc, $email = '', $pwd = '') {
	$errors = array();
	
	// Assigns Entered Email Address to a variable
	if(empty($email)) {
		$errors[] = "Enter your email address.";
	} 
	else {
		$e = mysqli_real_escape_string($dbc, trim($email));
	}
	
	// Assigns Entered Password to a variable
	if(empty($pwd)) {
		$errors[] = "Enter your password."; 
	}
	else {
		$p = mysqli_real_escape_string($dbc, trim($pwd));
	}

	if(empty($errors)) {
		// MySQL Query (fecthes various user variables)
		$q = "SELECT user_id, first_name, last_name, reg_date, lively_mode, karma, artworks FROM tdoa_users WHERE email = '$e' AND pass = SHA2('$p', 256)";
		$r = mysqli_query($dbc, $q);
	
		// Returns User Variables
		if(mysqli_num_rows($r) == 1) {
			$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
			return array(true, $row);
		}
		else {
			$errors[] = "Email address and password not found.";
		}
	}
	return array(false, $errors);
}
?>
