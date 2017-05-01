<?php
require_once 'auth.php';

$x = $_REQUEST;

/*
 *          POST DATA CHANGES
 */

if($_POST)
{
    // update with the purchase information
    $data = new getData;

    $pay = array(
        "BidderId"=>0,
        "PaymentMethod"=>0,
        "PaymentDetails"=>"",
        "PaymentAdjustment"=>0,
        "TotalPaid"=>0
        );
    

    foreach($pay as $k=>$v)
    {
        if($k == 'PaymentDetails')
        {
            $pay[$k] = mysql_escape_string ($x[$k]);
        }
        elseif(is_numeric($x[$k]))
        {
            $pay[$k] = ($x[$k]);
        }
        else
        {
            $pay[$k] = 0;
        }   
    }
    
   
    //if($x['TotalPaid'] != $x['totalwinningbids'])
    //{
        $pay['PaymentAdjustment'] = $pay['TotalPaid'] - $x['totalwinningbids'];//adjust the PaymentAdjustment to make things match
    //}
    
            
    //$results = $data->updateBidderPayment($paymentinfo);
    $data->updateBidderPayment($pay);

    Header('Location:cashout.php?biddernumber=' . $x['BidderNumber'] . '&paid=Y');
}

/*
 *          GET BIDDER & PURCHASE INFORMATION
 */

$comic = '<img src="images/auction_comic_388x244.jpg" style="margin:0 auto;" />';
$showform = TRUE;
$sql = array();
$biddernumber=0;
$receiptlink = '';

if($_GET)
{
    //if(isset($_GET['biddernumber']))// && is_number($_GET['biddernumber']))
    if(isset($x['biddernumber']))
        {
        $biddernumber = $x['biddernumber'];
    }    

	$data = new getData;
	$data->bnum = $biddernumber;
        $data->auctionyear = $_SESSION['auctionyear'];
	

    $showform = FALSE;
    
    if(isset($x['paid']) && $x['paid'] == 'Y' )
    {
        $receiptlink = '<a href="receipt.php?biddernumber=' . $biddernumber . '" target="_receipt">Print Receipt</a>';
    }
 
    // GET BIDDER INFORMATION

    $bidderinformation = $data->getBidderByNumber();

    // GET WINNINGS
    
    $auctioninformation = $data->getPurchases();

    
    if(mysql_num_rows($bidderinformation) < 1)
    { 
        $error_message .= "The Bidder Selected, " . $biddernumber . " is either not in the system or<br>entered is not a valid number.Please try again.";
        $showform=TRUE;
    }
    elseif(mysql_num_rows($auctioninformation)<1)
    {
        $error_message .= "The Bidder Selected, " . $biddernumber . " does not have any winning bids.<br>If this is in error, please find the item(s) in question<br>and verify the correct winner. Then try again.";
        $showform=TRUE;           
    }
    else
    //if(!$showform=TRUE)
    {
        $comic='';
    }
        
}
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cash Out Bidder</title>
        <style>
            #bidderselect 
            {
                
                margin:0 auto;
            }
            .contentcolumn
            {
                float:left;
                
            }
            #bidderinformation,
            #auctioninformation,
            #paymentinformation
            {
                text-align:left;
                float:left; 
            }
            
            #bidderinformation ul,
            #paymentinformation ul
            {
                margin: 0 auto;
                list-style: none;
                margin-left:-30px;
            }
            #paymentinformation ul li
            {
                margin-bottom:15px;
            }
            #bidderinformation ul li label,
            #paymentinformation ul li label
            {
                display: inline-block;
                width: 150px;
                border-bottom:2px dotted #000000;
                white-space:nowrap;
            }
            #bidderinformation ul li span,
            #paymentinformation ul li span,
            #paymentinformation ul li input
            {
                display: inline-block;
                width: 100px;
                text-align:right;
                border:1px dotted #000000;
                margin-left:-2px;
                padding-left:2px;
                font-weight:bold;
                padding-right:5px;
            }

            
            #auctioninformation table
            {
                margin:0 auto;
            }
            #auctioninformation thead,
            #auctioninformation tfoot
            {
                background:#7FB4FF;
                font-weight:bold;
                font-size: 20px;
            }
            #auctioninformation tbody tr:nth-child(even) 
            {
                background: #EEE;
            }
            #auctioninformation tbody tr:nth-child(odd) 
            {
                background: #FFF;
            }
        </style>
    </head>
    <body>
        <?php include_once 'header.php'; ?>
        <div id="content">
            
                <?php
                if($showform)
                {
                    ?>
            <form method="get" action="cashout.php">
                <div id="bidderselect">
                <fieldset id="selectbidder">
                    <legend>Cash Out Bidder</legend>
                    <input type="text" name="biddernumber" placeholder="Enter bidder number" autofocus="true" style="text-algin:center;" />
                    <input type="submit" value="Get Bidder">
                    <input type="reset" value="Reset">
                    <br>
                    <?php print $comic; ?>
                </fieldset>
                </div>
            </form>
                <?php
                }
                ?>
                
                <?php
                if(!$showform)
                {
                    $bid = mysql_fetch_assoc($bidderinformation);
                    ?>
                <p>Verify the customer and winning bids before asking about payment method.</p>
                <div id="biddercashout">
                    <div class="contentcolumn" style="width:auto; text-align:left;">
                        <fieldset>
                            <legend>       Auction Information for </legend>
                        <h1 style="margin-bottom:-5px;margin-top:-5px;">                        
                            <?php print $bid['FirstName'] . ' ' . $bid['LastName']; ?>
                            (#<?php print $bid['BidderNumber']; ?>)
                        </h1>
                            (<a href="bidder.php?cid=<?php print $bid['CustomerId']; ?>">edit</a>)<br>
                            <div style="maring-top:5px; padding-left:15px;">
                                <?php 
                        if(strlen($bid['Address']) + strlen($bid['City']) + strlen($bid['State']) + strlen($bid['Zip']) > 0)
                        {
                            print $bid['Address'] . ', ' . $bid['City'] . ' ' . $bid['State'] . ' ' . $bid['Zip']; 
                        }
                        if(strlen($bid['Phone']) > 0)
                        {
                            print '<br>';
                            print $bid['Phone'];
                        }
                        if(strlen($bid['Email']) > 0)
                        {
                            print '<br>';
                            print $bid['Email'];
                        }
                        ?>
                            </div>
                        <br>
                    </fieldset>
                    </div>
                    
                    <div class="contentcolumn">
                        <fieldset id="auctioninformation">
                            <legend><h2>Auction Items Won!</h2></legend>
                            
                            <table width="100%" cellspacing="0" cellpadding="1">
                                <thead>
                                    <tr>
                                        <td>Item #</td>
                                        <td style="text-align:left;">Auction Item</td>
                                        <td style="text-align:right;">Winning Bid</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totals=0;
                                    $displaytype="";
                                    while($item=mysql_fetch_assoc($auctioninformation))
                                    {
                                        if($item['SilentAuction'] != $displaytype)
                                        {
                                            switch($item['SilentAuction'])
                                            {
                                                case 'Y':
                                                    $typedisplay='Silent';
                                                    break;
                                                case 'N':
                                                    $typedisplay='Live';
                                                    break;
                                            }
                                            $displaytype = $item['SilentAuction'];
                                            ?>
                                    <tr>
                                        <td colspan="4" style="text-align:center;"><b><?php print $typedisplay; ?> Auction Items</b></td>
                                    </tr>
                                    <?php        
                                        }
                                        ?>
                                    <tr>
                                        <td style="text-align:center;"><?php print $item['AuctionItemNumber']; ?></td>
                                        <td style="text-align:left;">
                                            <?php print $item['AuctionItemDescription']; ?>
                                        </td>
                                        <td style="text-align:right;">$<?php print $item['WinningBidAmount']; ?></td>
                                        <td><a href="auctionitems.php?Item=<?php print $item['AuctionItemNumber']; ?>" style="text-decoration: none;">
                                            &nbsp;
                                            edit
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                    $totals += $item['WinningBidAmount'];
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <td></td>
                                    <td style="text-align:left;">Totals:</td>
                                    <td style="text-align:right;">$<?php print $totals; ?></td>
                                    <td></td>
                                </tfoot>
                            </table>
                        </fieldset>
                        <fieldset id="paymentinformation">
                            <legend><h2>Cash Out</h2></legend>
                            <form method="post" action="cashout.php?biddernumber=<?php print $bid['BidderNumber']; ?>" >
                                <input type="hidden" name="BidderId" value="<?php print $bid['BidderId']; ?>" />
                                <input type="hidden" name="BidderNumber" value="<?php print $bid['BidderNumber']; ?>" />
                                <input type="hidden" name="WinningBidAmount" value="<?php print $totals; ?>" />
                            <ul>
                                <li>
                                    <label>Payment Method</label>
                                    <select name="PaymentMethod">
                                        <option value="1" <?php if(isset($bid['PaymentMethod']) && $bid['PaymentMethod']==1){print ' selected';} ?>>Cash</option>
                                        <option value="2"<?php if(isset($bid['PaymentMethod']) && $bid['PaymentMethod']==2){print ' selected';} ?>>Check</option>
                                        <option value="3"<?php if(isset($bid['PaymentMethod']) && $bid['PaymentMethod']==3){print ' selected';} ?>>Credit Card</option>
                                        <option value="0" <?php if(isset($bid['PaymentMethod']) && $bid['PaymentMethod'] != 1  && $bid['PaymentMethod'] != 2  && $bid['PaymentMethod'] != 3 ){print ' selected';} ?>>Select Method</option>
                                    </select>
                                </li>
                                <li>
                                    <label>Payment Details</label>
                                    $ <input type="text" name="PaymentDetails" placeholder="check#,confirm,etc." value="<?php if(isset($bid['PaymentDetails'])) { print $bid['PaymentDetails'];}; ?>" />
                                </li>
                                <li>
                                    <label>Winning Bids</label>
                                    $ <input type="text" name="totalwinningbids" value="<?php print $totals; ?>" readonly style="border:none;" />
                                </li>
                                <li>
                                    <label>Payment Adjustment</label>
                                    $ <input type="text" name="PaymentAdjustment" placeholder="Adjustment" value="<?php print $bid['PaymentAdjustment']; ?>" readonly style="border:none;"/>
                                </li>
                                <li>
                                    <ul>
                                        <hr>
                                    </ul>
                                </li>
                                <li>
                                    <label>Totals</label>
                                    $ <input type="text" name="TotalPaid" placeholder="Total Payments" value="<?php 
                                    if(isset($bid['TotalPaid'])) 
                                    { 
                                        print $bid['TotalPaid'];
                                    } 
                                    else 
                                    {
                                        print $totals + $bid['PaymentAdjustment'];
                                    } ; 
                                    ?>" />
                                </li>
                            </ul>
                            <input type="submit" value="Finsih Transaction" />
                            &nbsp;
                            <?php print $receiptlink; ?>
                            </form>
                        </fieldset>
                    </div>
                    </div>
                <?php
                
                }
                
                ?>
                </div>
                
            </form>
        </div>
    </body>
</html>
