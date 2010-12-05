<h2><img src="<?PHP echo $media_url; ?>system/images/icons/24x24/process.png" alt="icon" style="vertical-align: middle; margin-top: -4px;" /> Settings</h2>
<p id="page-intro">Flush Cache</p>
<div class="content-box">
    <div class="content-box-header">
        <h3>Manager</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab" id="manager">
    <?PHP if ($cache_status): ?>
        <?PHP foreach ($cache_status as $status): ?>
            <div class="notification success png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div><?PHP echo $status; ?></div>
            </div>
        <?PHP endforeach; ?>
    <?PHP endif; ?>
            <div class="notification png_bg" style="display: none;" id="cachenotice">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>&nbsp;</div>
            </div>
            <?PHP
            $attributes = array(
                'name' => 'cachedata',
                'id' => 'cachedata',
                'onsubmit' => 'return validate();'
            );
            echo form_open(current_url(), $attributes);
            ?>
                <table>
                    <thead>
                        <tr>
                            <th width="25"><input class="check-all" type="checkbox" /></th>
                            <th width="100">Cache Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <div class="bulk-actions align-left">
                                    <select name="action" id="action">
                                        <option value="" selected="selected">Choose an action...</option>
                                        <option value="flush">Flush Cache</option>
                                    </select>
                                    <a class="button" href="#" onclick="validate(); return false;">Apply to selected</a>
                                </div>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" value="yes" name="apc"<?PHP if ($apc_installed === FALSE): ?> disabled="disabled"<?PHP endif; ?> /></td>
                            <td nowrap="nowrap">APC Cache</td>
                            <td nowrap="nowrap">
                            <?PHP if ($apc_installed === TRUE): ?>
                                Active
                            <?PHP else: ?>
                                <span style="color: red;">Not Installed</span>
                            <?PHP endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" value="yes" name="memcache"<?PHP if ($memcache_installed === FALSE): ?> disabled="disabled"<?PHP endif; ?> /></td>
                            <td nowrap="nowrap">Memcache</td>
                            <td nowrap="nowrap">
                            <?PHP if ($memcache_installed === TRUE): ?>
                                Active
                            <?PHP else: ?>
                                <span style="color: red;">Not Installed</span>
                            <?PHP endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" value="yes" name="disk"<?PHP if ($diskcache_installed === FALSE): ?> disabled="disabled"<?PHP endif; ?> /></td>
                            <td nowrap="nowrap">Disk Cache</td>
                            <td nowrap="nowrap">
                            <?PHP if ($diskcache_installed === TRUE): ?>
                                Active <span style="color: #999;">( <?PHP echo $disk_cache_count; ?> )</span>
                            <?PHP else: ?>
                                <span style="color: red;">No Cache Files</span>
                            <?PHP endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
function validate()
{
    notification('#cachenotice', 'close');

    if($('#action').val() == '')
    {
        $('#action').focus();
        notification('#cachenotice', 'open', 'Please choose an action.', 'attention');
        return false;
    }
    if( !$("input:checked").val())
    {
        notification('#cachenotice', 'open', 'Please select one or more cache methods.', 'attention');
        return false;
    }
    else
    {
        $('#cachedata').submit();
    }
}
</script>