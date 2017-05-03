<?php
require_once 'auth.php';

$iid = 0;	//Item Id value
$sql = array();

if($_POST)
{
	// update the database record
	$p = array();
	foreach($_POST as $k=>$v)
	{
		$p[$k] = mysql_escape_string($v);
	}
	
	if($p['AuctionItemId'] > 0 ) { $iid = $p['AuctionItemId']; }
	
	if($iid == 0)
	{
		// INSERT new record
		$sql[] = "INSERT INTO auction.auctionitems (AuctionItemNumber,AuctionItemDescription,WinningBidder,WinningBidAmount)";
		$sql[] = "VALUES (";
		$sql[] = $p['AuctionItemNumber'] . ",";
		$sql[] = $p['AuctionItemDescription'] . ",";
		$sql[] = $p['WinningBidder'] . ",";
		$sql[] = $p['WinningBidAmount'] . " ";
		$sql[] = ");";

	}
	else
	{
		// UPDATE existing record
		$sql[] = "UPDATE auction.auctionitems SET";
		$sql[] = "AuctionItemNumber='" . $p['AuctionItemNumber'] . "',";
		$sql[] = "AuctionItemDescription='" . $p['AuctionItemDescription'] . "',";
		$sql[] = "WinningBidder=" . $p['WinningBidder'] . ",";
		$sql[] = "WinningBidAmount=" . $p['WinningBidAmount'] . " ";
		$sql[] = "WHERE AuctionItemId=" . $iid . ";";
	}
	
        $results = mysql_query(implode(" ",$sql));
	
	if($iid == 0 )
	{
		$iid = mysql_insert_id();
	}
	//print "<p>" . implode(" ",$sql) . "</p>";
	header('Location:itemlist.php');
	
}

if(!$_GET)
{
    print '<p>An error has occurred. Please return to the <a href="itemslist.php">Auction Item List</a> and select the item to edit again.</p>';
    print '<p>If this persists, please contact the System Administrator.</p>';
    exit;
}

// populate the fields 

//$sql = array();

$sql[] = "SELECT";
$sql[] = "AuctionItemId,";
$sql[] = "AuctionItemNumber,";
$sql[] = "AuctionItemDescription,";
$sql[] = "WinningBidder,";
$sql[] = "WinningBidAmount,";
$sql[] = "SilentAuction,";
//$sql[] = "(select FirstName + ' ' + LastName from auction.customers as ac INNER JOIN auction.bidder as ab ON ab.CustomerId=ac.CustomerId and ab.AuctionYear = " . $_SESSION['auctionyear'] . "AND ab.BidderNumber=WinningBidder) as BidderName"; 
$sql[] = "'end' as theend";
$sql[] = "FROM";
$sql[] = "auction.auctionitems";
$sql[] = "WHERE";
$sql[] = "AuctionYear=" . $_SESSION['auctionyear'];
$sql[] = "AND AuctionItemId=" . $_GET['iid'] . ";";

//print "<p>" . implode(" ",$sql) . "</p>";
$results=mysql_query(implode(" ",$sql));

?><!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            #items{
                text-align: left;
                width: 500px;
            }
            #items ul {
                margin: 0 auto;
                list-style: none;
            }
            #items ul li {
                padding-bottom:10px;
            }
            #items ul li label {
                display: inline-block;
                width: 200px;               
            }
            #items ul li input,
            #items ul li textarea {
                width: 50px;
                padding-left: 10px;
                padding-top: 3px;
                padding-bottom: 2px;
                padding-right: 2px;
                text-align:center;
            }
            #items .submitbutton {
                margin-left: 200px;
            }
    </style>
    </head>
    <body>
		<?php include_once 'header.php'; ?>
	    <div id="content">
			<form method="post" action="itemedit.php">
	            
	            <fieldset id="items">
	            	
	                <legend>Edit Auction Item</legend>
	                <?php
	                if(!$results)
	                {
	                	print '<p>An error has occurred. Please return to the <a href="itemlist.php">Auction Item List</a> and select the item to edit again.</p>';
					    print '<p>If this persists, please contact the System Administrator.</p>';
	                }
	                else
	                {
	                	$dr = mysql_fetch_assoc($results);
                                
                                $getbidder = mysql_query("SELECT CONCAT(customers.FirstName,' ',customers.LastName) as 'BidderName' FROM auction.customers INNER JOIN auction.bidder ON customers.CustomerId=bidder.CustomerId AND bidder.AuctionYear=" . $_SESSION['auctionyear'] . " AND bidder.BidderNumber=" . $dr['WinningBidder'] . ";");
                                $biddername = mysql_fetch_array($getbidder);
	                ?>
	                <input type="hidden" name="AuctionItemId" value="<?php print $dr['AuctionItemId']; ?>" />
	                <ul>
	                	<li>
	                		<label for="AuctionItemNumber">Auction Item Number: </label>
	                		<input type="text" name="AuctionItemNumber" value="<?php print $dr['AuctionItemNumber']; ?>" />
	                	</li>
	                	<li>
	                		<label for="AuctionItemDescription">Item Description: </label>
                                        <textarea rows="3" style="width:200px;text-align:left;" name="AuctionItemDescription"><?php print $dr['AuctionItemDescription']; ?></textarea>
	                	</li>
                                <li>
                                    <label for="SilentAuction">Silent Auction</label>
                                    Silent <input style="width:auto;margin-right:50px;" type="radio" name="SilentAuction" value="Y" <?php if($dr['SilentAuction']=='Y'){print " checked ";} ?> />
                                    Live <input style="width:auto;"  type="radio" name="SilentAuction" value="N" <?php if($dr['SilentAuction']=='N'){print " checked ";} ?> />
                                </li>
	                	<li>
	                		<label for="WinningBidder">Winning Bidder: </label>
	                		<input type="text" name="WinningBidder" value="<?php print $dr['WinningBidder']; ?>" />
                                        <?php print $biddername[0]; ?>
                                        
	                	</li>
	                	<li>
	                		<label for="WinningBidAmount">Winning Bid Amount: </label>
	                		<input type="text" name="WinningBidAmount" value="<?php print $dr['WinningBidAmount']; ?>" />
	                	</li>
	                </ul>
                        <div style="width:100%; padding:0 auto;text-align:center;">
	                <input type="submit" value="Save" />
	                <input type="reset" value="Reset" />
                        <input type="submit" name="action" value="Cancel" />
                        </div>
	                <?php 
	                }
	                ?>
	            </fieldset>
	        </form>
	    </div>    
    </body>
</html>