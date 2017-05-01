

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
<?php
    print '<h1>SESSION variables</h1>';
foreach ($_SESSION as $key=>$value)
{
    print '<br>' . $key . ' = ' . $value;
}
?>        
    </body>
</html>
