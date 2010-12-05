<h2><img src="<?PHP echo $media_url;?>system/images/icons/24x24/page.png" alt="icon" style="vertical-align: middle; margin-top: -4px;" /> PHP Error Logs</h2>
<?PHP if (isset($logfile)): ?>
<p><a class="button" href="<?PHP echo base_url(); ?>system/logs/php">&laquo; Back</a> <a class="button" href="#" onclick="location.reload(); return false;">Refresh</a></p>
<br />
<div class="content-box">
    <div class="content-box-header">
        <h3><?PHP echo $logfile; ?></h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab" id="tab1">
            <table>
                <tbody>
                <?PHP
                $i = 0;
                foreach ($errors as $error):
                    $i++;
                ?>
                    <tr>
                        <td style="color: #999;"><?PHP echo $i; ?></td>
                        <td><?PHP echo $error; ?></td>
                    </tr>
                <?PHP endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?PHP else: ?>
<script type="text/javascript">
function validate()
{
    notification('#logfilesnotice', 'close');

    if($('#action').val() == '')
    {
        $('#action').focus();
        notification('#logfilesnotice', 'open', 'Please choose an action.', 'attention');
        return false;
    }
    if( !$("input:checked[name='logs[]']").val())
    {
        notification('#logfilesnotice', 'open', 'Please select one or more log files to delete.', 'attention');
        return false;
    }
    else
    {
        $('#logfiles').submit();
    }
}
</script>
<p id="page-intro">Archive of daily PHP Errors</p>
<div class="content-box">
    <div class="content-box-header">
        <h3>Logs</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab" id="logs">
        <?PHP if (count($errorfiles) > 0): ?>
            <div class="notification png_bg" style="display: none;" id="logfilesnotice">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>&nbsp;</div>
            </div>
            <?PHP
            $attributes = array(
                'name' => 'logfiles',
                'id' => 'logfiles',
                'onsubmit' => 'return validate();'
            );
            echo form_open(current_url(), $attributes);
            ?>
                <table>
                    <thead>
                        <tr>
                            <th width="25"><input class="check-all" type="checkbox" /></th>
                            <th width="200">Date</th>
                            <th width="100">Modified</th>
                            <th width="50">Lines</th>
                            <th>Size</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <div class="bulk-actions align-left">
                                    <select name="action" id="action">
                                        <option value="" selected="selected">Choose an action...</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                    <a class="button" href="#" onclick="validate(); return false;">Apply to selected</a>
                                </div>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                    <?PHP foreach ($errorfiles as $file): ?>
                        <tr>
                            <td nowrap="nowrap"><input type="checkbox" value="<?PHP echo $file['name']; ?>" name="logs[]" /></td>
                            <td nowrap="nowrap"><a href="<?PHP echo base_url(); ?>system/logs/php/date/<?PHP echo substr($file['name'], 0, 14); ?>" title="<?PHP echo $file['path']; ?>"><?PHP echo date('l, F jS, Y', strtotime(substr($file['name'], 4, 10))); ?></a></td>
                            <td nowrap="nowrap"><?PHP echo $file['modified']; ?></td>
                            <td nowrap="nowrap"><?PHP echo $file['lines']; ?></td>
                            <td nowrap="nowrap"><?PHP echo $file['size']; ?></td>
                        </tr>
                    <?PHP endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?PHP else: ?>
            <div class="notification information png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>There are currently no PHP Error log files to display.</div>
            </div>
        <?PHP endif; ?>
        </div>
    </div>
</div>
<?PHP endif; ?>