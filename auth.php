<?php
    session_start();
    
    /*
    This page's purpose is to check that he user is logged in, that the session 
    is legitimate and if not, to redirect the user back to the login page.
    */
    include_once 'lib/libdatabasequeries.php';

    
    if(($_SESSION['token'] == '') || ($_SESSION['token'] != $checkMyToken))
    {
        //TODO: determine the page this is coming from and include the requesting
        // URL so it can be passed and the login page can redirect there instead of 
        // a generic page.
        header('Location:login.php');
    }
    
    //include_once 'lib/query.php';
    //include_once 'lib/libdatabasequeries.php';

	//successful login, set global variables
	
    $data = new getData;
    $data->auctionyear = $_SESSION['auctionyear'];

    function loginFailMessage ($fail_message)
    {
        /*
         * This function will put out the HTML code for displaying an error
         * message informing the user to log in to continue.
         */
        
        $msg[] = '<html>';
        $msg[] = '<head>';
        $msg[] = '  <link rel="stylesheet" type="text/css" href="auction.css" media="projectsion, screen" />';
        $msg[] = '</head>';
        $msg[] = '<body>';
        $msg[] = include_once 'header.php';
        $msg[] = '<div id="failingmessage">';
        $msg[] = '  <p>';
        if(len($fail_message)>0)
        {
            $msg[] = $fail_message;
        }
        $msg[] = '      Please return to the <a href="login.php">login page</a>, and try again.';
        $msg[] = '  </p>';
        $msg[] = '</div>';
        $msg[] = '</body>';
        $msg[] = '</html>';
        return implode("\r\n",$msg);
    }
    
    function dispalyRawReturn($result)
    {
        /*
         * Takes the connected result, to be supplied by calling page, and
         * displays all of the returned data unmodified and complete, for 
         * development purposes of seeing what is coming back to verify 
         * data is returned, and complete.
         */
          
        //start table
        $out[] = '<table border="1">';
        
        //table header row
        $field_num = mysql_num_fields($result);
        $out[] = '<tr>';
        for($i=0; $i < $field_num; $i++)
        {
            $field = $request.mysql_fetch_field($i);
            $out[] = '<td>' . $field . '</td>';
        }
        $out[] = '</tr>';
        
        //display data in table
        while($row = mysql_fetch_row($result))
        {
            $out[] = '<tr>';
            foreach($row as $cell)
                $out[] = '<td>' . $cell . '&nbsp;</td>';
            
            $out[] = '</tr>';
        }
        
        //end table
        $out[] = '</table>';
        
        //pass compiled HTML back
        return implode("\r\n",$out);
    }

?>
