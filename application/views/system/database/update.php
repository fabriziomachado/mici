<h2><img src="<?PHP echo $media_url;?>system/images/icons/24x24/save.png" alt="icon" style="vertical-align: middle; margin-top: -4px;" /> Database Management</h2>

<p id="page-intro">Update Database</p>

<?PHP if ($current_version < $latest_version): ?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Update</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab">
        <?PHP if (!empty($error_message)): ?>
            <div class="notification error png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>admin/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div><?PHP echo $error_message; ?></div>
            </div>
        <?PHP endif; ?>
            <h4>Your system needs to be updated!</h4>
            <p>
                You are currently using <?PHP echo anchor('/system/database/migration/view/migration/file/'.$current_version.'_migration', 'Version '.$current_version, array('target'=>'_blank', 'title'=>'View this Schema file in a new window.')); ?>.
                You need to update to <?PHP echo anchor('/system/database/migration/view/migration/file/'.$latest_version.'_migration', 'Version '.$latest_version, array('target'=>'_blank', 'title'=>'View this Schema file in a new window.')); ?>.
            </p>
            <p><?PHP echo anchor('/system/database/update/version/'.$latest_version, 'Update Database', array('class'=>'button', 'id'=>'updatedb')); ?></p>

        </div>
    </div>
</div>

<script type="text/javascript">
$().ready(function(){
    $('#updatedb').click(function(){
        if( !confirm('Are you sure you want to update the database? This cannot be undone.'))
        {
            return false;
        }
    });
});
</script>

<?PHP else: ?>

<div class="content-box">
    <div class="content-box-header">
        <h3>Up to Date</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab">
            <h4>Your System is up to date.</h4>
        </div>
    </div>
</div>

<?PHP endif; ?>