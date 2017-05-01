<?php
session_start();
$error_message="";
//require_once 'auth.php';
/*
 * The idea behind putting this information into variables 
 * is that in the future, this information could be coming
 * from the database instaead, making it easier for other
 * organizatons to customize as they deem necessary.
 */

 /*
  * TODO: pull organization, logo and auction year from database
  * TODO: create page to enter this information (admin.php?)
  */
$organization = "Middlebury Congregational Church";
$logo = "images/church.JPG";
$auction_year = 2015;
$activity = $auction_year . " Auction";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Middlebury UCC Annual Auction</title>
        <style>
            h1 {
                margin-top:-15px;
                
            }
            h2 {
                margin-top:-5px;
            }
        </style>
    </head>
    <body>
        <?php include_once 'header.php'; ?>

        <div id="content" >
            <h2><i>Welcome to the</i></h2>
            <h1><?php print $organization; ?></h1>
            <img src="<?php print $logo; ?>" width="512" height="384"alt="<?php print $organization; ?>">
            <h2><?php print $activity; ?></h2>
        </div>

    </body>
</html>
