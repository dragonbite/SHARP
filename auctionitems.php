<?php
//session_start();
require_once 'auth.php';



/*
 * Flow of this page
 *		Inital page shows both table of auction times wich each entry having their own link for edit
 *		Click the edit link opens a form at the top of the page for data entry of everything. This
 *			includes the "Save & Next" option for winning bidder data entry
 */

$item = array(
	"AuctionItemId"=>0,
	"AuctionItemNumber"=>"0",
	"AuctionItemDescription"=>"",
	"WinningBidder"=>"",
	"WinningBidAmount"=>0,
	"SilentAuction"=>"Y",
	"NextNumber"=>0
	);
$sql = array();
$return_url = array();
$silent="";
$showform = FALSE;
 
// mwans we are updating
if($_POST)
{
	// means we are updating
    //foreach ($_POST as $key=>$value)
	//{
	//print '<br>' . $key . ' = ' . $value;
	//}
	
	foreach ($item as $field=>$value)
	{
            
		if(isset($_POST[$field])) 
		{
			$item[$field] = mysqli_escape_string($dbconn, $_POST[$field]);
		}
		
		if($field=="SilentAuction") 
		{
			$silent = $item["SilentAuction"];
		}
 //               print '<br>' . $field . '=' . $item[$field];
	}
	
//print $item['AuctionItemId'];
        if($item['AuctionItemId'] > 0)
        {
            // means updating record
            $sql[] = "UPDATE auction.auctionitems SET";
            $sql[] = "AuctionItemNumber = '" . $item['AuctionItemNumber'] . "',";
            $sql[] = "AuctionItemDescription = '" . $item['AuctionItemDescription'] . "',";
            if($item['WinningBidder']!='')
            {
                $sql[] = "WinningBidder = " . $item['WinningBidder'] . ",";
            }
			if($item['WinningBidAmount'] != '' )
			{
				$sql[] = "WinningBidAmount = " . $item['WinningBidAmount'] . ",";
            }
			$sql[] = "SilentAuction = '" . $item['SilentAuction'] . "'";
            $sql[] = "WHERE";
            $sql[] = "AuctionItemId=" . $item['AuctionItemId'] . ";";
	    
            //print '<br>' . implode(" ",$sql);
            $result = mysqli_query($dbconn, implode(" ",$sql)) or die('error saving record');
        }
        else
        {
            // means entering a new record
            $sql[] = "INSERT INTO auction.auctionitems (AuctionItemNumber,AuctionItemDescription,";
			if($item['WinningBidder']!='')
			{
			$sql[] = "WinningBidder,";
			}
			
			if($item['WinningBidAmount'] > 0 )
			{
				$sql[] = "WinningBidAmount,";
			}
			
			$sql[] = "SilentAuction,AuctionYear)";
            $sql[] = "VALUES (";
            $sql[] = "'" . $item['AuctionItemNumber'] . "',";
            $sql[] = "'" . $item['AuctionItemDescription'] . "',";
            if($item['WinningBidder'] != '')
            {
                $sql[] = "" . $item['WinningBidder'] . ",";
            }
			if($item['WinningBidAmount'] > 0)
			{
				$sql[] = "" . $item['WinningBidAmount'] . ",";
            }
			$sql[] = "'" . $item['SilentAuction'] . "',";
			$sql[] = "" . $_SESSION['auctionyear'] . "";
            $sql[] = ");";
            
            //print '<br>' . implode(" ",$sql);
            $results = mysqli_query($dbconn, implode(" ",$sql));

            if(!$results)
            {
                print "There was an error running your query.";
                exit;
            }             
            //$item[] = mysqli_insert_id($dbconn);
        }
        
	
	// build URL to go to next
	$redirect_url = "auctionitems.php";
	
	if($_POST['formaction'] == "Save & Next")
	{
		$redirect_url .= "?Silent=" . $item["SilentAuction"];
		$redirect_url .= "&Item=" . $item["NextNumber"];
	}

        header("Location:" . $redirect_url)	;


	
}

// means we are editing or adding a new record
if($_GET)  
{
    $showform = TRUE;
    if($_GET['Item'] > 0) 	
    {
        $AuctionItemNumber = $_GET['Item'];

        // means we are editing
        $sql[] = "SELECT";
        $sql[] = "AuctionItemId,";
        $sql[] = "AuctionItemNumber,";
        $sql[] = "AuctionItemDescription,";
        $sql[] = "WinningBidder,";
        $sql[] = "WinningBidAmount,";
        $sql[] = "SilentAuction,";
        $sql[] = "'end' as theend";
        $sql[] = "FROM";
        $sql[] = "auction.auctionitems";
        $sql[] = "WHERE";
        $sql[] = "AuctionYear=" . $_SESSION['auctionyear'];
        $sql[] = "AND AuctionItemNumber='" . $AuctionItemNumber . "'"; 
        $sql[] = "ORDER BY CAST(AuctionItemNumber AS unsigned)" . ";";

        $results = mysqli_query($dbconn, implode(" ",$sql));

        if($results) 
        {

            while($x = mysqli_fetch_assoc($results)) 
            {
                foreach($x as $field=>$value) 
                {
                    $item[$field] = $value;		//populate the array from returned data
                }
            }

                //get the next item's number
                $sql_next = array();
                $sql_next[] = "SELECT";
                $sql_next[] = "AuctionItemNumber";
                $sql_next[] = "FROM";
                $sql_next[] = "auction.auctionitems";
                $sql_next[] = "WHERE";
                $sql_next[] = "AuctionYear=" . $_SESSION['auctionyear'];
                $sql_next[] = "ORDER BY CAST(AuctionItemNumber AS unsigned)" . ";";

                $results_next = mysqli_query($dbconn, implode(" ",$sql_next));
                
                $found = FALSE;

                if($results_next)
                {
                        //$x_next = mysql_fetch_array($results_next);
                    while($x_next = mysqli_fetch_array($results_next))
                    {
                        
                    
                        for($i_next=0; $i_next < (count($x_next)-1); $i_next++)
                        {
                            if($found===TRUE)
                            {
                                $item['NextNumber'] = $x_next[$i_next];
                                $found = FALSE;
                                $i_next = count($x_next);
                                break 2;
                            }
                            if($x_next[$i_next] === $item['AuctionItemNumber'])
                            {
                                $found = TRUE;
                            }
                                
                        }
                        
                    }
                }

                /*
                 * At this point all of the fields should be populated and it is ready to 
                 * populate the form fields.
                 */

        }
        else
        {
            print '<br>results from = ' . implode(" ",$sql);
        }
    }
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
                width:50%;
                padding:0 auto;
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
                width:490px;
                
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
            .itemlist tfoot tr td
            {
                font-weight:bold;
                font-size:18px;
                text-align:left;
                padding-bottom:3px;
                margin-bottom:10px;
                border-top:1px solid;
            }
            .selection
            {
                background-color:yellow;
            }
            #editform{
                text-align: left;
                width:600px;
                margin:0 auto;
                font-size:14px;
                
            }
            #editform ul {
                margin: 0 auto;
                list-style: none;
            }
            #editform ul li {
                margin-bottom:5px;
            }
            #editform ul li label {
                display: inline-block;
                width: 250px;               
            }
            #editform ul li input,
            #editform ul li textarea {
                width: 200px;
                padding-left: 10px;
                padding-top: 3px;
                padding-bottom: 2px;
                padding-right: 2px;
                text-align:center;
            }
            #editform .submitbutton {
                margin-left: 200px;
            }
        </style>
    </head>
    <body>
        <?php include_once 'header.php'; ?>
        <div id="content">
            <div>
                <h1 style="margin-bottom:2px;">Auction Items</h1>
                <a href="auctionitems.php?Item=">Enter New Item</a>
            </div>
            <?php 
            if($showform)
            {
             ?>
	            <div id="editform">
	            	<form method="post" action="auctionitems.php" >
                        <fieldset id="auctionitem">
                            <legend><?php print 'Item #' . $item['AuctionItemNumber'] ?></legend>
	            	<input type="hidden" name="AuctionItemId" value="<?php print $item['AuctionItemId']; ?>" />
	            	<input type="hidden" name="NextNumber" value="<?php print $item['NextNumber']; ?>" />
		                <ul>
		                	<li>
		                		<label for="AuctionItemNumber">Auction Item Number: </label>
		                		<input type="text" name="AuctionItemNumber" value="<?php print $item['AuctionItemNumber']; ?>" />
		                	</li>
		                	<li>
		                		<label for="AuctionItemDescription">Item Description: </label>
	                                        <br>
                                                <input type="text" name="AuctionItemDescription" style="width:350px;margin-left:100px;" value ="<?php print $item['AuctionItemDescription']; ?>" />
                                        </li>
	                                <li>
	                                    <label for="SilentAuction" style="margin-right:25px;">Silent Auction</label>
	                                    Silent <input style="width:auto;margin-right:50px;" type="radio" name="SilentAuction" value="Y" <?php if($item['SilentAuction']=='Y'){print " checked ";} ?> />
	                                    Live <input style="width:auto;"  type="radio" name="SilentAuction" value="N" <?php if($item['SilentAuction']=='N'){print " checked ";} ?> />
	                                </li>
		                	<li>
		                		<label for="WinningBidder">Winning Bidder: </label>
		                		<input type="text" name="WinningBidder" autofocus value="<?php print $item['WinningBidder']; ?>" />
		                	</li>
		                	<li>
		                		<label for="WinningBidAmount" style="margin-right:-8px;">Winning Bid Amount: </label>
		                		$<input type="text" name="WinningBidAmount" value="<?php print $item['WinningBidAmount']; ?>" />
		                	</li>
		                </ul>
	                        <div style="width:100%; padding:0 auto;text-align:center; margin-top:10px;">
		                <input type="submit" name="formaction" value="Save" />
                                <?php if($item['NextNumber'] > 0)
                                {
                                    print '<input type="submit" name="formaction" value="Save & Next" />';
                                }
                                ?>
		                <input type="reset" value="Reset" />
	                        <input type="submit" name="formaction"  value="Cancel" />
	                        </div>
	            	</form>
	            </div>
            
            <?php 
            }
            ?>
            
            <?php
            $sql = array();
            
            $typeTitle = 'Live';
            
            $auctionType=array();
            $auctionType[]='N';
            $auctionType[]='Y';
            
            foreach($auctionType as $type)
            {
                $total = 0; //resets for each category
                
            	if($type == 'Y') { $typeTitle = 'Silent'; } 
            	
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
            	
				//print '<p>' . implode(" ",$sql) . '</p>';
            	
				$results = mysqli_query($dbconn, implode(" ",$sql));
            	//$results = mysqli_query($dbconn, $sql);
            	
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
                                while ($dr = mysqli_fetch_assoc($results))
                                {
                                    if($dr['AuctionItemNumber'] == $item['AuctionItemNumber'])
                                    {
                                        $highlight = ' style="background:yellow;font-weight:bold;" ';
                                    }
                                    else
                                    {
                                        $highlight = '';
                                    }
                                    print "<tr" . $highlight . ">";
                                    print "<td " . $highlight . ">" . $dr['AuctionItemNumber'] . "</td>";
                                    print '<td class="descriptioncolumn"' . $highlight . '>' . '<a href="auctionitems.php?Item=' . $dr['AuctionItemNumber'] .'">' . $dr['AuctionItemDescription'] . '</a>' . '</td>';
                                    print "<td" . $highlight . ">" . $dr['WinningBidder'] . "</td>";
                                    print '<td class="moneycolumn"' . $highlight . '>' . '$ ' . $dr['WinningBidAmount'] . '</td>';
                                    print "</tr>";
                                    $total += $dr['WinningBidAmount'];
                                }
                            }
                            else
                            {
                                print '<tr><td colspan="4">No results found</td></tr>';
                                print '<tr><td colspan="4">' . implode(" ",$sql) . '</td></tr>';
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align:left;">Total Bid Amount</td>
                                <td class="moneycolumn">$ <?php print $total; ?></td>
                            </tr>
                        </tfoot>
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
