<div id="sidebar">
    <div id="sidebar-wrapper">
        <h1 id="sidebar-title"><a href="<?PHP echo $base_url; ?>admin">Administrator</a></h1>
        <a id="logolink" href="<?PHP echo $base_url; ?>admin"><img id="logo" src="<?PHP echo $media_url; ?>admin/images/logo.png" alt="Administrator logo" /><span id="logourl"><?PHP echo $_SERVER['HTTP_HOST']; ?></span></a>
        <div id="profile-links">
            <div style="white-space: nowrap">Hello, <?PHP echo $name; ?></div><br />
            <?PHP echo anchor('/', 'View the Site', array('target' => '_blank')); ?> &nbsp;|&nbsp; <?PHP echo anchor('/admin/logout', 'Sign Out'); ?>
        </div>
        <ul id="main-nav">
            <li><?PHP echo anchor('/admin', 'Dashboard', array('class' => 'nav-top-item no-submenu'.$dashboard_class));	?></li>
            <li><?PHP echo anchor('/admin/tag', 'Tag Management', array('class' => 'nav-top-item no-submenu'.$tag_class)); ?></li>
            <li><?PHP echo anchor('/admin/settings', 'Website Settings', array('class' => 'nav-top-item no-submenu'.$settings_class)); ?></li>
        </ul>
    </div>
</div>