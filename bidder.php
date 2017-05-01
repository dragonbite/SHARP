<?php
//session_start();
require_once 'auth.php';

/*
 * To add a new field, first add to database table, then add the same name into both 
 * of these arrays. That will automatically generate a text input.
 */
 
 /*
 Global variables:
 -----------------
 	$data is of the getData class 
 	$data->auctionyear is already set
 */


// TODO: code adding bidder number to person
// TODO: code inserting a new person
$cid = 0;
$cu = array(
    "CustomerId" => 0,
    "BidderId" => 0,
    "BidderNumber" => "",
    "FirstName" => "",
    "LastName" => "",
    "Address" => "",
    "City" => "",
    "State" => "",
    "Zip" => "",
    "Phone" => "",
    "LicenseState" => "",
    "LicenseNumber" => "",
    "Email" => "",
    "Notes" => ""
    );

if ($_POST)
{
    /*
     * There are 4 different combinations to be aware of
     * No CustomerId = new customer
     * CustomerId and no BidderId = save Customer -or- hit button to generate BidderId
     * CustomerId and BidderId = edit customer and/or bidder number
     */
    
    /*
     * Overall view:
     *  CustomerId
     *      Bidder Id
     *          -> UPDATE bidder 
     *      No Bidder Id 
     *          "get bidder id"
     *              -> INSERT bidder
     *      -> UPDATE customer (in all instances)
     *  No Customer Id
     *      -> INSERT customer
     */
    
    // 
    $pd = array();
    foreach($_POST as $k=>$v)
    {
        $pd[$k] = mysql_escape_string($v);
        //print '<br>' . $k . '=' . $v;
    }
    
    //make BidderId if it is otherwise missing
    if($pd['BidderId'] == "")
    {
        $pd['BidderId'] = 0;
    }
    //print '<br>' . 'BidderId' . '=' . $pd['BidderId'];
    
    // enter or update the information
    $sql = array();
    $sql1 = array();
    
    
    if(isset($pd['CustomerId'])) // && is_numeric($_POST['CustomerId']))
    {
    	/*
        ===================================================================
        			EXISTING BIDDER 
        ===================================================================
        */
        
        if($pd['CustomerId'] != 0)                // run the query
        {
            $cid = $pd['CustomerId'];
            $data->cid = $pd['CustomerId'];
            
            /*
            ===================================================================
            			UPDATE BIDDER INFORMATION 
            ===================================================================
            */
            
            if($pd['Clicked'] == 'Get Bidder Number')
            {
            	/*
            	This function enters a new entry into the [bidder] table, then redirects
            	back to this page so the user can inform the customer what his or her 
            	Bidder Number is.
            	*/
//                $sql1[] = " INSERT INTO auction.bidder (CustomerId, BidderNumber, AuctionYear)";
//                $sql1[] = " SELECT " . $cid . ", coalesce(max(BidderNumber)+1,1), " . $_SESSION['auctionyear'];
//                $sql1[] = " FROM auction.bidder";
//                $sql1[] = " WHERE AuctionYear = " . $_SESSION['auctionyear'] . ";";
//                mysql_query(implode(" ",$sql1));
				
				$data->insertBidderNumber();
            } 
            elseif ($pd['BidderId'] > 0)
            {
            	/*
            	If the Bidder Number already exists, then update teh [bidder] table with
            	the entered number.  This also occurs when it is the customer information
            	being updated and not the Bidder Number.
            	*/
            	
            	// TODO: check that the bidder number does not already exist before updating
            	
//                $sql1[] = "UPDATE auction.bidder SET ";
//                $sql1[] = "BidderNumber = " . $pd['BidderNumber'];
//                $sql1[] = " WHERE BidderId = " . $pd['BidderId'];
//                $sql1[] = ";";
//                mysql_query(implode(" ",$sql1));
                
                $message = $data->updateBidderNumber($pd);
                if(strlen($message) > 0)
                {
                	// there was an error
                }
            }
            
            /*
            ===================================================================
            			UPDATE CUSTOMER INFORMATION 
            ===================================================================
            */
            
//            $sql[] = "UPDATE auction.customers SET";
//            $sql[] = "FirstName= '" . $pd['FirstName'] . "', ";
//            $sql[] = "LastName='" . $pd['LastName'] . "', ";
//            $sql[] = "Address='" . $pd['Address'] . "', ";
//            $sql[] = "City='" . $pd['City'] . "', ";
//            $sql[] = "State='" . $pd['State'] . "', ";
//            $sql[] = "Zip='" . $pd['Zip'] . "', ";
//            $sql[] = "Phone='" . $pd['Phone'] . "', ";
//            $sql[] = "LicenseState='" . $pd['LicenseState'] . "', ";
//            $sql[] = "LicenseNumber='" . $pd['LicenseNumber'] . "', ";
//            $sql[] = "Email='" . $pd['Email'] . "', ";
//            $sql[] = "Notes='" . $pd['Notes'] . "'";
//            $sql[] = "WHERE CustomerId=" . $pd['CustomerId'];
//            $sql[] = ";";
//            $results = mysql_query(implode(" ",$sql));
            
            $results = $data->updateCustomer($pd);
            
            if($results)
            {
                $error_message = "Successfully updated.";
            }

        } // end $pd['CustomerId'] != 0
        
        
        /*
        ===================================================================
        			NEW CUSTOMER
        ===================================================================
        */
    	else    
        {
            
//            $sql[] = "INSERT INTO auction.customers (FirstName, LastName, Address, City, State, Zip, Phone, LicenseState, LicenseNumber, Email, Notes)";
//            $sql[] = "VALUES (";
//            $sql[] = "'" . $pd['FirstName'] . "'" . ",";
//            $sql[] = "'" . $pd['LastName'] . "'" . ",";
//            $sql[] = "'" . $pd['Address'] . "'" . ",";
//            $sql[] = "'" . $pd['City'] . "'" . ",";
//            $sql[] = "'" . $pd['State'] . "'" . ",";
//            $sql[] = "'" . $pd['Zip'] . "'" . ",";
//            $sql[] = "'" . $pd['Phone'] . "'" . ",";
//            $sql[] = "'" . $pd['LicenseState'] . "'" . ",";
//            $sql[] = "'" . $pd['LicenseNumber'] . "'" . ",";
//            $sql[] = "'" . $pd['Email'] . "'" . ",";
//            $sql[] = "'" . $pd['Notes'] . "'" ;
//            $sql[] = ");";
//            
//            //print implode(" ",$sql);
//   
//            //TODO: get the last record created ID to set as teh customer id
//            $results = mysql_query(implode(" ",$sql));
//            $cid = mysql_insert_id();
            
            //$data = new getData();
            //$data->auctionyear = $_SESSION['auctionyear'];
            
            $cid = $data->insertCustomer($pd);
            
            //if(!$results)
            if($cid > 0)
            {
                $error_message = "Successfully created.";
            }
            else
            {
                $error_message = "There was an error running your query.";
            }
        }

        
    }
//    if(strlen($error_message) == 0)
//    {
        header("Location:bidder.php?cid=" . $cid . "&message=" . $error_message);
//    }
}

/*
 * ================================================
 *              GET CUSTOMER DATA 
 * ================================================
 */

$placeholder = array(
    "BidderNumber" => "Click button to get number",
    "CustomerId" => 0,
    "BidderId" => 0,
    "FirstName" => "e.g. 'John'",
    "LastName" => "e.g. 'Smith'",
    "Address" => "e.g. '1242 Whittemore Rd.'",
    "City" => "e.g. 'Middlebury'",
    "State" => "e.g. 'CT'",
    "Zip" => "e.g. '06762'",
    "Phone" => "e.g. '2037582671'",
    "LicenseState" => "e.g. 'CT'",
    "LicenseNumber" => "e.g. '123456789'",
    "Email" => "e.g. 'coolguy@gmail.com'",
    "Notes" => ""
    ); 
$output = array();
$frame_legend = "New Customer";

if($_GET)
{
    
    $get = array();

    if(is_numeric($_GET['cid']))
    {
        $cid = $_GET['cid'];

        //$data = new getData;
        //$data->auctionyear = $_SESSION['auctionyear'];
        $data->cid = $_GET['cid'];
        $getresult = $data->getCustomer();

        if ($getresult)
        {           
            $cust = mysql_fetch_assoc($getresult);
            foreach($cust as $key=>$value)
            {
                $cu[$key] = $value;
            }
            $frame_legend = $cu['FirstName'] . ' ' . $cu['LastName'];
            if(strlen($frame_legend)==0)
            {
            	$frame_legend = "New Bidder";
            }
           if($cu['BidderNumber'] > 0)
           {
               $data->bnum = $cu['BidderNumber'];
               $auctioninformation = $data->getPurchases();
           }
        }
        else
        {
            //print implode(" ",$get);
            print 'no information returned';
        }
    }
    elseif (strtolower($_GET['cid'])=="new")
    {
        // everything is blank, ready for a new person
    }
    if(isset($_GET['message']))
    {
        $error_message = $_GET['message'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php print $frame_legend; ?></title>
        <style>
            #customer,
            #auctioninformation
            {
                text-align: left;
                float:left;
            }
            #customer ul {
                margin: 0 auto;
                list-style: none;
            }
            #customer ul li label {
                display: inline-block;
                width: 150px;               
            }
            #customer ul li input,
            #customer ul li textarea {
                width: 200px;
                padding-left: 10px;
                padding-top: 3px;
                padding-bottom: 2px;
                padding-right: 2px;
            }
            #customer .submitbutton {
                margin-left: 200px;
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
    	    <form method="post" action="bidder.php">
                
                <fieldset id="customer" style="border:2px double red;">
                    <legend><?php print $frame_legend; ?></legend>
                    
                    <p>&nbsp;</p> 
                    
                    <input type="hidden" name="CustomerId" value="<?php print $cu['CustomerId']; ?>">
                    <input type="hidden" name="BidderId" value="<?php print $cu['BidderId']; ?>">
                    <div style="width: 600px; margin: 0 auto; ">
                        <?php 
                        if($cid > 0)
                        {
                            if($cu['BidderId'] > 0)
                            {
                            ?>
                                                   
                        <ul>
                            <li>
                                <label for="BidderNumber">Bidder Number</label>
                                <input type="text" name="BidderNumber" value="<?php print $cu['BidderNumber']; ?>" placeholder="<?php print $placeholder['BidderNumber']; ?>" style="text-align:center;font-size:18px; font-weight:bold;color:#7FB4FF;" >
                            </li>
                        </ul>                      
                            <?php 
                            }
                        } 
                        ?>
                        <p><i>Please enter as much information as possible below.</i></p>
                        
                        <ul>
                            <li>
                                <label for="FirstName">First name:</label> 
                                <input type="text" name="FirstName" value="<?php print $cu['FirstName'];?>" autofocus placeholder="e.g. 'John'"/>
                            </li>
                            <li>
                                <label for=" LastName">Last name:</label>
                                <input type="text" name="LastName" value="<?php print $cu['LastName'];?>" placeholder="e.g. 'Smith'"/>
                            </li>
                            <li>
                                <label for="Address">Address:</label>
                                <input type="text" name="Address" value="<?php print $cu['Address'];?>" placeholder="e.g. '1234 Main Street'"/>
                            </li>
                            <li>
                                <label for="City">City:</label>
                                <input type="text" name="City" value="<?php print $cu['City'];?>" placeholder="e.g. 'Middlebury'"/>
                            </li>
                            <li>
                                <label for="State">State:</label>
                                <input type="text" name="State" value="<?php print $cu['State'];?>" placeholder="e.g. 'CT'"/>
                            </li>
                            <li>
                                <label for="Zip">Zip code:</label>
                                <input type="text" name="Zip" value="<?php print $cu['Zip'];?>" placeholder=" e.g. '06762'"/>
                            </li>
                            <li>
                                <label for="Phone">Phone</label>
                                <input type="text" name="Phone" value="<?php print $cu['Phone'];?>" placeholder="e.g. '2035981234'"/>
                            </li>
                            <li>
                                <label for="Email">Email</label>
                                <input type="text" name="Email" value="<?php print $cu['Email'];?>" placeholder="e.g. 'somebody@gmail.com'"/>
                            </li>
                            <li>
                                <label for="LicenseState">License state:</label>
                                <input type="text" name="LicenseState" value="<?php print $cu['LicenseState'];?>" placeholder="e.g. 'CT'"/>
                            </li>
                            <li>
                                <label for="LicenseNumber">License Number</label>
                                <input type="text" name="LicenseNumber" value="<?php print $cu['LicenseNumber'];?>" placeholder="e.g. '1233445567'"/>
                            </li>
                            <li>
                                <label for="Notes">Notes:</label>
                                <textarea rows="3" name="Notes"><?php print $cu['Notes']; ?></textarea>
                            </li>
                        </ul>
                        <br>
                        <div class="submitbutton">
                        <input type="submit" value="Save <?php print $frame_legend; ?>" name="Clicked" />
                        <?php
                        if($cu['CustomerId'] > 0)
                        {
                            if(!$cu['BidderId'])
                            {
                                ?>
                        &nbsp; <input type="submit" value="Get Bidder Number" name="Clicked" />
                        <?php
                            }
                        }
                         ?>
                        </div>
                    </div>
                    
                </fieldset>
                <?php
                //if($auctioninformation)
                if($cu['BidderNumber'] > 0)
                { ?>
                <fieldset id="auctioninformation">
                            <legend>Auction items won so far</legend>
                            
                            <table  cellspacing="0" cellpadding="1">
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
                                    if(mysql_num_rows($auctioninformation) == 0)
                                    {
                                        ?>
                                    <tr>
                                        <td colspan="4">No Winning Bids Yet</td>
                                    </tr>
                                        <?php
                                    }
                                    else
                                    {
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
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr style="font-weight:bold;border:1px solid #eeeeee;">
                                        <td></td>
                                        <td style="text-align:left;">Totals:</td>
                                        <td style="text-align:right;">$<?php print $totals; ?></td>
                                        <td></td>
                                    </tr>
                                        
                                </tfoot>
                            </table>
                        </fieldset>
                <?php
                }
                ?>
            </form>
        </div>
    </body>
</html>
