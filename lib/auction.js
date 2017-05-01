/*
 * CLOCK FUNCTIONS
 * Assume there are 2 <div> elements; jDate and jTime
 * jDate gets populated with 
 *      document.getElementById('jDate').innerHTML = GetDate();
 * jTime gets populated with (inteval to update on schedule)
 *      setInterval(function() { document.getElementById('jTime').innerHTML = GetTime() }, 500);
 */

function GetDate() {
   var webdate = new Date()
   var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
   var day = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
   return day[webdate.getDay()] + ' | ' + month[webdate.getMonth()] + '-' + webdate.getDate() + ' | ' + webdate.getFullYear()
}

function GetTime() {
   var webtime = new Date()
   var hours = webtime.getHours()
   var minutes = webtime.getMinutes()
   var seconds = webtime.getSeconds()
   var meridian                            //AM or PM

   if (hours > 11) { meridian = 'PM' } else { meridian = 'AM' }
   if (hours > 12) { hours = hours - 12 }

   if (minutes < 10) {
	   if (minutes == 0) { minutes = '00' } else { minutes = '0' + minutes }
   }
   if (seconds < 10) {
	   if (seconds == 0) { seconds = '00' } else { seconds = '0' + seconds }
   }
   return hours + ':' + minutes + ':' + seconds + ' ' + meridian
}