<h2><img src="<?PHP echo $media_url;?>system/images/icons/24x24/users.png" alt="icon" style="vertical-align: middle; margin-top: -4px;" /> User Management</h2>
<p id="page-intro">Website Users</p>

<div class="content-box">
    <div class="content-box-header">
		<h3>Users</h3>
                <p style="float: right; margin-right: 10px;"><?php echo anchor('system/users/add', '[+] Add User', array('title' => 'Add a new user', 'class' => 'button')); ?></p>
		<div class="clear"></div>

    </div>

	<div class="content-box-content">
		<div class="tab-content default-tab" id="tab1">

		<?PHP if( !empty($error_message)): ?>
		    <div class="notification error png_bg">
			<a href="#" class="close"><img src="<?PHP echo $media_url;?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
			<div><?PHP echo $error_message; ?></div>
		    </div>
		<?PHP endif; ?>

		<?PHP if(count($users) > 0): ?>

                <div class="notification png_bg" style="display: none;" id="validation_notification">
                        <a href="#" class="close"><img src="<?PHP echo $media_url;?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                        <div>&nbsp;</div>
                </div>

                <?PHP
                $attributes = array(
                    'name' => 'users',
                    'id' => 'users',
                    'onsubmit' => 'return validate();'
                );
                echo form_open(current_url(), $attributes);
                ?>
                <table width="100%" id="data_table" class="display">
                    <thead>
                        <tr>
                            <th width="25"><input class="check-all" type="checkbox" /></th>
                            <th width="150">Name</th>
                            <th width="100">Role</th>
                            <th width="100">Username</th>
                            <th width="125">Created</th>
                            <th>Last Modified</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <div class="bulk-actions align-left">
                                    <select name="action" id="action">
                                    	<option value="" selected="selected">Choose an action...</option>
                                    	<option value="delete" style="color: #990000">Delete</option>
                                    </select>
                                    <input type="submit" class="button" value="Apply to Selected" />
                                    <input class="check-all" type="checkbox" name="checkall" id="checkall" /><label for="checkall" class="check-all-label">select all</label>
                                </div>

                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tfoot>

                    <tbody>
                    <?PHP foreach($users as $user): ?>
                        <tr>
                            <td><input type="checkbox" value="<?PHP echo $user['id']; ?>" name="users[]" /></td>
                            <td><?PHP echo anchor('system/users/edit/id/'.$user['id'], $user['display_name'], array('title' => 'Edit ' . $user['display_name'])); ?></td>
                            <td><?PHP echo $user_types[$user['role']]; ?></td>
                            <td><?PHP echo $user['username']; ?></td>
                            <td><?PHP echo $user['created_at']; ?></td>
                            <td><?PHP echo $user['updated_at']; ?></td>
                        </tr>
                    <?PHP endforeach; ?>
                    </tbody>
                </table>
            <div class="clear"></div>
        </form>

		<?PHP else: ?>

            <div class="notification information png_bg">
            	<a href="#" class="close"><img src="<?PHP echo $media_url;?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
            	<div>There are currently no users.</div>
            </div>

		<?PHP endif; ?>

        </div>

        <div class="tab-content" id="tab2">
            instruction text...
        </div>
    </div>
</div>

<script type="text/javascript">
function validate()
{
    notification('#validation_notification', 'close');

    if( !$('input[name="users[]"]:checked').val())
    {
        notification('#validation_notification', 'open', 'Please Select some Users', 'attention');
        return false;
    }
    else if($('#action').val() == '')
    {
       notification('#validation_notification', 'open', 'Please Choose an Action', 'attention');
        $('#action').focus();
       return false;
    }
    else if( !confirm('Are you sure you want to delete the selected users? This cannot be undone!'))
    {
        return false;
    }
}
</script>

<?PHP if (count($users) > 0): ?>
<script type="text/javascript" language="javascript" src="<?PHP echo $media_url; ?>system/js/dt/jquery.data_tables.js"></script>
<script type="text/javascript" language="javascript" src="<?PHP echo $media_url; ?>system/js/dt/plugins/column_visible.js"></script>
<script type="text/javascript" language="javascript" src="<?PHP echo $media_url; ?>system/js/dt/plugins/fixed_header.js"></script>
<script type="text/javascript" language="javascript" src="<?PHP echo $media_url; ?>system/js/dt/jquery.data_tables_init.js"></script>
<?PHP endif; ?>