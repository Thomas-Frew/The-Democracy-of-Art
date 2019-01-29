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
$page_title = 'Home';
include('tdoa_header.html');
include('tdoa_user_data.php');

// Title and Subheading
echo "<h1>Home</h1>";
echo "<p class = 'major_subheading'>Welcome back {$_SESSION['first_name']} {$_SESSION['last_name']}!</p>";

// MySQL Query (fetches various artwork variables from best artwork)
$q = "SELECT artwork_id, artwork_name, artwork_desc, artwork_img, first_name, last_name, karma, review_desc, upload_date FROM tdoa_artworks ORDER BY karma DESC, upload_date DESC LIMIT 1";
$r = mysqli_query($dbc, $q);

if(mysqli_num_rows($r) != 0) {
	echo "<p class = 'minor_subheading'>If you're looking for something to do, why not admire the top rated artwork?</p>";
	echo "<table class = 'artwork'>";
	
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	
		// Assigns Colour of Upvote and Downvote Buttons
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
	echo "If you're looking for something to do, why not <a href = 'tdoa_studio.php'> upload an artwork?</a>";
}

// Footer Elements
include('tdoa_footer.html');
?>
