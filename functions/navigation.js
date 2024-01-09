// JavaScript Document

/*********************************************************************************************************/
// Menu Dropdowns
// --------
function executeReminder() {
    var xmlhttp = new XMLHttpRequest();
  
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === XMLHttpRequest.DONE) {
            if (xmlhttp.status === 200) {
                // Handle the response here if needed
            }
        }
    };
  
    xmlhttp.open("POST", "../inc/navigation.php", true);
    xmlhttp.send();
}

// Call the function initially
executeReminder();

// Set interval to execute the function every 30 seconds
setInterval(executeReminder, 30000); // 30 seconds

//---------

initNav = function() {
		var navRoot = document.getElementById("primary-navigation");
		var lis = navRoot.getElementsByTagName("li");
		for (var i=0; i<lis.length; i++)
		{
			var drops = lis[i].getElementsByTagName("ul");
			if (drops.length)
			{
				lis[i].onmouseover = function()
				{
					this.className += " hover";
				}
				lis[i].onmouseout = function()
				{
					this.className = this.className.replace("hover", "");
				}
			}
		}
		var navRoot = document.getElementById("language");
		if (navRoot != null)  /*for vmworld pages*/
		{
		var lis = navRoot.getElementsByTagName("li");
		for (var i=0; i<lis.length; i++)
		{
			var drops = lis[i].getElementsByTagName("ul");
			if (drops.length)
			{
				lis[i].onmouseover = function()
				{
					this.className += " hover";
				}
				lis[i].onmouseout = function()
				{
					this.className = this.className.replace("hover", "");
				}
			}
		}
		}
}

if (window.addEventListener){
	window.addEventListener("load", initNav, false);
}
else if (window.attachEvent){
	window.attachEvent("onload", initNav);
}

/*********************************************************************************************************/

