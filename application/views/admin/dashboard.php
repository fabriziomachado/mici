<?PHP $errorfiles = 3; ?>
<h2>Welcome, <?PHP echo $name; ?></h2>
<p id="page-intro">What would you like to do?</p>

<ul class="shortcut-buttons-set">
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>admin/tag" title="Tag Management"><span><img src="<?PHP echo $media_url;?>admin/images/icons/64x64/map.png" alt="icon" /><br />Tags</span></a></li>
    <li><a class="shortcut-button" href="<?PHP echo base_url(); ?>admin/settings" title="Website Settings"><span><img src="<?PHP echo $media_url;?>admin/images/icons/64x64/process.png" alt="icon" /><br />Settings</span></a></li>
</ul>

<div class="clear"></div>