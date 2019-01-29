<?php
// Header Elements
$page_title = 'Login';
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

// Form for Login Data
echo "<h1>Login</h1>";
echo "<form action = 'tdoa_login_action.php' method = 'POST'>";
echo "Email Address: <p><input type = 'text' name = 'email' size = '32' maxlength = '60'></p>";
echo "Password: <p><input type = 'password' name = 'pass' size = '32' maxlength = '256'></p>";
echo "<p><input type = 'submit' value = 'Login!'></p>";
echo "</form>";

// Footer Elements
include('tdoa_footer.html');
