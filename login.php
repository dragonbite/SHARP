<?php
$error_message="";

if($_POST)
{

    session_start();
    $_SESSION['token'] = '';
    $login=FALSE;
    
    require_once 'lib/config.inc.php';  //get connection information
    
    

    $entered = $_POST;
    foreach($entered as $key=>$value)
    {
        // replace value with scrubbed value
         $entered[$key] = mysqli_escape_string($dbconn, $value);
        //$entered[$key] = $conni->real_escape_string($value);
    }
    
    //TODO: remove the "-2" from the current year so use the current year
    $sql = "SELECT token, Year(CURDATE()) as 'auctionyear' FROM auction.security WHERE Password='" . $entered['password'] . "' AND UserId='" . $entered['username'] ."';";
    //$sql = "SELECT token, Year(CURDATE()) - 1 as 'auctionyear' FROM auction.security WHERE Password='" . $entered['password'] . "' AND UserId='" . $entered['username'] ."';";
        
    //$result = mysql_query($sql);

    //$conni->query($sql);
    
    //if(mysqli_num_rows($result) > 0 )
    
    $result = mysqli_query($dbconn, $sql);
    
    $row_cnt = $result->num_rows;
    
    if ( $row_cnt > 0 )
    {
        $credentials = mysqli_fetch_assoc($result);
        foreach($credentials as $key=>$value)
        {
            $_SESSION[$key]=$value;
        }
    }
    else
    {
        $error_message = "Failed to log in. Please try again or ask for assistance.";
    }    
    
    
    if($_SESSION['token'] == $checkMyToken)
    {
        header("Location:index.php");
        //print 'Tokens match';
        foreach ($_SESSION as $k=>$v)
        {
            //print '<br>Session-' . $k . '=' . $v;
        }
    }
    else
    {
        //$error_message = 'Failed to log in. Please go to the <a href="login.php">loing page</a> and try again.';
        $error_message = "Failed to log in. Please try again or ask for assistance.";
        //exit;
    }

}


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Log in to auction site</title>
        <style>
            #loginform
            {
                width: 300px;
                margin: 0 auto;
                border-color: #A1C8FF;
                border-style: double;
            }
            #loginform #loginformlegend
            {
                font-size: 14px;
                color: #A1C8FF;
                font-weight: bold;
            }
            #loginform ul li 
            {
                list-style: none;
                line-height: 40px;
            }
            #loginform ul li label
            {
                width: 80px;
                margin: 3px;
                padding: 2px;
                font-weight: bold;
            }
            #loginform ul li input
            {
                width: 100px;
                padding-right: 30px;
            }
        </style>
    </head>
    <body>
        <?php include_once "header.php"; ?>
        <form action="login.php" method="post">
            <fieldset id="loginform">
                <legend id="loginformlegend">Please log in</legend>
                <ul>
                    <li><label>Username : </label> <input type="text" id="username" name="username" required autofocus="true"></li>
                    <li><label>Password : </label> <input type="password" id="password" name="password" required></li>
                </ul>
                <input type="submit"></input>
            </fieldset>
        </form>
    </body>
</html>
