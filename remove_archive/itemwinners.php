<?php
//session_start();
require_once 'auth.php';


if($_POST)
{
    print 'In post';
    // update the record
    $p = array();
    foreach($_POST as $key=>$value)
    {
        $p[$key] = $value;
        print '<br>' . $key . '=' . $value;
    }
    
    // TODO: write up the code to update the record based on the ['AuctionItemId']
    
    header ('Location:itemwinners.php?auction=' . $p['auction'] . '&' . 'item=' . $p['NextNumber']);
}


$qs = array('auction'=>'','item'=>0);

if(isset($_GET['auction'])) {$qs['auction'] = $_GET['auction']; }
if(isset($_GET['item'])) { $qs['item']=$_GET['item']; }


if(!($qs['auction'] === 'live' || $qs['auction'] === 'silent'))
{
    include_once 'header.php';
    print '<div style="text-align:center;">';
    print '<h1>Select Auction</h1>';
    print '<a href="itemwinners.php?auction=silent" style="width:150px; margin-right:15px; border:1px solid #000000;">Silent Auction</a>';
    print '&nbsp;';
    print '<a href="itemwinners.php?auction=live" style="width:150px; margin-right:15px; border:1px solid #000000;">Live Auction</a>';
    print "</div>";
    exit;        
}
if($_GET)
{
    print 'we are in GET';
}

switch ($qs['auction']) 
{
    case 'live':
        $type='N';
        $typeTitle = 'Live';
        break;
    case 'silent':
        $type='Y';
        $typeTitle = 'Silent';
        break;
}
    
// Get the list of auction items, in order        

$order = array();
$next = array();
$items = array();

/*
$sql[] = "SELECT ";
$sql[] = "AuctionItemNumber";
$sql[] = "FROM auction.auctionitems";
$sql[] = "WHERE AuctionYear=" . $_SESSION['auctionyear'];
$sql[] = "AND SilentAuction='" . $type . "'";
$sql[] = "ORDER BY CAST(AuctionItemNumber AS unsigned)";
$sql[] = ";";

$order_results = mysql_query(implode(" ",$sql));
*/
$auction = array(
    "item"=> array()
);

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

$item = array(
    array()
        );

if($results)
{
    $i = 0;
    while($x = mysql_fetch_assoc($results))
    {
        // copy data into multidimensional array
        
        $item[] = array (
            'AuctionItemId'=>$x['AuctionItemId'], 
            'AuctionItemNumber'=>$x['AuctionItemNumber'],
            'AuctionItemDescription'=>$x['AuctionItemDescription'], 
            'WinningBidder'=>$x['WinningBidder'],
            'WinningBidAmount'=>$x['WinningBidAmount'],
            'NextNumber'=>0
                );
        
        // populate the 'NextNumber'
        if($i > 0)
        {
            $item[$i-1]['NextNumber'] = $item[$i]['AuctionItemNumber'];
        }
        $i++;
    }
}


/*
foreach($item as $x )
{
    print '<br>';
    foreach($x as $fields=>$v)
    {
        print $fields . ' = ' . $x[$fields] . ' | ';
    }
}
*/

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
            .itemlist tbody tr td 
            {
                white-space: nowrap;
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
            .itemlist input {
                width:50px;
            }
            .selecteditem
            {
                background-color:yellow;
            }
        </style>
    </head>
    <body onload="javascript:document.getElementById('EnterWinner').focus();">
        <?php include_once 'header.php'; ?>
        <div id="content">
            <div>
                <h1 style="margin-bottom:2px;">Auction Items</h1>
                <a href="itemwinners.php">Enter Item Winners</a>
            </div>
            <div class="pagecolumn">
                <h3><?php print $typeTitle; ?> Auction</h3>
                <form action="itemwinners.php" method="post" >
                <table class="itemlist">
                    <thead>
                    <td>Auction<br>Item<br>Number</td>
                    <td>Auction Item Description</td>
                    <td>Winning<br>Bidder<br>Number</td>
                    <td>Winning<br>Bid<br>Amount</td>
                    <td>&nbsp;</td>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        if($item)
                        {
                            for($row=1; $row<count($item); $row++)
                            {
                                print '<tr>';
                                if($item[$row]['AuctionItemNumber'] === $qs['item'])
                                {
                                    print '<input type="hidden" name="AuctionItemId" value="' . $item[$row]['AuctionItemId'] . '" />';
                                    print '<input type="hidden" name="NextNumber" value="' . $item[$row]['NextNumber'] . '" />';
                                    print '<input type="hidden" name="auction" value="' . $qs['auction'] . '" />';
                                    print '<input type="hidden" name="item" value="' . $qs['item'] . '" />';
                                    
                                    // current one beign edited
                                    print '<td class="selecteditem">' . '<input type="text" name="AuctionItemNumber" style="text-align:center;" value="' . $item[$row]['AuctionItemNumber'] . '" />' . '</td>';
                                    print '<td class="selecteditem" style="text-align:left;">' . '<input type="text" name="AuctionItemDescription" style="text-align:left;width:450px;" value="' . $item[$row]['AuctionItemDescription'] . '" />' . '</td>';
                                    print '<td class="selecteditem">' . '<input type="text" id="EnterWinner" name="WinningBidder" style="text-align:center;" value="' . $item[$row]['WinningBidder'] . '" autofocus />' . '</td>';
                                    print '<td class="selecteditem">' . '$' . '<input type="text" name="WinningBidAmount" style="text-align:right;" value="' . $item[$row]['WinningBidAmount'] . '" />' . '</td>';
                                    print '<td class="selecteditem">' . '<input type="submit" value="Update" style="width:75px;"/>' . '</td>';
                                }
                                else
                                {
                                    // static data
                                    print '<td>' . $item[$row]['AuctionItemNumber'] . '</td>';
                                    print '<td style="text-align:left;">' . $item[$row]['AuctionItemDescription'] . '</td>';
                                    print '<td>' . $item[$row]['WinningBidder'] . '</td>';
                                    print '<td style="text-align:right;">' . $item[$row]['WinningBidAmount'] . '</td>';
                                    print '<td>' . '<a href="itemwinners.php?auction=' . $qs['auction'] . '&' . 'item=' . $item[$row]['AuctionItemNumber'] . '">Edit</a>' . '</td>';
                                }
                                print '</tr>';
                            }
                            /*
                            foreach($item as $x=>$value )
                            {
//                                foreach($x as $field=>$v)
                                {
                                print '<tr>';
                                print '<td>' . $item['AuctionItemNumber'] . '</td>';
                                print '<td>' . $x['AuctionItemDescription'] . '</td>';
                                print '<td>' . $x['WinningBidderNumber'] . '</td>';
                                print '<td>' . $x['WinningBidAmount'] . '</td>';
                                print '<td>' . $x['NextNumber'] . '</td>';
                                print '</tr>';
                                }
                            }*/
/*{
    print '<br>';
    foreach($x as $fields=>$v)
    {
        print $fields . ' = ' . $x[$fields] . ' | ';
    }
}
                            
                            
                            for($i=1; $i<count($item); $i++)
                            {
                                print "<tr>";
                                print "<td>" . $dr[$i]['AuctionItemNumber'] . "</td>";
                                print '<td class="descriptioncolumn">' . '<a href="itemedit.php?iid=' . $dr[$i]['AuctionItemId'] .'">' . $dr[$i]['AuctionItemDescription'] . '</a>' . '</td>';
                                print "<td>" . $dr[$i]['WinningBidder'] . "</td>";
                                print '<td class="moneycolumn">' . '$ ' . $dr[$i]['WinningBidAmount'] . '</td>';
                                print '<td>' . $dr[$i]['NextNumber'] . '</td>';
                                print "</tr>";
                            }
*/                        }

                        ?>
                    </tbody>
                </table>
                    </form>
            </div>            	
                        
            
            
            
            
            
        </div>
    </body>
</html>
