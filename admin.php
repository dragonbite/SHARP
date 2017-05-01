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
        <div id="contentxS">
        	<p>This site will be used in the future for setting up auction-specific information such as </p>
        	<p>the orginazation's name, the date, the year and more.</p>
                <frameset id="login">
                    <legend>For managing logins</legend>
                    <ul>
                        <li>
                            <a href="login.php">Log Out</a>
                        </li>
                    </ul>
                    
                </frameset>
                
                
        </div>
    </body>
</html>