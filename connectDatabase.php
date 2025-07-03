<?php
	$dbname = "cbaucham_gasdb";
	$dbuser = "cbaucham_guest";
	$dbpass = "ggcITEC3860@";
	$dbhost = "localhost";
	
	// $connect = mysql_connect($dbhost, $dbuser, $dbpass) or die("Unable to Connect to '$dbhost'");
	
	// mysql_select_db($dbname) or die("Could not open the db '$dbname'");

	$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

	if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
	}
?>