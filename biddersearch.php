<?php
    //session_start();
    require_once 'auth.php';
    
    $output = array();
    $searchvar = "";
    $sql = array();

    if(isset($_GET['searchfor'])) 
    {
        //$searchvar = mysql_escape_string($_GET["searchfor"]);
        $searchvar = mysqli_escape_string($dbconn, $_GET["searchfor"]);
        $output[] = '<a href="bidder.php?cid=new">Add New Customer</a>';
    }

    /*
     * Use the getData class of /lib/query.sql class.
     */

//    $data = new getData;
//    $data->auctionyear = $_SESSION['auctionyear'];
    
    //$data->searchfor = mysql_escape_string($searchvar);
    $data->searchfor = mysqli_escape_string($dbconn, $searchvar);
    
    $results = $data->getCustomerList($dbconn);

    //$sql = itemlist_select($searchvar);
    //$result = mysql_query($sql);

    if($results) 
    {
        $output[] = '<table id="biddersearch">';
        $output[] = '<thead>';
        $output[] = '<td>Bidder#</td>';
        $output[] = '<td>Name</td>';
        $output[] = '<td>Address</td>';
        $output[] = '<td>Phone</td>';
        $output[] = '<td>Email</td>';
        $output[] = '</thead>';
        
        $output[] = '<tbody>';
        while($row = mysqli_fetch_assoc($results))	//reference by assoc array key
        {
            
            $output[] = '<tr>';
            //$output[] = '<td><a href="bidder.php?customerid=' . $row[0] . '">' . $row[1] . ' ' . $row[2] . '</a></td>';
            if(is_null($row['BidderNumber']))
            {
                $output[] = '<td><a href="bidder.php?cid=' . $row['CustomerId'] . '">New Bidder</a></td>';
            }
            else
            {
                $output[] = '<td><a href="bidder.php?cid=' . $row['CustomerId'] . '">' . $row['BidderNumber'] . '</a></td>';
            }
            $output[] = '<td><a href="bidder.php?cid=' . $row['CustomerId'] . '">' . $row['FirstName'] . ' ' . $row['LastName'] . '</a></td>';
            $output[] = '<td>' . $row['Address'] . ', ' . $row['City'] . ' ' . $row['State'] . ' ' . $row['Zip'] . '</td>';
            $output[] = '<td>' . $row['Phone'] . '</td>';
            $output[] = '<td>' . $row['Email'] . '</td>';
            $output[] = '</tr>';
        } 
        $output[] = '</tbody>';
        $output[] = '</table>';
        //$output[] = '<a href="bidder.php?cid=new">Add New Customer</a>';
    }
    else
    {
        $output[] = '<div id="noRecords">No matching records found.<br>Try again with a smaller search.</div>';
    }
    
    if($_GET)
    {
        $output[] = '<a href="bidder.php?cid=new">Add New Customer</a>';
    }

    
?>

<html>
<head>
	<?php include_once 'head.inc.php'; ?>
    <style>
        #biddersearch
        {
            font-size: 14px;
            width: 100%;
        }
        #biddersearch thead 
        {
            font-weight: bold;
            text-align: center;
        }
        #biddersearch tbody
        {
            white-space: nowrap;
        }
        #biddersearch tbody tr:nth-child(even) {background: #EEE}
        #biddersearch tbody tr:nth-child(odd) {background: #FFF}
    </style>
</head>
<body>
	<?php include_once 'header.php'; ?>
    <div id="content">
    	<form method="get" action="biddersearch.php">
         <fieldset id="search">
                <legend>Search for People</legend>
                <input type="text" id="searchfor" name="searchfor" placeholder="Search name, add, ph# or email ..." autofocus="true"/>
                <input type="submit"  />
            </fieldset>
        </form>
        <?php if($output) //if(isset($_GET['searchfor']))
        { 
            
            ?>
        <fieldset id="results">
            
            <?php 
            if(strlen($searchvar) > 0)
            {
            	print "<legend>Search results for '" . $searchvar ."'</legend>";
            }  
            ?>
            
            <?php print implode("\r\n",$output);  ?>
            
        </fieldset>
        <?php 
        } ?>
    </div>
</body>
</html>
