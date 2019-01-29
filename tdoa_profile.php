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

$errors = array();

// MySQL Query (fetches user ID and karma)
$q = "SELECT user_id, karma FROM tdoa_users ORDER BY karma DESC, artworks DESC";
$r = mysqli_query($dbc, $q);

// Assigns Karma Rank to a variable
if (mysqli_num_rows($r) != 0) {
	$count = 1; // This variable ranks users in the order of which they appear: starting at 1 and incrementing for each user

	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		if ($row['user_id'] == $_SESSION['user_id']) {
			$karma_rank = $count;
		}
		$count ++;
	}
}
else {
	$errors[] = "Karma rank not found. Please try again later.";
	$karma_rank = "NF";
}

// MySQL Query (fetches user ID and artworks)
$q = "SELECT user_id, artworks FROM tdoa_users ORDER BY artworks DESC, karma DESC";
$r = mysqli_query($dbc, $q);

// Assigns Artwork Rank to a variable
if (mysqli_num_rows($r) != 0) {
	$count = 1;

	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		if ($row['user_id'] == $_SESSION['user_id']) {
			$artwork_rank = $count;
		}
		$count ++;
	}
}
else {
	$errors[] = "Artwork rank not found. Please try again later.";
	$author_rank = "NF";
}

// Complex MySQL Query (fetches Best Friend ID: the user ID of whoever has upvoted the you the most)
$q = "SELECT user_id, COUNT(*) AS magnitude FROM tdoa_votes WHERE vote_type = 'upvote' AND author_id = {$_SESSION['user_id']} AND user_id != {$_SESSION['user_id']} GROUP BY user_id ORDER BY magnitude DESC LIMIT 1";
$r = mysqli_query($dbc, $q);

// Determines Best Friend from Best Friend ID
if (mysqli_num_rows($r) == 1) {
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$best_friend_id = $row['user_id'];
	}
	
	//MySQL Query (fetches first and last of best friend)
	$q = "SELECT first_name, last_name FROM tdoa_users WHERE user_id = $best_friend_id";
	$r = mysqli_query($dbc, $q);
	
	if (mysqli_num_rows($r) == 1) {
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$best_friend_fn = $row['first_name'];
			$best_friend_ln = $row['last_name'];
		}
	}
	else {
		$errors[] = "Name of Best Friend not found. Please try again later.";
	}
}
else {
	$errors[] = "Best Friend not found: you are the only one on this database or noone has upvoted you! Please try again later.";
	$best_friend_fn = $_SESSION['first_name'];
	$best_friend_ln = $_SESSION['last_name'];
}

// Complex MySQL Query (fetches Mortal Enemy ID: the user ID of whoever has downvoted the you the most)
$q = "SELECT user_id, COUNT(*) AS magnitude FROM tdoa_votes WHERE vote_type = 'downvote' AND author_id = {$_SESSION['user_id']} AND user_id != {$_SESSION['user_id']} GROUP BY user_id ORDER BY magnitude DESC LIMIT 1";
$r = mysqli_query($dbc, $q);

// Determines Mortal Enemy name from Mortal Enemy ID
if (mysqli_num_rows($r) == 1) {
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$mortal_enemy_id = $row['user_id'];
	}
	
	//MySQL Query (fetches first and last of best friend)
	$q = "SELECT first_name, last_name FROM tdoa_users WHERE user_id = $mortal_enemy_id";
	$r = mysqli_query($dbc, $q);
	
	if (mysqli_num_rows($r) == 1) {
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$mortal_enemy_fn = $row['first_name'];
			$mortal_enemy_ln = $row['last_name'];
		}
	}
	else {
		$errors[] = "Name of Mortal Enemy not found. Please try again later.";
	}
}
else {
	$errors[] = "Mortal Enemy not found: you are the only one on this database or noone has downvoted you! Please try again later.";
	$mortal_enemy_fn = $_SESSION['first_name'];
	$mortal_enemy_ln = $_SESSION['last_name'];
}

// Complex MySQL Query (fetches Favourite Artist ID: the user ID of whoever you have upvoted the most)
$q = "SELECT author_id, COUNT(*) AS magnitude FROM tdoa_votes WHERE vote_type = 'upvote' AND user_id = {$_SESSION['user_id']} AND author_id != {$_SESSION['user_id']} AND user_id = {$_SESSION['user_id']} GROUP BY user_id ORDER BY magnitude DESC LIMIT 1";
$r = mysqli_query($dbc, $q);

// Determines Favourite Artist name from Favourite Artist ID
if (mysqli_num_rows($r) == 1) {
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$favourite_artist_id = $row['author_id'];
	}

	//MySQL Query (fetches first and last of best friend)
	$q = "SELECT first_name, last_name FROM tdoa_users WHERE user_id = $favourite_artist_id";
	$r = mysqli_query($dbc, $q);
	
	if (mysqli_num_rows($r) == 1) {
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$favourite_artist_fn = $row['first_name'];
			$favourite_artist_ln = $row['last_name'];
		}
	}
	else {
		$errors[] = "Name of Favourite Artist not found. Please try again later.";
	}
}
else {
	$errors[] = "Favourite Artist not found: you are the only one on this database or you haven't upvoted anyone! Please try again later.";
	$favourite_artist_fn = $_SESSION['first_name'];
	$favourite_artist_ln = $_SESSION['last_name'];
}

// Header Elements
$page_title = 'Profile';
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

// Title
echo "<h1>{$_SESSION['first_name']} {$_SESSION['last_name']}</h1>";

// Karma and Artwork Rankings
echo "<h3 class = 'major_subheading'><span class = 'rank'>#$karma_rank </span> Karma Rank</h3>";
echo "<h3 class = 'major_subheading'><span class = 'rank'>#$artwork_rank </span> Artwork Rank</h3>";

// Total Karma, Total Artworks and Date Registered
echo "<p>Total Karma: {$_SESSION['karma']}</p>";
echo "<p class = 'minor_subheading'>Total Artworks: {$_SESSION['artworks']}</p>";
echo "<p class = 'minor_subheading'>Date Registered: {$_SESSION['reg_date']}</p>";

// Best Friend, Mortal Enemy and Favourite Artist
echo "<p>Best Friend: $best_friend_fn $best_friend_ln</p>";
echo "<p class = 'minor_subheading'>Mortal Enemy: $mortal_enemy_fn $mortal_enemy_ln</p>";
echo "<p class = 'minor_subheading'>Favourite Artist: $favourite_artist_fn $favourite_artist_ln</p>";

// MySQL Query (fetches various artwork variables from best artwork)
$q = "SELECT artwork_id, artwork_name, artwork_desc, artwork_img, karma, review_desc, upload_date FROM tdoa_artworks WHERE author_id = {$_SESSION['user_id']} ORDER BY karma DESC, upload_date DESC LIMIT 1";
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
		
		// Best Artwork
		echo "<tr class = 'artwork_master'>";
		echo "<td class = 'artwork_art'><img src = " . $row['artwork_img'] . "></td>";
		echo "<td class = 'artwork_text'><h3>Best Artwork:</h3><strong>" . $row['artwork_name'] . "</strong> (Posted at " . $row['upload_date'] . ")<p>" . $row['artwork_desc'] . "</p><p><i>" . $row['review_desc'] . "</i> <a href = 'tdoa_exhibition_review.php?artwork_id=" . $row['artwork_id'] ."'>Review!</a></p></td>";
		echo "<td class = 'artwork_karma'><a style = 'color: $upvote_color' href = 'tdoa_exhibition_upvote.php?artwork_id=" . $row['artwork_id'] ."'>▲</a><h2>" . $row['karma']. "</h2><a style = 'color: $downvote_color' href = 'tdoa_exhibition_downvote.php?artwork_id=" . $row['artwork_id'] ."'>▼</a></td></tr>";
	}
}

// MySQL Query (fetches various artwork variables from worst artwork)
$q = "SELECT artwork_id, artwork_name, artwork_desc, artwork_img, karma, review_desc, upload_date FROM tdoa_artworks WHERE author_id = {$_SESSION['user_id']} ORDER BY karma ASC, upload_date DESC LIMIT 1";
$r = mysqli_query($dbc, $q);

if(mysqli_num_rows($r) != 0) {
	
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
		
		// Worst Artwork
		echo "<tr class = 'artwork_master'>";
		echo "<td class = 'artwork_art'><img src = " . $row['artwork_img'] . "></td>";
		echo "<td class = 'artwork_text'><h3>Worst Artwork:</h3><strong>" . $row['artwork_name'] . "</strong> (Posted at " . $row['upload_date'] . ")<p>" . $row['artwork_desc'] . "</p><p><i>" . $row['review_desc'] . "</i> <a href = 'tdoa_exhibition_review.php?artwork_id=" . $row['artwork_id'] ."'>Review!</a></p></td>";
		echo "<td class = 'artwork_karma'><a style = 'color: $upvote_color' href = 'tdoa_exhibition_upvote.php?artwork_id=" . $row['artwork_id'] ."'>▲</a><h2>" . $row['karma']. "</h2><a style = 'color: $downvote_color' href = 'tdoa_exhibition_downvote.php?artwork_id=" . $row['artwork_id'] ."'>▼</a></td></tr>";
	}
	echo "</table>";
}

// Footer Elements
include('tdoa_footer.html');
?>
