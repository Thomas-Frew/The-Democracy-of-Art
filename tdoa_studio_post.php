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

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$errors = array();

	if(empty($_FILES['file'])) {
		$errors[] = "Please select an artwork from your device.";
	}
	else {
		// Assigns Artwork Name to a variable
		if(empty($_POST['name'])) {
			$errors[] = "Please enter a name for this artwork.";
		}
		else {
			$name = mysqli_real_escape_string($dbc, trim($_POST['name']));
		}

		// Assigns Artwork Description to a variable
		if(empty($_POST['description'])) {
			$desc = "The author did not give this artwork a description.";
		}
		else {
			$desc = mysqli_real_escape_string($dbc, trim($_POST['description']));
		}

		$fn = mysqli_real_escape_string($dbc, $_SESSION['first_name']);
		$ln = mysqli_real_escape_string($dbc, $_SESSION['last_name']);
		
		// Assigns Upload Directory, Target File Directory and File Type to variables
		$upload_directory = "artworks/";
		$target_file = $upload_directory . basename($_FILES['file']['name']);
		$target_file = str_replace(' ', '_', $target_file);
		$file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		
		// Tests File Type Against Image File Types
		if($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "tif" ) {
			$errors[] = "Please upload an image file: png, jpg, jpeg or tif.";
		}
		
		// Tests File Against Existing Files
		if (file_exists($target_file)) {
			$errors[] = "Sorry, an artwork with this file name already exists.";
		}
		
		// Tests File Size Against Upload Limit
		if ($_FILES["file"]["size"] > 5000000) {
			$errors[] = "Please upload a file less than 5MB large.";
		}
	}
	
	if(empty($errors)) {
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
			// MySQL Query (writes artwork into database)
			$q = "INSERT INTO tdoa_artworks (artwork_name, artwork_desc, artwork_img, author_id, first_name, last_name, karma, review_desc, upload_date)
			VALUES('$name', '$desc', '$target_file', '{$_SESSION['user_id']}', '$fn', '$ln', 0, 'This artwork has not been reviewed yet.', NOW())";
			$r = mysqli_query($dbc, $q);
			
			if(mysqli_affected_rows($dbc) == 1) {
				// MySQL Query (updates artwork count)
				$q = "UPDATE tdoa_users SET artworks = artworks + 1 WHERE user_id = {$_SESSION['user_id']}";
				$r = mysqli_query($dbc, $q);
				
				if(mysqli_affected_rows($dbc) == 1) {
					load('tdoa_exhibition.php');
				}
				else {
					$errors[] = "Database connection failed. Please free to try again later.";
					include('tdoa_studio.php');
				}
			}
			else {
				$errors[] = "Database connection failed. Please free to try again later.";
				include('tdoa_studio.php');
			}
		} 
		else {
			$errors[] = "File upload failed. Please try again later.";
		}
	}
	else {
		include('tdoa_studio.php');
	}
}
else {
	load('tdoa_studio.php');
}
?>