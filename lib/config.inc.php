<?php

//$dbname='mccauction';		// if using different database, change it here
$dbname='';
$dblogin='';			// read-only login for the single table
$dbuser=$dblogin;
$dbpass='';
$dbhost='localhost';
$error_message='';

$conni = new mysqli($dbhost,  $dbuser, $dbpass, $dbname);

$dbconn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('<p>Error connecting to mysql</p>');

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('<p>Error connecting to mysql</p>');
$checkMyToken = 'JzJhVpKTnnhRqn6Vb5aXG7jpd67B6N8J6pxBvjWmBVKZC';

?>
