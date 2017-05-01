<link rel="stylesheet" type="text/css" href="lib/auction.css" media="projection, screen" />
<script type="text/javascript" src="lib/auction.js"></script>
<script type="text/javascript" >
	function windClock()
    {
    	document.getElementById('jDate').innerHTML = GetDate();
        setInterval(function() { document.getElementById('jTime').innerHTML = GetTime() }, 500);
    }
</script>