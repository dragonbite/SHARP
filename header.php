<?php

?>
<html>
    <head>
        <link href="auction.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="auction.js"></script>
        <script type="text/javascript" >
        function pageLoad()
        {
            document.getElementById('jDate').innerHTML = GetDate();
            setInterval(function() { document.getElementById('jTime').innerHTML = GetTime() }, 500);
        }
        </script>
        <style>
            #errormessage 
            {
                color:red;
                font-weight:bold;
                text-align:center;
            }
        </style>
    </head>
</html>
<body onLoad="javascript:pageLoad();">

    <div id="navigationHeader">
        <img src="./images/church_header.JPG" alt="Middlebury Congregational Church"/> 
	    <div id="menubar">
	    <nav>
            <?php
                $myPages = array("Home"=>"index.php", 
                    "Bidders"=>"biddersearch.php", 
                    "Auction Items"=>"auctionitems.php", 
                    "Cash Out"=>"cashout.php",
                    "Reports"=>"reports.php",
                    "Admin"=>"admin.php"
                );
                foreach($myPages as $name=>$link)
                {
                    print '<a href="' . $link . '">' . $name . '</a>';
                }
            ?> 
        </nav>
	    <span class="navigationButtons" style="float:right;font-size:14px;">
            <br />
            <label id="jDate"></label>
            <br />
            <label id="jTime" style="font-size:24px; font-weight:bold;"></label>
        </span>
        </div>
    </div>
    <?php if(strlen($error_message) >0) {print '<div id="errormessage">' . $error_message . '</div>'; }?>
