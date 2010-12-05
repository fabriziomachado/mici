function get_domain(hostname)
{
	var host = hostname.split('.');
	var dom = '';
	
	if(host.length == 3)
	{
		dom = host[1]+'.'+host[2];
	}
	else if(host.length == 2)
	{
		dom = host[0]+'.'+host[1];
	}
	else if(host.length == 1)
	{
		dom = host[0];
	}
	
	if(dom != '')
	{
		return dom.toString();
	}
	else
	{
		return 'localhost';	
	}
}

function new_window(mypage, myname, w, h, scroll)
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	var winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',toolbar=no,status=no,resizable=yes,menubar=no,location=no';
	fb_win = window.open(mypage, myname, winprops);
}

function filebrowser(field_name, filter)
{
	url = media_url+'browser/js/tiny_mce/filebrowser/index.php?field_name='+field_name+'&filter='+filter+'&assets_url='+assets_url+'&assets_abs_path='+assets_abs_path;
	new_window(url, 'filebrowser', 950, 650, 'no');	
}