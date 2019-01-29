<?php
if(!isset($_SESSION['user_id'])) {
	echo "<div class = 'user_data'>";
	
	// Login and Registration Links
	echo "<h3><a href = 'tdoa_register.php'>Register</a> &bull; <a href = tdoa_login.php>Login</a></h3>";
	
	echo "</div>";
}
else {
	echo "<div class = 'user_data'>";
	
		// User Information
		echo "<h2>{$_SESSION['first_name']} {$_SESSION['last_name']}</h2>";
		echo "<p class = 'major_subheading'>{$_SESSION['karma']} Karma</p>";
		
		// Dropdown Menu
		echo "<div class = 'dropdown'>";
		echo "<button class = 'dropdown_master'><h2>Explore<h2></button>";
		echo "<div class = 'dropdown_links'>";
		echo "<a href = 'tdoa_home.php'>Home</a>";
		echo "<a href = 'tdoa_profile.php'>Profile</a>";
		echo "<a href = 'tdoa_exhibition.php'>Exhibition</a>";
		echo "<a href = 'tdoa_studio.php'>Studio</a>";
		echo "<a href = 'tdoa_leaderboard.php'>Leaderboard</a>";
		echo "<a href = 'tdoa_settings.php'>Settings</a>";
		echo "<a href = 'tdoa_logout.php'>Logout</a>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		
	echo "</div>";
}