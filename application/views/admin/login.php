<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

        <title>Administrator | Sign In</title>

        <link rel="stylesheet" href="<?PHP echo $media_url; ?>admin/css/reset.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?PHP echo $media_url; ?>admin/css/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?PHP echo $media_url; ?>admin/css/invalid.css" type="text/css" media="screen" />

        <!--[if lte IE 7]>
            <link rel="stylesheet" href="<?PHP echo $media_url; ?>admin/css/ie.css" type="text/css" media="screen" />
        <![endif]-->

        <!--[if IE 6]>
            <script type="text/javascript" src="<?PHP echo $media_url; ?>admin/js/DD_belatedPNG_0.0.7a.js"></script>
            <script type="text/javascript">
                DD_belatedPNG.fix('.png_bg, img, li');
            </script>
        <![endif]-->

    </head>

    <body id="logindiv">
        <div id="login-wrapper" class="png_bg">
            <div id="login-top">
                <h1>Administrator</h1>
                <img id="logo" src="<?PHP echo $media_url; ?>admin/images/logo.png" alt="Administrator logo" />
            </div>
            <div id="login-content">
                <?PHP
                $attributes = array(
                    'name' => 'loginform',
                    'id' => 'loginform',
                    'onsubmit' => 'return validate();'
                );
                echo form_open(current_url(), $attributes);
                ?>
                <?PHP
                if (isset($errors)):
                    foreach ($errors as $error):
                ?>
                <div class="notification error png_bg">
                    <div><?PHP echo $error; ?></div>
                </div>
                <?PHP
                    endforeach;
                endif;
                ?>
                    <p><label for="login">Username</label> <input class="text-input" type="text" name="login" id="login" value="<?PHP echo $login; ?>" /></p>
                    <div class="clear"></div>
                    <p><label for="password">Password</label> <input class="text-input" type="password" name="password" id="password" /></p>
                    <div class="clear"></div>
                    <p id="remember-password"><label for="remember"><input type="checkbox" name="remember" value="1" id="remember"<?PHP if (!empty($remember)) echo ' checked="checked"'; ?> />Remember me</label></p>
                    <div class="clear"></div>
                    <p><input class="button" name="submit" type="submit" value="Sign In" /></p>
                </form>
            </div>
        </div>

        <div id="manifest"><a href="http://www.manifestinteractive.com" target="_blank" style="color: #666;" title="Visit Manifest Interactive, LLC">&#169; Copyright <?PHP echo date('Y'); ?> Manifest Interactive, LLC &nbsp;&middot;&nbsp; All Rights Reserved</a></div>

        <script type="text/javascript">
            document.getElementById('loginform').setAttribute('spellcheck', false);
            document.getElementById('loginform').setAttribute('autocomplete', 'off');
            document.getElementById('login').focus();
        </script>
        
        <?PHP if(FRAMEWORK_APPLICATION_ENV != 'production'): ?>
        <div id="server_badge" class="<?PHP echo FRAMEWORK_APPLICATION_ENV; ?>"><?PHP echo FRAMEWORK_APPLICATION_ENV; ?></div>
        <?PHP endif; ?>
    </body>
</html>