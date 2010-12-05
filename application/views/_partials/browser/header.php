<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Manifest Interactive<?PHP echo $meta_title; ?></title>

        <meta http-equiv="content-language" content="en" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="imagetoolbar" content="no" />

        <meta name="author" content="Manifest Interactive" />
        <meta name="cache-control" content="public" />
        <meta name="company" content="Manifest Interactive, LLC." />
        <meta name="content-language" content="en" />
        <meta name="copyright" content="Copyright &copy; <?PHP echo date("Y"); ?> Manifest Interactive, LLC., All Rights Reserved" />
        <meta name="country" content="USA" />
        <meta name="coverage" content="worldwide" />
        <meta name="description" content="<?PHP echo $meta_description; ?>" />
        <meta name="distribution" content="global" />
        <meta name="googlebot" content="index,follow" />
        <meta name="keywords" content="<?PHP echo $meta_keywords; ?>" />
        <meta name="language" content="en" />
        <meta name="revisit-after" content="7 days" />
        <meta name="robots" content="noodp" />
        <meta name="robots" content="index,follow" />
        <?PHP if($browser['ismobiledevice']): ?>
        <meta name="viewport" content="width=1024, user-scalable=1" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <?PHP endif; ?>

        <link rel="stylesheet" type="text/css" href="<?PHP echo $media_url; ?>browser/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="<?PHP echo $media_url; ?>browser/css/inner.css" />
        <link rel="stylesheet" type="text/css" href="<?PHP echo $media_url; ?>browser/css/inner_blue.css" title="Blue" />
        <link rel="stylesheet" type="text/css" href="<?PHP echo $media_url; ?>browser/css/superfish.css" />
        <link rel="stylesheet" type="text/css" href="<?PHP echo $media_url; ?>browser/css/table.css" />
        <link rel="stylesheet" type="text/css" href="<?PHP echo $media_url; ?>browser/css/prettyPhoto.css" />
        <link rel="stylesheet" type="text/css" href="<?PHP echo $media_url; ?>browser/css/menu.css" />
        
        <link rel="alternate stylesheet" type="text/css" href="<?PHP echo $media_url; ?>browser/css/inner_green.css" title="Green" />
        <link rel="alternate stylesheet" type="text/css" href="<?PHP echo $media_url; ?>browser/css/inner_orange.css" title="Orange" />

        <link rel="shortcut icon" type="image/x-icon" href="<?PHP echo $media_url; ?>browser/img/favicon.gif" />

        <?PHP if($browser['ismobiledevice']): ?>
        <link rel="stylesheet" type="text/css" href="<?PHP echo $media_url; ?>browser/css/mobile.css" />
        <link rel="apple-touch-icon-precomposed" href="<?PHP echo $media_url; ?>browser/img/icon.png" />
        <link rel="apple-touch-startup-image" href="<?PHP echo $media_url; ?>browser/img/splash.jpg" />
        <?PHP endif; ?>

        <!--[if IE 7]>
            <link href="<?PHP echo $media_url; ?>browser/css/inner_ie7.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <!--[if IE 6]>
        <link rel="stylesheet" type="text/css" href="<?PHP echo $media_url; ?>browser/css/menu_ie6.css.css" />
        <style>
            body {behavior: url("<?PHP echo $media_url; ?>browser/css/csshover3.htc");}
            #header_menu li .drop {background:url("<?PHP echo $media_url; ?>browser/img/menu/drop.gif") no-repeat right 8px;
        </style>
        <![endif]-->

        <script type="text/javascript" src="<?PHP echo $media_url; ?>browser/js/styleswitcher.js"></script>

    </head>

    <body>
<?PHP if($maintenance_starts_in): ?>
        <div id='scheduled_maintenance'>Scheduled Maintenance will begin in <?PHP echo $maintenance_starts_in; ?>.&nbsp; We should be back online <?PHP echo $maintenance_ends_at; ?></div>
<?PHP endif; ?>
        <a name="top"></a>
        <a href="#top" accesskey="T"></a>

        <!-- CENTERCOLUMN -->
        <div id="centerColumn">


            <!-- HEADER -->
            <div id="header">

                <!-- LOGO -->
                <div id="logo"></div>

                <!-- NAVIGATION BAR using class="active" makes that item highlight -->
                <div id="nav">
                    <?PHP echo $header_menu; ?>
                </div>

            </div><!-- END OF HEADER -->