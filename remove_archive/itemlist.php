<?php
//session_start();
require_once 'auth.php';

/*
 * Should not need any additional parts of this PHP block 
 * as the PHP code is placed in the body portion and it loops
 * between Silent ('Y') and Live ('N') auction types.
 */
 


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
            <div>
                <h1 style="margin-bottom:2px;">Auction Items</h1>
                <a href="itemwinners.php">Enter Item Winners</a>
            </div>
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
            	
            	$results = mysql_query(implode(" ",$sql));
            	//$results = mysql_query($sql);
            	
            	?>
                <div class="pagecolumn">
                    <h3><?php print $typeTitle; ?> Auction</h3>
                    <table class="itemlist">
                        <thead>
                        <td>Auction<br>Item<br>Number</td>
                        <td>Auction Item Description</td>
                        <td>Winning<br>Bidder<br>Number</td>
                        <td>Winning<br>Bid<br>Amount</td>
                        </thead>
                        <tbody>
                            <?php
                            if($results)
                            {
                                while ($dr = mysql_fetch_assoc($results))
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
                                print '<tr><td colspan="4">' . implode(" ",$sql) . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>            	
            <?php 
                unset($sql);
                unset($results);
            }
            // end of the loop by $auctionType
            ?>
            
            
            
            
            
            
            
        </div>
    </body>
</html>
