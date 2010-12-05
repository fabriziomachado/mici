<h2>Welcome, <?PHP echo $name; ?></h2>
<p id="page-intro">What would you like to do?</p>

<ul class="shortcut-buttons-set">
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>system/users" title="User Management"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/users.png" alt="icon" /><br />Users</span></a></li>
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>system/database/schema" title="Database Management"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/save.png" alt="icon" /><br />Database</span></a></li>
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>system/logs/php" title="PHP Error Logs"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/page.png" alt="icon" /><br />Error Logs</span></a></li>
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>system/settings" title="Settings"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/process.png" alt="icon" /><br />Settings</span></a></li>
</ul>

<div class="clear"></div>

<ul class="shortcut-buttons-set">
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>system/asset_generator" title="Asset Generator"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/photo_camera.png" alt="icon" /><br />Asset Generator</span></a></li>
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>system/check" onclick="new_window(this.href, 'systemcheck', 450, 700, 'yes'); return false;" title="System Check ( Open in Pop Up Window )"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/accept.png" alt="icon" /><br />System Check</span></a></li>
    <!--//<li><a class="shortcut-button" href="#messages" rel="modal" style="position: relative;"><span><img src="<?PHP echo $media_url;?>system/images/icons/64x64/warnings_64.png" alt="icon" /><br />Alerts</span><span id="alerts">3</span></a></li>//-->
</ul>

<div class="clear"></div>