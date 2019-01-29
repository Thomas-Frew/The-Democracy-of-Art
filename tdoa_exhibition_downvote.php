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

if(isset($_GET['artwork_id'])) {
	$errors = array();
	$artwork_id = $_GET['artwork_id'];
	
	// MySQL Query (fetches author)
	$q = "SELECT author_id FROM tdoa_artworks WHERE artwork_id = $artwork_id";
	$r = mysqli_query($dbc, $q);
	
	// Assigns Artwork Author to a variable
	if (mysqli_num_rows($r) == 1) {
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		$author_id = $row['author_id'];
	}
	else {
		$errors[] = "Author not found. Please try again later.";
		include('tdoa_exhibition.php');
	}
	
	// MySQL Query (fetches vote-type)
	$q = "SELECT vote_type FROM tdoa_votes WHERE user_id = {$_SESSION['user_id']} AND artwork_id = $artwork_id";
	$r = mysqli_query($dbc, $q);

	// Assigns Previous and New Vote Type, and the action's Karma Impact to variables
	if (mysqli_num_rows($r) == 0) {
		$vote_type = "Downvote";
		$karma_change = -1;
	}
	elseif (mysqli_num_rows($r) == 1) {
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		$prev_vote_type = $row['vote_type'];
		
		if ($prev_vote_type == "Upvote") {
			$vote_type = "Downvote";
			$karma_change = -2;
		}
		elseif ($prev_vote_type == "Neutral") {
			$vote_type = "Downvote";
			$karma_change = -1;
		}
		elseif ($prev_vote_type == "Downvote") {
			$vote_type = "Neutral";
			$karma_change = 1;
		}
		else {
			$errors[] = "Vote type not found. Please try again later.";
			include('tdoa_exhibition.php');
		}
	}
	else {
		$errors[] = "Multiple votes found by the same user. Please try again later.";
	}
	
	if (!isset($prev_vote_type)) {
		// MySQL Query (writes vote into database)
		$q = "INSERT INTO tdoa_votes (vote_type, author_id, artwork_id, user_id, vote_date)
		VALUES('$vote_type', '$author_id', '$artwork_id', '{$_SESSION['user_id']}', NOW())";
		$r = mysqli_query($dbc, $q);
	}
	else {
		// MySQL Query (updates vote-type)
		$q = "UPDATE tdoa_votes SET vote_type = '$vote_type' WHERE user_id = {$_SESSION['user_id']} AND artwork_id = $artwork_id";
		$r = mysqli_query($dbc, $q);
	}
	
	if(mysqli_affected_rows($dbc) == 1) {
			// MySQL Query (updates artwork karma)
			$q = "UPDATE tdoa_artworks SET karma = karma + $karma_change WHERE artwork_id = $artwork_id";
			$r = mysqli_query($dbc, $q);
				
			if(mysqli_affected_rows($dbc) == 1) {
				// MySQL Query (updates user karma)
				$q = "UPDATE tdoa_users SET karma = karma + $karma_change WHERE user_id = $author_id";
				$r = mysqli_query($dbc, $q);
				
			if(mysqli_affected_rows($dbc) == 1) {
				load('tdoa_exhibition.php');
			}
			else {
				$errors[] = "Database connection failed. Feel free to try again later!";
				include('tdoa_exhibition.php');
			}
		}
		else {
			$errors[] = "Database connection failed. Feel free to try again later!";
			include('tdoa_exhibition.php');
		}
	}
	else {
		$errors[] = "Database connection failed. Feel free to try again later!";
		include('tdoa_exhibition.php');
	}
}
else {
	$errors[] = "Database connection failed. Please try again later.";
	include('tdoa_exhibition.php');
}
?>