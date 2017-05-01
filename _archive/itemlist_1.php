<?php
//session_start();
require_once 'auth.php';

/*
 * Should not need any additional parts of this PHP block 
 * as the PHP code is placed in the body portion and it loops
 * between Silent ('Y') and Live ('N') auction types.
 */
 
//$sql = "";  //array();
//$sql1 = "";  //array();
$auctionType=array();
$auctionType[]='Y';
$auctionType[]='N';


/*
 * This is a concept of using arrays and looping to do both lists but coding the 
 * similarities only once.
 */
//$sql = array();


/*
 * This is the currently working, redundant method_exists
 */
 
$sql0 = buildSQL('N');
$sql1 = buildSQL('Y');

//$results = mysql_fetch_assoc(implode(" ",$sql));

//$results = mysql_query(implode(" ",$sql));
//$results1 = mysql_query(implode(" ",$sql1));
$results0 = mysql_query($sql0);
$results1 = mysql_query($sql1);

//TODO: create and show list of auction items
//TODO: create means to update auction items
//TODO: cretae means to easily update items of live auction
//TODO: create means to upload auction items from EXCEL or CSV
function buildSQL($silent)
{
    $s = array();
    $s[] = "SELECT ";
    $s[] = "AuctionItemId,";
    $s[] = "AuctionItemNumber,";
    $s[] = "AuctionItemDescription,";
    $s[] = "WinningBidder,";
    $s[] = "WinningBidAmount";
    $s[] = "FROM auction.auctionitems";
    $s[] = "WHERE AuctionYear=" . $_SESSION['auctionyear'];
    $s[] = "AND SilentAuction='" . $silent . "'";
    //$s[] = "ORDER BY AuctionItemNumber;";
    $s[] = "ORDER BY CAST(AuctionItemNumber AS unsigned)";
    $s[] = ";";
    return implode(" ",$s);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Auction Items</title>
        <style>
            #columns
            {
                width:100%;
            }
            .pagecolumn
            {
                
                float:left;
            }
            .itemlist
            {
                text-align:center;
                border:solid 1px black;
                font-size:13px;
                width:500px;
            }
            .itemlist a
            {
                text-decoration:none;
            }
            .itemlist a:hover
            {
                background-color:yellow;
            }
            .itemlist tbody tr td a
            {
                text-decoration:none;
            }
            .itemlist tbody tr td a:hover
            {
                background-color:yellow;
            }            
            .itemlist tbody tr:nth-child(even) 
            {
                background: #CCC;
            }
            .itemlist tbody tr:nth-child(odd) 
            {
                background: #FFF;
            }
            
            .itemlist .descriptioncolumn
            {
                white-space:nowrap;
                text-align:left;
            }
            .itemlist .moneycolumn
            {
                text-align:right;
                padding-right:3px;
            }
        </style>
    </head>
    <body>
        <?php include_once 'header.php'; ?>
        <div id="content">
            This is where the auction items will show
            
            <?php
            $sql = array();
            
            $typeTitle = 'Live';
            
            $auctionType=array();
            $auctionType[]='Y';
            $auctionType[]='N';
            
            foreach($auctionType as $type)
            {
            	if($type == 'N') { $typeTitle = 'Silent'; } 
            	
            	//$sql = buildSQL($type);
            	
            	$sql[] = "SELECT ";
                $sql[] = "AuctionItemId,";
                $sql[] = "AuctionItemNumber,";
                $sql[] = "AuctionItemDescription,";
                $sql[] = "WinningBidder,";
                $sql[] = "WinningBidAmount";
                $sql[] = "FROM auction.auctionitems";
                $sql[] = "WHERE AuctionYear=" . $_SESSION['auctionyear'];
                $sql[] = "AND SilentAuction='" . $type . "'";
                $sql[] = "ORDER BY CAST(AuctionItemNumber AS unsigned)";
                $sql[] = ";";
            	
                print "<p>" . implode(" ",$sql) . "</p>";
            	$results = mysql_query(implode(" ",$sql));
            	//$results = mysql_query($sql);
            	
            	?>
                <div class="pagecolumn">
                    <h1><?php print $typeTitle; ?> Auction</h1>
                    <table class="itemlist">
                        <thead>
                        <td>Auction<br>Item<br>Number</td>
                        <td>Auction Item Description</td>
                        <td>Winning<br>Bidder<br>Number</td>
                        <td>Winning<br>Bid<br>Amount</td>
                        </thead>
                        <tbody>
                            <?php
                            if($results0)
                            {
                                while ($dr = mysql_fetch_assoc($results0))
                                {
                                    print "<tr>";
                                    print "<td>" . $dr['AuctionItemNumber'] . "</td>";
                                    print '<td class="descriptioncolumn">' . '<a href="itemedit.php?iid=' . $dr['AuctionItemId'] .'">' . $dr['AuctionItemDescription'] . '</a>' . '</td>';
                                    print "<td>" . $dr['WinningBidder'] . "</td>";
                                    print '<td class="moneycolumn">' . '$ ' . $dr['WinningBidAmount'] . '</td>';
                                    print "</tr>";
                                }
                            }
                            else
                            {
                                print '<tr><td colspan="4">No results found</td></tr>';
                                print '<tr><td colspan="4">' . $sql . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>            	
            <?php 
            }
            // end of the loop by $auctionType
            ?>
            
            
            
            
            
            
            <div id="columns">
                <div class="pagecolumn">
                    <h1>Live Auction Items</h1>
                    <table class="itemlist">
                        <thead>
                        <td>Auction<br>Item<br>Number</td>
                        <td>Auction Item Description</td>
                        <td>Winning<br>Bidder<br>Number</td>
                        <td>Winning<br>Bid<br>Amount</td>
                        </thead>
                        <tbody>
                            <?php
                            if($results0)
                            {
                                while ($dr = mysql_fetch_assoc($results0))
                                {
                                    print "<tr>";
                                    print "<td>" . $dr['AuctionItemNumber'] . "</td>";
                                    print '<td class="descriptioncolumn">' . '<a href="itemedit.php?iid=' . $dr['AuctionItemId'] .'">' . $dr['AuctionItemDescription'] . '</a>' . '</td>';
                                    print "<td>" . $dr['WinningBidder'] . "</td>";
                                    print '<td class="moneycolumn">' . '$ ' . $dr['WinningBidAmount'] . '</td>';
                                    print "</tr>";
                                }
                            }
                            else
                            {
                                print '<tr><td colspan="4">No results found</td></tr>';
                                //print '<tr><td colspan="4">' . implode(" ",$sql) . '</td></tr>';
                                print '<tr><td colspan="4">' . $sql0 . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagecolumn">
                    <h1>Silent Auction Items</h1>
                    <table class="itemlist">
                        <thead>
                        <td>Auction<br>Item<br>Number</td>
                        <td>Auction Item Description</td>
                        <td>Winning<br>Bidder<br>Number</td>
                        <td>Winning<br>Bid<br>Amount</td>

                        </thead>
                        <tbody>
                            <?php
                            if($results1)
                            {
                                while ($dr = mysql_fetch_assoc($results1))
                                {
                                    print "<tr>";
                                    print "<td>" . $dr['AuctionItemNumber'] . "</td>";
                                    print '<td class="descriptioncolumn">' . '<a href="itemedit.php?iid=' . $dr['AuctionItemId'] .'">' . $dr['AuctionItemDescription'] . '</a>' . '</td>';
                                    print "<td>" . $dr['WinningBidder'] . "</td>";
                                    print '<td class="moneycolumn">' . '$ ' . $dr['WinningBidAmount'] . '</td>';
                                    print "</tr>";
                                }
                            }
                            else
                            {
                                print '<tr><td colspan="4">No results found</td></tr>';
                                //print '<tr><td colspan="4">' . implode(" ",$sql) . '</td></tr>';
                                print '<tr><td colspan="4">' . $sql1 . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
