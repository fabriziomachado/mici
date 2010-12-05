<script type="text/javascript" src="<?PHP echo $media_url; ?>admin/js/jquery-ui-1.8.4.custom.min.js"></script>
<h2><img src="<?PHP echo $media_url; ?>admin/images/icons/24x24/process.png" alt="icon" style="vertical-align: middle; margin-top: -4px;" /> Settings</h2>
<p id="page-intro">Website Settings</p>
<div class="content-box">
    <div class="content-box-header">
        <h3>Settings</h3>
        <ul class="content-box-tabs">
            <li><a href="#settings" class="default-tab">Settings</a></li>
            <li><a href="#help">Help</a></li>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab" id="settings">
            <div class="notification png_bg" style="display: none;" id="website_settings_notice">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>&nbsp;</div>
            </div>
            <?PHP
            $attributes = array(
                'name' => 'website_settings',
                'id' => 'website_settings',
                'onsubmit' => 'return website_settings_validate();'
            );
            echo form_open(current_url(), $attributes);
            ?>
                <table>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <div class="bulk-actions align-left">
                                    <input type="submit" class="button" value="Update Settings" />
                                </div>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr>
                            <td nowrap="nowrap" width="175" class="right"><label>Maintenance Mode:</label></td>
                            <td nowrap="nowrap">
                                <div id="mm" class="toggle">
                                    <input type="radio" id="mm_off" name="mm" value="disabled"<?PHP if ($mode == 'disabled' || $mode == '') echo ' checked="checked"'; ?> /><label for="mm_off">OFF</label>
                                    <input type="radio" id="mm_on" name="mm" value="enabled"<?PHP if ($mode == 'enabled') echo ' checked="checked"'; ?> /><label for="mm_on">ON</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td nowrap="nowrap" width="175" class="right"><label>Recurring:</label></td>
                            <td nowrap="nowrap">
                                <div id="recurring" class="toggle">
                                    <input type="radio" id="recurring_no" name="recurring" value="no"<?PHP if ($recurring == 'no' || $recurring == '') echo ' checked="checked"'; ?> /><label for="recurring_no">NO</label>
                                    <input type="radio" id="recurring_yes" name="recurring" value="yes"<?PHP if ($recurring == 'yes') echo ' checked="checked"'; ?> /><label for="recurring_yes">YES</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="right"><label>Start Date &amp; Time:</label></td>
                            <td>
                                <input class="text-input small-input" type="text" name="start-date" id="start-date" style="width: 90px !important; padding: 5px !important;" value="<?PHP echo $start_date; ?>" />
                                <select name="start-time" id="start-time">
                                    <?PHP echo $select_time; ?>
                                </select>
                                <input type="button" class="button" value="Now" id="set_now" />
                            </td>
                        </tr>
                        <tr>
                            <td class="right"><label>End Date &amp; Time:</label></td>
                            <td>
                                <input class="text-input small-input" type="text" name="end-date" id="end-date" style="width: 90px !important; padding: 5px !important;" value="<?PHP echo $end_date; ?>" />
                                <select name="end-time" id="end-time">
                                    <?PHP echo $select_time; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="top right"><label for="">Message:</label></td>
                            <td><textarea class="text-input textarea wysiwyg" id="message" name="message" cols="79" rows="15"><?PHP echo $message; ?></textarea></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
        <div class="tab-content" id="help">
            <h5>Maintenance Mode</h5>
            <p>You can disable your website so that you can perform maintenance by setting <strong>Maintenance Mode</strong> to <strong>ON</strong>.  This will redirect all pages of your website to a maintenance page.  This temporary page will automatically refresh every 30 seconds while the user is on the page to check if <strong>Maintenance Mode</strong> has been turned <strong>OFF</strong>.</p>
            <br />
            <h5>Maintenance Expires Date</h5>
            <p>When Maintenance Mode is ON this is the date and time that Maintenance Mode will automatically be terminated.</p>
            <br />
            <h5>Maintenance Message</h5>
            <p>This is the message that will be displayed to the user when they try to access the website while it is in Maintenance Mode.</p>
        </div>
    </div>
</div>
<script type="text/javascript">
var mm = '';
$(function(){
    $("#mm").buttonset();
    $("#recurring").buttonset();
    $("#start-date").datepicker({ dateFormat: 'yy-mm-dd', minDate: 0 });
    $("#end-date").datepicker({ dateFormat: 'yy-mm-dd', minDate: 0 });
    if($('input:radio[name=mm]:checked').val() == 'enabled')
    {
        notification('#website_settings_notice', 'open', '<strong>Maintenance Mode is Currently Enabled<\/strong>!!! &nbsp;This is disabling your public website until you turn Maintenance Mode OFF, or it Expires at the Date and Time set below.', 'information');
    }
    $('#start-time').val('<?PHP echo $start_time; ?>');
    $('#end-time').val('<?PHP echo $end_time; ?>');

    $('#set_now').click(function(){
        var now = new Date();
        var minute = pad(Math.round((now.format('MM'))/5)*5, 2);

        if(minute == 60)
        {
            minute = 55;
        }

        var time = now.format('H')+':'+minute+':00';

        $('#start-date').val(now.format('yyyy-mm-dd'));
        $('#start-time').val(time);
    });
});
function website_settings_validate()
{
    notification('#website_settings_notice', 'close');

    var mm = $('input:radio[name=mm]:checked').val();

    if(mm == '')
    {
        notification('#website_settings_notice', 'open', 'Please choose an action.', 'attention');
        return false;
    }
    else
    {
        if(mm == 'disabled')
        {
            return true;
        }
        else if(mm == 'enabled' && $('#start-date').val() == '')
        {
            notification('#website_settings_notice', 'open', 'Please select a Maintenance Start Date.', 'attention');
            $('#start-date').focus();
            return false;
        }
        else if(mm == 'enabled' && $('#start-time').val() == '')
        {
            notification('#website_settings_notice', 'open', 'Please select a Maintenance Start Time.', 'attention');
            $('#start-time').focus();
            return false;
        }
        else if(mm == 'enabled' && $('#end-date').val() == '')
        {
            notification('#website_settings_notice', 'open', 'Please select a Maintenance End Date.', 'attention');
            $('#end-date').focus();
            return false;
        }
        else if(mm == 'enabled' && $('#end-time').val() == '')
        {
            notification('#website_settings_notice', 'open', 'Please select a Maintenance End Time.', 'attention');
            $('#end-time').focus();
            return false;
        }
        else if(mm == 'enabled' && $('#message').val() == '')
        {
            notification('#website_settings_notice', 'open', 'Please enter a Maintenance Message.', 'attention');
            $('#message').focus();
            return false;
        }
        else if(mm == 'enabled' && confirm("Are you sure you want to enable Maintenance Mode?\nThis will disable your public website until Maintenance Mode is turned OFF."))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
</script>