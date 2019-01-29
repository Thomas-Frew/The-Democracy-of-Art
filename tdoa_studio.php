<?php
// Top-Level Connection and Session Elements (non-database variation)
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
$page_title = 'Studio';
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
echo "<h1>Studio</h1>";
echo "<p class = 'major_subheading'>Feeling creative? Why not contribute some art? (Images will automatically resize: Beware the squishing!)</p>";

// Form for Artwork Uploads
echo "<form action = 'tdoa_studio_post.php' method = 'POST' enctype = 'multipart/form-data'>";
echo "File:<p><input class = 'minor_subheading'  type = 'file' name = 'file'></p>";
echo "Name:<p><input name = 'name' type = 'text' size = '68' maxlength = '40'></p>";
echo "Description:<p><textarea name = 'description' rows = '5' cols = '82' maxlength = '400'></textarea></p>";
echo "<input type = 'submit' value = 'Upload!'>";
echo "</form>";

// Footer Elements
include('tdoa_footer.html');
?>