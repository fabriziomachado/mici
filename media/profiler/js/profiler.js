var profilercss = document.createElement("link");
profilercss.setAttribute("rel", "stylesheet");
profilercss.setAttribute("type", "text/css");
profilercss.setAttribute("href", media_url+"profiler/css/profiler.css");
document.getElementsByTagName("head")[0].appendChild(profilercss);

var PROFILER_DETAILS = false;
var PROFILER_HEIGHT = "short";
addEvent(window, 'load', loadCSS);

function changeTab(tab)
{
	if ( ! PROFILER_DETAILS)
	{
		toggleDetails();
	}
	var profiler_helper = document.getElementById('profiler_helper');
	hideAllTabs();
	addClassName(profiler_helper, tab, true);
}
function hideAllTabs()
{
	var profiler_helper = document.getElementById('profiler_helper');
	removeClassName(profiler_helper, 'console');
	removeClassName(profiler_helper, 'speed');
	removeClassName(profiler_helper, 'queries');
	removeClassName(profiler_helper, 'memory');
	removeClassName(profiler_helper, 'files');
}
function toggleDetails()
{
	var container = document.getElementById('profiler-container');
	if(PROFILER_DETAILS)
	{
		addClassName(container, 'hideDetails', true);
		PROFILER_DETAILS = false;
	} 
	else
	{
		removeClassName(container, 'hideDetails');
		PROFILER_DETAILS = true;
	}
}
function toggleHeight()
{
	var container = document.getElementById('profiler-container');
	if(PROFILER_HEIGHT == "short")
	{
		addClassName(container, 'tallDetails', true);
		PROFILER_HEIGHT = "tall";
	}
	else{
		removeClassName(container, 'tallDetails');
		PROFILER_HEIGHT = "short";
	}
}
function loadCSS()
{
	setTimeout(function(){document.getElementById("profiler-container").style.display = "block"}, 10);
}
function addClassName(objElement, strClass, blnMayAlreadyExist)
{
	if ( objElement.className )
	{
		var arrList = objElement.className.split(' ');
		if ( blnMayAlreadyExist )
		{
			var strClassUpper = strClass.toUpperCase();
			for ( var i = 0; i < arrList.length; i++ )
			{
				if ( arrList[i].toUpperCase() == strClassUpper )
				{
					arrList.splice(i, 1);
					i--;
				}
			}
		}
		arrList[arrList.length] = strClass;
		objElement.className = arrList.join(' ');
	}
	else
	{  
		objElement.className = strClass;
	}
}
function removeClassName(objElement, strClass)
{
	if ( objElement.className )
	{
		var arrList = objElement.className.split(' ');
		var strClassUpper = strClass.toUpperCase();
		for ( var i = 0; i < arrList.length; i++ )
		{
			if ( arrList[i].toUpperCase() == strClassUpper )
			{
				arrList.splice(i, 1);
				i--;
			}
		}
		objElement.className = arrList.join(' ');
	}
}
function addEvent( obj, type, fn )
{
	if ( obj.attachEvent )
	{
		obj["e"+type+fn] = fn;
		obj[type+fn] = function() { obj["e"+type+fn]( window.event ) };
		obj.attachEvent( "on"+type, obj[type+fn] );
	} 
	else
	{
		obj.addEventListener( type, fn, false );
	}
}