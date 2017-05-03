<?php  
/*
 * This class is for consolidating the SQL queries to be used into one callable
 * class, with the output being the results of mysql_query();.  
 *
 * To use this class...
 *
 * 		$data = new getData;
 * 		$data->year = $_SESSION['auctionyear'];
 * 		$data->cid = $_GET['cid'];
 * 		$results = $data.getCustomer;
 
 */
 
 require_once 'config.inc.php';
// $dbconn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('<p>Error connecting to mysql</p>');

 
 class getData
 {
 	var $auctionyear;		// auction year holder, defaults for current year
 	var $cid = 0;			// CustomerId
 	var $bnum = 0;			// BidderId
 	var $searchfor = "";	// string to search for when finding bidder
 	var $info;
        
    private $sql = array();
 	
 	private function getYear()
 	{
 		$this->year = $_SESSION['auctionyear'];	
 	}

/*
================================================================
			GET Information
================================================================
*/
 	function getBidderByNumber($dbconn)
 	{
        unset($sql);
        //gets the entire Bidder information, from the Customer and Bidder tables,
        // and returns the dataset. Based on the Bidder's Number 
	    $sql[] = "SELECT";
	    $sql[] = "BidderId,";
	    $sql[] = "BidderNumber,";
	    $sql[] = "PaymentMethod,";
	    $sql[] = "PaymentDetails,";
        $sql[] = "PaymentAdjustment,";
        $sql[] = "TotalPaid,";
	    $sql[] = "customers.CustomerId,";
	    $sql[] = "FirstName,";
	    $sql[] = "LastName,";
	    $sql[] = "Address,";
	    $sql[] = "City,";
	    $sql[] = "State,";
	    $sql[] = "Zip,";
	    $sql[] = "Phone,";
	    $sql[] = "LicenseState,";
	    $sql[] = "LicenseNumber,";
	    $sql[] = "Email,";
	    $sql[] = "Notes";
	    $sql[] = "FROM auction.customers";
	    $sql[] = "INNER JOIN";
	    $sql[] = "auction.bidder ON customers.CustomerId=bidder.CustomerId";
	    $sql[] = "AND bidder.auctionyear=" . $this->auctionyear;
	    $sql[] = "WHERE BidderNumber=" . $this->bnum;
	    $sql[] = ";";
        //return(mysql_query(implode(" ",$sql)));
        return(mysqli_query($dbconn, implode(" ",$sql)));
 	}
 	
 	function getCustomer($dbconn)
 	{
 		unset($sql);
 		//get customer information based on the customer's id
        $sql[] = "SELECT";
        $sql[] = "BidderId,";
        $sql[] = "BidderNumber,";
        $sql[] = "PaymentMethod,";
        $sql[] = "PaymentDetails,";
        $sql[] = "PaymentAdjustment,";
        $sql[] = "customers.CustomerId,";
        $sql[] = "FirstName,";
        $sql[] = "LastName,";
        $sql[] = "Address,";
        $sql[] = "City,";
        $sql[] = "State,";
        $sql[] = "Zip,";
        $sql[] = "Phone,";
        $sql[] = "LicenseState,";
        $sql[] = "LicenseNumber,";
        $sql[] = "Email,";
        $sql[] = "Notes";
        $sql[] = "FROM auction.customers";
        $sql[] = "LEFT OUTER JOIN";
        $sql[] = "auction.bidder ON customers.CustomerId=bidder.CustomerId";
        $sql[] = "AND bidder.auctionyear=" . $this->auctionyear;
        $sql[] = "WHERE customers.CustomerId=" . $this->cid . ";";
        //return(mysql_query(implode(" ",$sql)));
        return(mysqli_query($dbconn, implode(" ",$sql)));

 	}

 	function getCustomerList($dbconn)
 	{
        unset($sql);
        //$this->searchfor = mysql_escape_string($this->searchfor);
        //$this->searchfor = mysqli_escape_string($dbconn, $this->searchfor);
        //get list of customers listing for search results
        $sql[] = "SELECT";
        $sql[] = "customers.CustomerId, FirstName, LastName, Address, City, State, Zip, Email, Phone,";
        $sql[] = "BidderId, BidderNumber";
        $sql[] = "FROM auction.customers";
        $sql[] = "Left Outer Join";
        $sql[] = "auction.bidder ON auction.customers.CustomerId=auction.bidder.CustomerId";
        $sql[] = "and auction.bidder.AuctionYear=" . $this->auctionyear;
        $sql[] = "WHERE FirstName like '%" . $this->searchfor . "%'";
        $sql[] = " OR LastName like '%" . $this->searchfor . "%'";
        $sql[] = " OR Address like '%" . $this->searchfor . "%'";
        $sql[] = " OR BidderNumber like '%" . $this->searchfor . "%'";
        $sql[] = " OR Phone like '%" . $this->searchfor . "%'";
        $sql[] = " OR Email like '%" . $this->searchfor . "%'";
        $sql[] = "ORDER BY LastName, FirstName";
        //return(mysql_query(implode(" ",$sql)));
        return(mysqli_query($dbconn, implode(" ",$sql)));

 	}
 	
 	function getPurchases($dbconn)
 	{
        unset($sql);
        //get list of won auctions for user based on their BidderNumber
        $sql[] = "SELECT";
        $sql[] = "AuctionItemId,";
        $sql[] = "AuctionItemNumber,";
        $sql[] = "AuctionItemDescription,";
        $sql[] = "WinningBidder,";
        $sql[] = "WinningBidAmount,";
        $sql[] = "SilentAuction";
        $sql[] = "FROM";
        $sql[] = "auction.auctionitems";
        $sql[] = "WHERE";
        $sql[] = "AuctionYear=" . $this->auctionyear;
        $sql[] = "AND WinningBidder='" . $this->bnum . "'"; 
        $sql[] = "ORDER BY SilentAuction, CAST(AuctionItemNumber AS unsigned)" . ";";
        //return(mysql_query(implode(" ",$sql)));
        return(mysqli_query($dbconn, implode(" ",$sql)));

 	}
 	
 	function getItemsList($silent)
 	{
 		unset($sql);
 		//get list of all items
        //return(mysql_query(implode(" ",$sql)));
        return(mysqli_query($dbconn, implode(" ",$sql)));

 	}
 	
/*
================================================================
			INSERT New Records
================================================================
*/
	function insertBidderNumber($dbconn)
	{
		unset($sql1);
		// Insert a new, automatically generated Bidder Number
		$sql1[] = " INSERT INTO auction.bidder (CustomerId, BidderNumber, AuctionYear)";
        $sql1[] = " SELECT " . $this->cid . ", coalesce(max(BidderNumber)+1,1), " . $this->auctionyear;
        $sql1[] = " FROM auction.bidder";
        $sql1[] = " WHERE AuctionYear = " . $this->auctionyear . ";";
        //mysql_query(implode(" ",$sql1));
        mysqli_query($dbconn, implode(" ",$sql1));
        
	}
	function insertCustomer($info, $dbconn)
	{
		unset($sql);
		// Insert a new record into the Customer table and returns the CustomerId value
	    $sql[] = "INSERT INTO auction.customers (FirstName, LastName, Address, City, State, Zip, Phone, LicenseState, LicenseNumber, Email, Notes)";
        $sql[] = "VALUES (";
        $sql[] = "'" . $info['FirstName'] . "'" . ",";
        $sql[] = "'" . $info['LastName'] . "'" . ",";
        $sql[] = "'" . $info['Address'] . "'" . ",";
        $sql[] = "'" . $info['City'] . "'" . ",";
        $sql[] = "'" . $info['State'] . "'" . ",";
        $sql[] = "'" . $info['Zip'] . "'" . ",";
        $sql[] = "'" . $info['Phone'] . "'" . ",";
        $sql[] = "'" . $info['LicenseState'] . "'" . ",";
        $sql[] = "'" . $info['LicenseNumber'] . "'" . ",";
        $sql[] = "'" . $info['Email'] . "'" . ",";
        $sql[] = "'" . $info['Notes'] . "'" ;
        $sql[] = ");";
        //$results = mysql_query(implode(" ",$sql));
        $results = mysqli_query($dbconn, implode(" ", $sql));
        if($results)
        {
        	$this->cid = mysqli_insert_id($dbconn);
        }
        return $this->cid;
	}
	function insertAuctionItem()
	{
		
	}
/*
================================================================
			UPDATE Records
================================================================
*/
	function updateCustomer($info, $dbconn)
	{
		$sql[] = "UPDATE auction.customers SET";
        $sql[] = "FirstName= '" . $info['FirstName'] . "', ";
        $sql[] = "LastName='" . $info['LastName'] . "', ";
        $sql[] = "Address='" . $info['Address'] . "', ";
        $sql[] = "City='" . $info['City'] . "', ";
        $sql[] = "State='" . $info['State'] . "', ";
        $sql[] = "Zip='" . $info['Zip'] . "', ";
        $sql[] = "Phone='" . $info['Phone'] . "', ";
        $sql[] = "LicenseState='" . $info['LicenseState'] . "', ";
        $sql[] = "LicenseNumber='" . $info['LicenseNumber'] . "', ";
        $sql[] = "Email='" . $info['Email'] . "', ";
        $sql[] = "Notes='" . $info['Notes'] . "'";
        $sql[] = "WHERE CustomerId=" . $info['CustomerId'];
        $sql[] = ";";
        //return mysql_query(implode(" ",$sql));
        return mysqli_query( $dbconn, implode(" ",$sql));
	}
	function updateBidderNumber($info,$dbconn)
	{
		unset($sql);
		// Updates the Bidder Number for the bidder
		//TODO: Check for Bidder Number's existance beforehand
		if($this->checkBidderNumberExists($info['BidderNumber']) == FALSE)
		{
			$sql[] = "UPDATE auction.bidder SET ";
	        $sql[] = "BidderNumber = " . $info['BidderNumber'];
	        $sql[] = " WHERE BidderId = " . $info['BidderId'];
	        $sql[] = ";";
	        //mysql_query(implode(" ",$sql1));
	        mysqli_query($dbconn, implode(" ",$sql));
		}
		else
		{
			return 'Error:Bidder Number exists!';
		}
	}
	function updateAuctionItem()
	{
		
	}
	function updateBidderPayment($info,$dbconn)
	{
		unset($sql);
        $sql[] = "UPDATE auction.bidder SET";
        $sql[] = "PaymentMethod=" . $info['PaymentMethod'] . ",";
        $sql[] = "PaymentDetails='" . $info['PaymentDetails'] . "',";
        $sql[] = "PaymentAdjustment=" . $info['PaymentAdjustment'] . ",";
        $sql[] = "TotalPaid=" . $info['TotalPaid'];
        $sql[] = "WHERE BidderId=" . $info['BidderId'];
        $sql[] = ";";
        //return mysql_query(implode(" ",$sql)) or die ('Error updating payment data');
        return mysqli_query($dbconn, implode(" ",$sql)) or die ('Error updating payment data');
	}

/*
================================================================
			Check Data
================================================================
*/
	function checkBidderNumberExists($checkNumber,$dbconn)
	{
		// Check that new number is unique
		$sql_check = array();
		$sql_check[] = "SELECT BidderNumber";
		$sql_check[] = "FROM auction.bidder";
		$sql_check[] = "WHERE BidderNumber=" . $checkNumber . ";";
		//$check_result = mysql_query(implode(" ",$sql_check));
		$check_result = mysqli_query($dbconn, implode(" ",$sql_check));
		$check_return = mysqli_fetch_array($check_result);
		if ($checkNumber == $check_return[0])
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
 	
 }
/*
$query_auctionyear = $_SESSION['auctionyear'];

function itemlist_select($auctiontype)
{
	$ilsel = array();
	$ilsel[] = "SELECT ";
    $ilsel[] = "AuctionItemId,";
    $ilsel[] = "AuctionItemNumber,";
    $ilsel[] = "AuctionItemDescription,";
    $ilsel[] = "WinningBidder,";
    $ilsel[] = "WinningBidAmount";
    $ilsel[] = "FROM auction.auctionitems";
    $ilsel[] = "WHERE AuctionYear=" . $query_auctionyear;
    $ilsel[] = "AND SilentAuction='" . $auctiontype . "'";
    $ilsel[] = "ORDER BY CAST(AuctionItemNumber AS unsigned)";
    $ilsel[] = ";";	
    return implode(" ",$ilsel);
}

function biddersearch_select($searchvar)
{
	$bsSEL = array();
    $bsSEL[] = "SELECT";
    $bsSEL[] = "customers.CustomerId, FirstName, LastName, Address, City, State, Zip, Email, Phone,";
    $bsSEL[] = "BidderId, BidderNumber";
    $bsSEL[] = "FROM auction.customers";
    $bsSEL[] = "Left Outer Join";
    $bsSEL[] = "auction.bidder ON auction.customers.CustomerId=auction.bidder.CustomerId";
    $bsSEL[] = "and auction.bidder.AuctionYear=" . $query_auctionyear;
    $bsSEL[] = "WHERE FirstName like '%" . $searchvar . "%'";
    $bsSEL[] = " OR LastName like '%" . $searchvar . "%'";	
    return implode(" ",$bsSEL);
}

function bidder_select($customerid)
{
	$bSEL = array();
	$bSEL[] = "SELECT";
	$bSEL[] = "BidderId,";
	$bSEL[] = "BidderNumber,";
	$bSEL[] = "customers.CustomerId,";
	$bSEL[] = "FirstName,";
	$bSEL[] = "LastName,";
	$bSEL[] = "Address,";
	$bSEL[] = "City,";
	$bSEL[] = "State,";
	$bSEL[] = "Zip,";
	$bSEL[] = "Phone,";
	$bSEL[] = "LicenseState,";
	$bSEL[] = "LicenseNumber,";
	$bSEL[] = "Email,";
	$bSEL[] = "Notes";
	$bSEL[] = "FROM auction.customers";
	$bSEL[] = "LEFT OUTER JOIN";
	$bSEL[] = "auction.bidder ON customers.CustomerId=bidder.CustomerId";
	$bSEL[] = "AND bidder.auctionyear=" . $_SESSION['auctionyear'];
	$bSEL[] = "WHERE customers.CustomerId=" . $customerid . ";";	
	return implode(" ",$bSEL);
}

function bidder_insert()
{
	
}

function bidder_update()
{
	
}
*/
?>
