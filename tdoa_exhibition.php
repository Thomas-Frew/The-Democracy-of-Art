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
$page_title = 'Exhibition';
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
echo "<h1>Exhibition</h1>";
echo "<p class = 'major_subheading'>Feeling critical? Why not review some of the latest artworks?</p>";

// MySQL Query (fetches various artwork variables)
$q = "SELECT artwork_id, artwork_name, artwork_desc, artwork_img, first_name, last_name, karma, review_desc, upload_date FROM tdoa_artworks ORDER BY upload_date DESC, last_name ASC";
$r = mysqli_query($dbc, $q);

if(mysqli_num_rows($r) != 0) {
	echo "<table class = 'artwork'>";
	
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	
		// Assigns Colour of Upvote and Downvote Buttons to variables
		$artwork_id = $row['artwork_id'];
		$upvote_color = $downvote_color = "#ffffff";

		$q2 = "SELECT vote_type FROM tdoa_votes WHERE user_id = {$_SESSION['user_id']} AND artwork_id = $artwork_id";
		$r2 = mysqli_query($dbc, $q2);

		if(mysqli_num_rows($r2) == 1) {
			$data = mysqli_fetch_array($r2, MYSQLI_ASSOC);
			
			$vote_type = $data['vote_type'];
			if ($vote_type == "Upvote") {
				$upvote_color = "#f0b030";
			}
			elseif ($vote_type == "Downvote") {
				$downvote_color = "#3070f0";
			}
		}
		
		// Artwork and Other Related Elements
		echo "<tr class = 'artwork_master'>";
		echo "<td class = 'artwork_art'><img src = " . $row['artwork_img'] . "></td>";
		echo "<td class = 'artwork_text'><strong>" . $row['artwork_name'] . "</strong>: By " . $row['first_name'] . " " . $row['last_name'] . " (Posted at " . $row['upload_date'] . ")<p>" . $row['artwork_desc'] . "</p><p><i>" . $row['review_desc'] . "</i> <a href = 'tdoa_exhibition_review.php?artwork_id=" . $row['artwork_id'] ."'>Review!</a></p></td>";
		echo "<td class = 'artwork_karma'><a style = 'color: $upvote_color' href = 'tdoa_exhibition_upvote.php?artwork_id=" . $row['artwork_id'] ."'>▲</a><h2>" . $row['karma']. "</h2><a style = 'color: $downvote_color' href = 'tdoa_exhibition_downvote.php?artwork_id=" . $row['artwork_id'] ."'>▼</a></td></tr>";
	}
	echo "</table>";
}
else {
	echo "There are currently no artworks on the system. Why not <a href = 'tdoa_studio.php'>upload one</a>?";
}

// Footer Elements
include('tdoa_footer.html');
?>