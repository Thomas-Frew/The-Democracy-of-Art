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

if(isset($_GET['artwork_id'])) {
	$errors = array();
	$artwork_id = $_GET['artwork_id'];

	// MySQL Query (fetches author)
	$q = "SELECT author_id FROM tdoa_artworks WHERE artwork_id = $artwork_id";
	$r =  mysqli_query($dbc, $q);
	
	// Assigns Artwork Author to a variable
	if (mysqli_num_rows($r) == 1) {
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		$author_id = $row['author_id'];
	}
	else {
		$errors[] = "Author not found. Please try again later.";
		load('tdoa_exhibition.php');
	}	
	
	// Tests whether the user is trying to review their own artwork
	if ($author_id != $_SESSION['user_id']) {
	
		// MySQL Query (fetches artwork name)
		$q = "SELECT artwork_name FROM tdoa_artworks WHERE artwork_id = $artwork_id";
		$r =  mysqli_query($dbc, $q);
		
		// Assigns Artwork Name to a variable
		if (mysqli_num_rows($r) == 1) {
			$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
			$artwork_name = $row['artwork_name'];
			
			// Header Elements
			$page_title = 'Reviewing $artwork_name';
			include('tdoa_header.html');
			include('tdoa_user_data.php');

			// Error Handler
			if(isset($errors) && !empty($errors)) {
				echo "<h1>It's an error!</h1>";
				echo "<p class = 'major_subheading'>The following error(s) occured: <br>";
				foreach($errors as $msg) {
					echo "&bull; $msg <br>";
				}
				echo "</p><h3>Please try again.</h3><br>";
			}

			// Title and Subheading
			echo "<h1>Review</h1>";
			echo "<p class = 'major_subheading'>Reviewing <i>$artwork_name...</i></p>";

			// Form for Reviews
			echo "<form action = 'tdoa_exhibition_review_post.php?artwork_id=" . $artwork_id . "' method = 'POST' accept-charset = 'utf-8'>";
			echo "<p><textarea name = 'review' rows = '1' cols = '62' maxlength = '60'></textarea></p>";
			echo "<input type = 'submit' value = 'Review!'>";
			echo "</form>";
		}
		else {
			$errors[] = "Artwork name not found. Please try again later.";
			include('tdoa_exhibition.php');
		}
	}
	else {
		$errors[] = "You can't review your own artwork! Why not upvote it instead?";
		include('tdoa_exhibition.php');
	}
}
else {
	$errors[] = "Artwork not found. Please try again later.";
	include('tdoa_exhibition.php');
}
?>