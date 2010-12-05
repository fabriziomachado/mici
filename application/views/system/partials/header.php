<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        
        <title>System Admin</title>
        
        <link rel="stylesheet" href="<?PHP echo $media_url;?>system/css/reset.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?PHP echo $media_url;?>system/css/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?PHP echo $media_url;?>system/css/invalid.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?PHP echo $media_url;?>system/css/dt/data_table.css" type="text/css" media="all" />
        
        <link rel="apple-touch-icon-precomposed" href="<?PHP echo $media_url; ?>system/images/icon.png" />
        <link rel="apple-touch-startup-image" href="<?PHP echo $media_url; ?>system/images/startup.png" />
        
        <!--[if lte IE 7]>
        <link rel="stylesheet" href="<?PHP echo $media_url;?>system/css/ie.css" type="text/css" media="screen" />
        <![endif]-->
        
        <script type="text/javascript">var media_url = '<?PHP echo $media_url;?>';</script>
<?PHP
	/* ----------------------------------------------------------------
		Adding conditional logic for the Asset Generator controller
		because it uses the latest version of jQuery + a custom
		jQuery UI build for progress bar and buttons.
	   ---------------------------------------------------------------- */
?>
<?PHP if($this->uri->segment(2) === 'asset_generator'): ?>
		<link rel="stylesheet" href="<?PHP echo $media_url;?>asset_generator/css/black-tie/jquery-ui-1.8.4.custom.css" type="text/css" media="screen" />
        <script type="text/javascript" src="<?PHP echo $media_url;?>browser/js/jquery/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="<?PHP echo $media_url; ?>browser/js/ba-debug.min.js"></script>
		<script type="text/javascript" src="<?PHP echo $media_url; ?>browser/js/jquery.ba-dotimeout.min.js"></script>
		<script type="text/javascript" src="<?PHP echo $media_url; ?>asset_generator/js/jquery-ui-1.8.4.custom.min.js"></script>
<?PHP else: ?>
<?PHP
	/* ----------------------------------------------------------------
		This is the default JavaScript jQuery load for the rest
		of the System controllers.
	   ---------------------------------------------------------------- */
?>
        <script type="text/javascript" src="<?PHP echo $media_url;?>system/js/jquery-1.3.2.min.js"></script>
<?PHP endif; ?>
        
    </head>
    
    <body>
    	<div id="body-wrapper">
        	
            <div id="pageloading">loading &nbsp;<img src="<?PHP echo $media_url;?>system/images/pageloading.gif" alt="Page Loading" style="vertical-align: middle;" /></div>
			
			<?PHP echo $sidebar; ?>
            
            <div id="main-content">
        		<noscript>
        			<div class="notification error png_bg">
        				<div>Javascript is disabled or is not supported by your browser. Please upgrade your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser" target="_blank">enable</a> Javascript to navigate the interface properly.</div>
        			</div>
        		</noscript>