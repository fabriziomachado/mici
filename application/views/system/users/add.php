<h2><img src="<?PHP echo $media_url;?>system/images/icons/24x24/users.png" alt="icon" style="vertical-align: middle; margin-top: -4px;" /> User Management</h2>

<p><?php echo anchor('system/users/index', '&laquo; Back to Users', array('title' => 'Back to User Listing', 'class' => 'button'));?></p>

<br />

<div class="content-box">
    <div class="content-box-header">
        <h3>Add User</h3>

        <div class="clear"></div>

    </div>

    <div class="content-box-content">
        <div class="tab-content default-tab">
	    <?PHP if( !empty($error_message)): ?>
		    <div class="notification error png_bg">
			<a href="#" class="close"><img src="<?PHP echo $media_url;?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
			<div><?PHP echo $error_message; ?></div>
		    </div>
		<?PHP endif; ?>

			<div class="notification png_bg" id="validation_notification" style="display: none;">
                <a href="#" class="close"><img src="<?PHP echo $media_url;?>admin/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>&nbsp;</div>
            </div>

            <?PHP
            $attributes = array(
                'name' => 'add_user',
                'id' => 'add_user',
                'onsubmit' => 'return validate();'
            );
            echo form_open(current_url(), $attributes);
            ?>
                <table>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <input class="button" type="submit" value="Submit" />

                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr>
                            <td width="150" class="right"><label for="role">Role:</label></td>
                            <td>
                                <select name="role" id="role">
                                    <option value="" selected="selected">&nbsp;</option>
                                    <option value="admin">Admin</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                                <small style="color: #999">&nbsp; "Admin" can access /admin.&nbsp; "Super Admin" can access /admin &amp; /system.</small>
                            </td>
                        </tr>
                        <tr>
                            <td width="125" class="right"><label for="username">Username:</label></td>
                            <td><input type="text" name="username" id="username" class="text-input small-input" value="" maxlength="16" /></td>
                        </tr>
                        <tr>
                            <td width="125" class="right"><label for="display_name">Real Name:</label></td>
                            <td><input type="text" name="display_name" id="display_name" class="text-input medium-input" value="" /></td>
                        </tr>
                        <tr>
                            <td width="125" class="right"><label for="email">Email Address:</label></td>
                            <td><input type="text" name="email" id="email" class="text-input medium-input" value="" /></td>
                        </tr>
                        <tr>
                            <td width="125" class="right"><label for="password">Password:</label></td>
                            <td><input type="password" name="password" id="password" class="text-input small-input" value="" /></td>
                        </tr>
                        <tr>
                            <td width="125" class="right"><label for="confirm_password" style="color: #999">Confirm Password:</label></td>
                            <td><input type="password" name="confirm_password" id="confirm_password" class="text-input small-input" value="" /></td>
                        </tr>
                    </tbody>
                </table>

                <div class="clear"></div>

          </form>
      	</div>
    </div>
</div>

<script type="text/javascript" src="<?PHP echo $media_url;?>system/js/password_strength_plugin.js"></script>
<script type="text/javascript">
$("#password").passStrength({
    userid: "#username"
});

function validate()
{
    notification('#validation_notification', 'close');

    var regx = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

    if($('#role').val() == '')
    {
        notification('#validation_notification', 'open', 'Please Select a Role', 'attention');
        $('#role').focus();
        return false;
    }
    else if($('#username').val() == '')
    {
       notification('#validation_notification', 'open', 'Please Enter a Username', 'attention');
        $('#username').focus();
       return false;
    }
    else if($('#display_name').val() == '')
    {
        notification('#validation_notification', 'open', 'Please Enter a Real Name', 'attention');
        $('#display_name').focus();
        return false;
    }
    else if($('#email').val() == '')
    {
        notification('#validation_notification', 'open', 'Please Enter an Email Address', 'attention');
        $('#email').focus();
        return false;
    }
    else if(regx.test($('#email').val()) == false)
    {
        notification('#validation_notification', 'open', 'Please Enter a Valid Email Address', 'attention');
        $('#email').focus();
        return false;
    }
    else if($('#password').val() == '')
    {
        notification('#validation_notification', 'open', 'Please Enter a Password', 'attention');
        $('#password').focus();
        return false;
    }
    else if($('#password').val() !== $('#confirm_password').val())
    {
        notification('#validation_notification', 'open', 'Your Passwords do not Match.', 'attention');
        $('#password').focus();
        return false;
    }
}
</script>