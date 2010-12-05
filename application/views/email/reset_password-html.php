<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head><title>Your new password on <?PHP echo $site_name; ?></title></head>
    <body>
        <div style="max-width: 800px; margin: 0; padding: 30px 0;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="5%">&nbsp;</td>
                    <td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
                        <h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Your new password on <?PHP echo $site_name; ?></h2>
                        You have changed your password.<br>
                      <br />
                        Please, keep it in your records so you don't forget it.<br />
                        <br />
                        <?PHP if (strlen($username) > 0): ?>Your username: <?PHP echo $username; ?><br /><?PHP endif; ?>
                        Your email address: <?PHP echo $email; ?><br />
                        <br />
                        <br />
                        Thank you,<br />
                        The <?PHP echo $site_name; ?> Team
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>