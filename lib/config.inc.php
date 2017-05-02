<?php

//$dbname='mccauction';		// if using different database, change it here
$dbname='auction';
$dblogin='myauction';			// read-only login for the single table
$dbuser=$dblogin;
$dbpass='5z58d8RxJ6Y3eMeH';
$dbhost='localhost';
$error_message='';

$conni = new mysqli($dbhost,  $dbuser, $dbpass, $dbname);

$dbconn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('<p>Error connecting to mysql</p>');

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('<p>Error connecting to mysql</p>');
$checkMyToken = 'JzJhVpKTnnhRqn6Vb5aXG7jpd67B6N8J6pxBvjWmBVKZC';

?>
