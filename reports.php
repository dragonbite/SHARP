<?php
require_once 'auth.php';
?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php print $frame_legend; ?></title>
        <style>
            #customer{
                text-align: left;
                width: 900px;
            }
            #currenttotals ul {
                margin: 0 auto;
                list-style: none;
            }
            #currenttotals ul li label {
                display: inline-block;
                width: 150px;               
            }
            #currenttotals ul li input,
            #currenttotals ul li textarea {
                width: 200px;
                padding-left: 10px;
                padding-top: 3px;
                padding-bottom: 2px;
                padding-right: 2px;
            }
            #customer .submitbutton {
                margin-left: 200px;
            }
            #combinedtotals tfoot tr td,
            #combinedtotals thead tr td 
            {
                background-color:#CCC;
                text-align:center;
                font-weight:bold;
            }

        </style>
    </head>
    <body>
        <?php include_once 'header.php'; ?>
        <div id="content">
        <frameset id="currenttotals">
        	<legend>Current Totals</legend>
        	<?php
        	
        	?>
        	<table id="combinedtotals">
        		<thead>
        			<tr>
        				<td>Revenue From</td>
        				<td><?php print $_SESSION['auctionyear']; ?><br>Counts</td>
        				<td><?php print $_SESSION['auctionyear']; ?><br>Revenue</td>
        			</tr>
        		</thead>
        		<tbody>
        			<?php 
        			$sql = array();
        			$bidders=array("Counts"=>0,"Revenue"=>0);
        			$silent=array("Counts"=>0,"Revenue"=>0);
        			$live=array("Counts"=>0,"Revenue"=>0);
        			
        			// Get Bidder Totals
        			$sql[] = "SELECT";
        			$sql[] = "COUNT(BidderNumber) as 'Counts' ";
        			$sql[] = "FROM";
        			$sql[] = "auction.bidder";
        			$sql[] = "WHERE AuctionYear=" . $_SESSION['auctionyear'];
        			$sql[] = ";";
        			$bidderresults = mysql_query(implode(" ",$sql));
                                if($bidderresults)
                                {
                                    $bidderinfo=mysql_fetch_assoc($bidderresults);
                                    $bidders['Counts'] = $bidderinfo['Counts'];
                                    $bidders['Revenue'] = ($bidders['Counts'] * 10);
                                }
                                else
                                {
                                    //print implode(" ",$sql);
                                }
        			
        			
        			
        			// Get Auction Totals
        			unset($sql);
        			$sql[] = "SELECT";
        			$sql[] = "SilentAuction,";
        			$sql[] = "COUNT(AuctionItemId) as 'Counts',";
        			$sql[] = "SUM(WinningBidAmount) as 'Revenue' ";
        			$sql[] = "FROM";
        			$sql[] = "auction.auctionitems";
        			$sql[] = "WHERE AuctionYear=" . $_SESSION['auctionyear'];
        			$sql[] = "GROUP BY SilentAuction";
        			$sql[] = ";";
        			$auctionresults = mysql_query(implode(" ",$sql));
                                if($auctionresults)
                                {
                                    while($item=mysql_fetch_assoc($auctionresults))
                                    {
                                            switch($item['SilentAuction'])
                                            {
                                                    case 'Y':
                                                            $silent['Counts'] = $item['Counts'];
                                                            $silent['Revenue'] = $item['Revenue'];
                                                            break;
                                                    case 'N':
                                                            $live['Counts'] = $item['Counts'];
                                                            $live['Revenue'] = $item['Revenue'];
                                                            break;
                                            }
                                    }
                                }
                                else
                                {
                                    print implode(" ",$sql);
                                }
        			?>
        			<tr>
        				<td style="text-algin:left;">Bidder Entry Tickets</td>
        				<td style="text-align:right;"><?php print $bidders['Counts']; ?></td>
        				<td style="text-align:right;">$<?php print $bidders['Revenue']; ?></td>
        			</tr>
        			<tr>
        				<td style="text-algin:left;">Silent Auction Bids</td>
        				<td style="text-align:right;"><?php print $silent['Counts']; ?></td>
        				<td style="text-align:right;">$<?php print $silent['Revenue']; ?></td>
        			</tr>
        			<tr>
        				<td style="text-algin:left;">Live Auction Bids</td>
        				<td style="text-align:right;"><?php print $live['Counts']; ?></td>
        				<td style="text-align:right;">$<?php print $live['Revenue']; ?></td>
        			</tr>
        		</tbody>
        		<tfoot>
        			<tr>
        				<td style="text-align:left;">Totals</td>
        				<td style="text-align:right;"><?php print 0 + $bidders['Counts'] + $silent['Counts'] + $live['Counts']; ?></td>
        				<td style="text-align:right;">$<?php print 0 + $bidders['Revenue'] + $silent['Revenue'] + $live['Revenue']; ?></td>
        			</tr>
        		</tfoot>
        	</table>
        </frameset>
        </div>
    </body>
</html>