<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <title>Website Administrator</title>

        <link rel="stylesheet" href="<?PHP echo $media_url;?>admin/css/reset.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?PHP echo $media_url;?>admin/css/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?PHP echo $media_url;?>admin/css/dt/data_table.css" type="text/css" media="all" />
        <link rel="stylesheet" href="<?PHP echo $media_url;?>admin/css/invalid.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?PHP echo $media_url;?>admin/css/jquery-ui-1.8.4.custom.css" type="text/css" media="screen" />

        <!--[if lte IE 7]>
        <link rel="stylesheet" href="<?PHP echo $media_url;?>admin/css/ie.css" type="text/css" media="screen" />
        <![endif]-->

        <script type="text/javascript">var media_url = '<?PHP echo $media_url;?>';</script>
        <script type="text/javascript" src="<?PHP echo $media_url;?>admin/js/jquery-1.4.2.min.js"></script>
    </head>
    <body>
    	<div id="body-wrapper">
            <div id="pageloading">loading &nbsp;<img src="<?PHP echo $media_url;?>admin/images/pageloading.gif" style="vertical-align: middle;" /></div>
            <?PHP echo $sidebar; ?>
            <div id="main-content">
                <noscript>
                    <div class="notification error png_bg">
                        <div>Javascript is disabled or is not supported by your browser. Please upgrade your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser" target="_blank">enable</a> Javascript to navigate the interface properly.</div>
                    </div>
                </noscript>