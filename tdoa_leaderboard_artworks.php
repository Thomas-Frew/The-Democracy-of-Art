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
$page_title = 'Leaderboard';
include('tdoa_header.html');
include('tdoa_user_data.php');

// Title and Subheading
echo "<h1>Leaderboard</h1>";
echo "<p class = 'major_subheading'>Feel like a competition? Here, you can see how you stack up against the jury!</p>";

// MySQL Query (fecthes various user variables)
$q = "SELECT first_name, last_name, reg_date, karma, artworks FROM tdoa_users ORDER BY artworks DESC, karma DESC";
$r = mysqli_query($dbc, $q);

if (mysqli_num_rows($r) != 0) {
	$count = 1; // This variable rank users in the order of which they appear: starting at 1 and incrementing for each user
	
	// Leaderboard Users
	echo "<table><tr><th>Rank</th><th>Name</th><th class = 'extra_padding'>Artworks</th><th class = 'extra_padding'>Karma</th><th>Date Registered</th><tr>";
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo "<tr><td>#$count</td>";
		echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
		echo "<td>" . $row['karma'] . "</td>";
		echo "<td>" . $row['artworks'] . "</td>";
		echo "<td>" . $row['reg_date'] . "</td></tr>";
		
		$count ++;
	}
	echo "</table>";
}
else {
	echo "<p>There are currently no users. Please <a href = 'tdoa_register.php'>register</a>.</p>";
}

// Additional Options
echo "<p>You can also rank artists by <a href = 'tdoa_leaderboard.php'>karma</a>.</p>";

// Footer Elements
include('tdoa_footer.html');
?>