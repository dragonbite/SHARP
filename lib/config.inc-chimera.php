<?php

$dbname='mccauction';		// if using different database, change it here
$dblogin='myauction';			// read-only login for the single table
$dbuser=$dblogin;
$dbpass='5z58d8RxJ6Y3eMeH';
$dbhost='localhost';

$conn = mysql_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('<p>Error connecting to mysql</p>');

?>
