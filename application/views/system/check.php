<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>System Admin | System Check</title>

        <link rel="stylesheet" href="<?PHP echo $media_url;?>system/css/reset.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?PHP echo $media_url;?>system/css/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?PHP echo $media_url;?>system/css/invalid.css" type="text/css" media="screen" />

        <!--[if lte IE 7]>
            <link rel="stylesheet" href="<?PHP echo $media_url;?>system/css/ie.css" type="text/css" media="screen" />
        <![endif]-->

        <!--[if IE 6]>
            <script type="text/javascript" src="<?PHP echo $media_url;?>system/js/DD_belatedPNG_0.0.7a.js"></script>
            <script type="text/javascript">
                DD_belatedPNG.fix('.png_bg, img, li');
            </script>
        <![endif]-->
    </head>
    <body id="logindiv">
        <div id="login-wrapper" class="png_bg" style="height: 60px;">
            <div id="login-top" style="padding: 15px 0 20px 0;">
                <h1>System Admin</h1>
                <img id="logo" src="<?PHP echo $media_url;?>system/images/logo.png" alt="System Admin logo" />
            </div>
            <div id="check-content">
            <?PHP if($failure === TRUE): ?>
                <div class="notification error png_bg" style="display: none;">
            <?PHP else: ?>
                <div class="notification information png_bg" style="display: none;">
            <?PHP endif; ?>
                    <a href="#" class="close"><img src="<?PHP echo $media_url;?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                    <div></div>
                </div>
                <h2><img src="<?PHP echo $media_url;?>system/images/icons/32x32/health.png" style="vertical-align: middle; margin-right: 10px; margin-top: -10px;" />System Check</h2>
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <th>Framework Check</th>
                    <th colspan="2">Status</th>
                  </tr>
                  <tr>
                    <td width="260">INI File Found:</td>
                    <td width="50"><center><img src="<?PHP echo $media_url;?>system/images/icons/32x32/<?PHP echo $ini_image; ?>.png" /></center></td>
                    <td width="30">
                    <?PHP if( !empty($ini_msg)):?>
                        <center><a href="#" onclick="notice('<?PHP echo addslashes($ini_msg); ?>'); return false;"><img src="<?PHP echo $media_url;?>system/images/icons/16x16/help.png" border="0" /></a></center>
                    <?PHP else: ?>
                        &nbsp;
                    <?PHP endif; ?>
                    </td>
                  </tr>
                  <tr class="altrow">
                    <td>Database Found:</td>
                    <td><center><img src="<?PHP echo $media_url;?>system/images/icons/32x32/<?PHP echo $db_image; ?>.png" /></center></td>
                    <td>
                    <?PHP if( !empty($db_msg)):?>
                        <center><a href="#" onclick="notice('<?PHP echo addslashes($db_msg); ?>'); return false;"><img src="<?PHP echo $media_url;?>system/images/icons/16x16/help.png" border="0" /></a></center>
                    <?PHP else: ?>
                        &nbsp;
                    <?PHP endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Tables Found:</td>
                    <td><center><img src="<?PHP echo $media_url;?>system/images/icons/32x32/<?PHP echo $tables_image; ?>.png" /></center></td>
                    <td>
                    <?PHP if( !empty($tables_msg)):?>
                        <center><a href="#" onclick="notice('<?PHP echo addslashes($tables_msg); ?>'); return false;"><img src="<?PHP echo $media_url;?>system/images/icons/16x16/help.png" border="0" /></a></center>
                    <?PHP else: ?>
                        &nbsp;
                    <?PHP endif; ?>
                    </td>
                  </tr>
                  <tr class="altrow">
                    <td>Log Folder Writable:</td>
                    <td><center><img src="<?PHP echo $media_url;?>system/images/icons/32x32/<?PHP echo $log_path_image; ?>.png" /></center></td>
                    <td>
                    <?PHP if( !empty($log_path_msg)):?>
                        <center><a href="#" onclick="notice('<?PHP echo addslashes($log_path_msg); ?>'); return false;"><img src="<?PHP echo $media_url;?>system/images/icons/16x16/help.png" border="0" /></a></center>
                    <?PHP else: ?>
                        &nbsp;
                    <?PHP endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Cache Folder Writable:</td>
                    <td><center><img src="<?PHP echo $media_url;?>system/images/icons/32x32/<?PHP echo $cache_path_image; ?>.png" /></center></td>
                    <td>
                    <?PHP if( !empty($cache_path_msg)):?>
                        <center><a href="#" onclick="notice('<?PHP echo addslashes($cache_path_msg); ?>'); return false;"><img src="<?PHP echo $media_url;?>system/images/icons/16x16/help.png" border="0" /></a></center>
                    <?PHP else: ?>
                        &nbsp;
                    <?PHP endif; ?>
                    </td>
                  </tr>
                </table>

                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <th>Server Check</th>
                    <th colspan="2">Status</th>
                  </tr>
                  <tr>
                    <td width="260">PHP 5.2 or Higher:</td>
                    <td width="50"><center><img src="<?PHP echo $media_url;?>system/images/icons/32x32/<?PHP echo $php_image; ?>.png" /></center></td>
                    <td width="30">
                    <?PHP if( !empty($php_msg)):?>
                        <center><a href="#" onclick="notice('<?PHP echo addslashes($php_msg); ?>'); return false;"><img src="<?PHP echo $media_url;?>system/images/icons/16x16/help.png" border="0" /></a></center>
                    <?PHP else: ?>
                        &nbsp;
                    <?PHP endif; ?>
                    </td>
                  </tr>
                  <tr class="altrow">
                    <td>Memcache Enabled:</td>
                    <td><center><img src="<?PHP echo $media_url;?>system/images/icons/32x32/<?PHP echo $memcache_image; ?>.png" /></center></td>
                    <td>
                    <?PHP if( !empty($memcache_msg)):?>
                        <center><a href="#" onclick="notice('<?PHP echo addslashes($memcache_msg); ?>'); return false;"><img src="<?PHP echo $media_url;?>system/images/icons/16x16/help.png" border="0" /></a></center>
                    <?PHP else: ?>
                        &nbsp;
                    <?PHP endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>SPL Enabled:</td>
                    <td><center><img src="<?PHP echo $media_url;?>system/images/icons/32x32/<?PHP echo $spl_image; ?>.png" /></center></td>
                    <td>
                    <?PHP if( !empty($spl_msg)):?>
                        <center><a href="#" onclick="notice('<?PHP echo addslashes($spl_msg); ?>'); return false;"><img src="<?PHP echo $media_url;?>system/images/icons/16x16/help.png" border="0" /></a></center>
                    <?PHP else: ?>
                        &nbsp;
                    <?PHP endif; ?>
                    </td>
                  </tr>
                </table>
                <hr noshade="noshade" />
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <?PHP if($failure === TRUE): ?>
                    <td width="60" style="text-indent: 0px;"><img src="<?PHP echo $media_url;?>system/images/icons/48x48/smile_sad_48.png" /></td><td style="text-indent: 0px;"><span style="color:red">This application may not work unless you correct the issues above.</span></td>
                    <?PHP else: ?>
                    <td width="60" style="text-indent: 0px;"><img src="<?PHP echo $media_url;?>system/images/icons/48x48/smile_grin_48.png" /></td><td style="text-indent: 0px;"><span style="color:green">Your environment passed all requirements. You're good to go!&nbsp; <?PHP echo anchor('/system', 'Continue &raquo;'); ?></span></td>
                    <?PHP endif; ?>
                  </tr>
                </table>
            </div>
        </div>
        <div id="manifest">Created by Manifest Interactive, LLC</div>
        <script type="text/javascript" src="<?PHP echo $media_url;?>system/js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript">
        function notice(text)
        {
            $('.notification').slideDown(400, function(){
                $(this).fadeTo(400, 1);
                $('.notification div').html(text);
            });
        }
        $(".close").click(
            function () {
                $(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
                    $(this).slideUp(400);
                });
                return false;
            }
        );
        </script>
    </body>
</html>