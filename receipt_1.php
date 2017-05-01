<?php
require_once 'auth.php';
/*
 * This page assumes to be coming from the cashout.php page, which will have
 * ensured the bidder number is a legitimate bidder and that they have purchases.
 */

/*
 *  VARIABLES DEFINITIONS
 */
$output = array();
$biddernumber = $_REQUEST['biddernumber'];

$data = new getData();
$data->auctionyear = $_SESSION['auctionyear'];
$data->bnum = $biddernumber;

$paymethod = array("0"=>"None Specified","1"=>"Cash","2"=>"Check","3"=>"Credit",""=>"None Specified");

/*
 *  HEADER PORTION
 */
$output[] = 'Middlebury Congregational Church<br>';
$output[] = 'The Green<br>';
$output[] = 'Middlebury, CT 06762 ';
$output[] = '<span style="float:right">' . Date("M d,Y") . '</span><br>';

/*
 *      BIDDER SECTION
 */

$bidder_record = $data->getBidderByNumber();
$brs = mysql_fetch_assoc($bidder_record);

$output[] = 'Auction Receipt for Bidder Number  <b>' . $brs['BidderNumber'] . '</b><br>' ;
$output[] = '<hr>' ;
$output[] = '<b>Sold to:</b><br><blockquote>' . $brs['FirstName'] . ' ' . $brs['LastName'] . '<br>' ;

if($brs['Address']) {$output[] = $brs['Address'] . '<br>' ;};
if($brs['City'] || $brs['State'] || $brs['Zip']) {$output[] = $brs['City'] . ', ' . $brs['State'] . ' ' . $brs['Zip'] . '<br>' ;};
if($brs['Phone']) { $output[] = $brs['Phone'] . '<br>'; };
if($brs['Email']) {$output[] = $brs['Email']. '<br>'; };

$output[] = '</blockquote>' ;
$output[] = '<hr>';
$output[] = 'Payment Method: ';

$output[] = $paymethod[$brs['PaymentMethod']];
if(strlen($brs['PaymentDetails']) > 0 )
{
    $output[] = " #" . $brs['PaymentDetails']; 
}
$output[] = '<hr>';

/*
 *      PURCHASES SECTION
 */

$purchase_record = $data->getPurchases();
$output[] = '<table id="PurchaseList">';
$output[] = '<thead>';
$output[] = '<tr>';
$output[] = '<td></td>';
$output[] = '<td>Item Description</td>' ;
$output[] = '<td></td>';
$output[] = '</tr>';
$output[] = '</thead>';
$output[] = '<tbody>';

$total_purchases = 0;

while ($dr = mysql_fetch_assoc($purchase_record)) 
{
    $output[] = '<tr>';
    $output[] = '<td style="vertical-align: top; white-space: nowrap;">' . $dr['AuctionItemNumber'] . '</td>';
    $output[] = '<td style="vertical-align: top;">' . $dr['AuctionItemDescription'] . '</td>';
    $output[] = '<td align="right" style="vertical-align: top; white-space: nowrap;">$' . $dr['WinningBidAmount'] . '</td>';
    $output[] = '</tr>';
    
    $total_purchases = $total_purchases + $dr['WinningBidAmount'];
}

if($brs['TotalPaid'] != $total_purchases)
{
    $output[] = '<tr>';
    $output[] = '<td style="vertical-align: top; white-space: nowrap;">' . '&nbsp;' . '</td>';
    $output[] = '<td style="vertical-align: top;">' . 'Misc. Adjustments' . '</td>';
    $output[] = '<td align="right" style="vertical-align: top; white-space: nowrap;">$' . ($brs['TotalPaid'] - $total_purchases) . '</td>';
    $output[] = '</tr>';

}

$output[] = '</tbody>' ;
$output[] = '<tfoot>' ;
$output[] = '<tr>' ;
$output[] = '<td colspan="2" align="right">Total : </td>' ;
$output[] = '<td align="right" style="vertical-align: top; white-space: nowrap;">$ ' . $brs['TotalPaid'] . '</td>';
$output[] = '</tr>';
$output[] = '</tfoot>';
$output[] = '</table>';

?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <style>
            /*
            BODY {
                padding: 8px;
                line-height: 1.33 ;
                background: white;
                font-size: 9pt;
            }
            HR { border: 1px inset }
            #layout {
                width:10in;
                float:right;
            }
            #churchreceipt{
                width:2.5in;
                float:right;
                font-size: 8pt;
                margin-right:1.0in;
            }
            #middle {
                width:2.5in;
                float:right;
                font-size: 8pt;
                margin-right:0.75in;
            }
            #customerreceipt{
                
                width:2.5in;
                float:right;
                font-size: 8pt;
            }
            #PurchaseList {
                font-size:8pt;
                width:100%;
            }
            #PurchaseList thead {
                text-decoration: underline;
                font-weight:bold;
            }
            #PurchaseList tfoot td {
                border-top-style: solid;
                text-decoration: underline;
                border-top-width: 1px;
                border-top-color: #000000;
            }
            */
            #layout {
                width:100%;
                padding:0 auto;
            }
            #layout div {
                float:left;
                width:33%;
                font-size:10px;
            }
            #customerreceipt {
                /* do not hide */
            }
            #middle,
            #churchreceipt {
                display:none;
            }
            
            #PurchaseList tfoot tr td {
                border-top: 1px solid #999999;
            }
            
            
            @media print
            {
                .actionButton {display:none;}
				.page
				{
					-webkit-transform: rotate(-90deg); 
					-moz-transform:rotate(-90deg);
					filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
				}
                #layout {
                    width:11in;
                    height:8.5in;
                    position:absolute;
                    left:0px;
                    top:0px;
                    font-size:12px;
                }
                #layout div {
                    width:3in;
                    float:left;
                    margin-left:0.5in;
                }
                #customerreceipt,
                #middle,
                #churchreceipt {
                    display:block;
                }
            }
        </style>

        <script src="_master.js" type="text/javascript"></script>
        <script language="javascript" type="text/javascript">
            function printReceipt() {
               // alert("inside Javascript script");
               window.print();
               //window.close();
            }
        </script>
    </head>
    <body onload="return printreceipt();">
        <div id="layout">
            <button onclick="javascript:printReceipt();" class="actionButton">Print</button>
            <div id="customerreceipt">
                <form action="cashout.php" method="Post">
                <?php
                print implode("\n\r", $output);
                ?>
                </form>
            </div>
            <div id="middle">
                <?php
                print implode("\n\r", $output);
                ?>
            </div>
            <div id="churchreceipt">
                <?php
                print implode("\n\r", $output);
                ?>
            </div>
        </div>
    </body>
</html>

