<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head><title>Welcome to <?PHP echo $site_name; ?>!</title></head>
    <body>
        <div style="max-width: 800px; margin: 0; padding: 30px 0;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="5%">&nbsp;</td>
                    <td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;"><h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Welcome to <?PHP echo $site_name; ?>!</h2>
                        Thanks for joining <?PHP echo $site_name; ?>. We listed your sign in details below, make sure you keep them safe.<br>
                      <br />
                        To open your <?PHP echo $site_name; ?> homepage, please follow this link:<br />
                        <br />
                        <big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?PHP echo site_url('/account/login/'); ?>" style="color: #3366cc;">Go to <?PHP echo $site_name; ?> now!</a></b></big><br />
                        <br />
                        Link doesn't work? Copy the following link to your browser address bar:<br />
                        <nobr><a href="<?PHP echo site_url('/account/login/'); ?>" style="color: #3366cc;"><?PHP echo site_url('/account/login/'); ?></a></nobr><br />
                        <br />
                        <br />
                        <?PHP if (strlen($username) > 0): ?>
                        Your username: <?PHP echo $username; ?><br />
                        <?PHP endif; ?>
                        Your email address: <?PHP echo $email; ?><br />
                        <br />
                        <br />
                        Have fun!<br />
                        The <?PHP echo $site_name; ?> Team
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>