<?php
	$dbc = mysqli_connect
		('localhost','gary','garypass','tdoa')
	OR die 
		(mysqli_connect_error());
	mysqli_set_charset($dbc,'utf8');
?>
