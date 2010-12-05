<?PHP
$dashboard_class = (isset($dashboard_class)) ? $dashboard_class : '';
$users_class = (isset($users_class)) ? $users_class : '';
$database_class = (isset($database_class)) ? $database_class : '';
$database_schema_class = (isset($database_schema_class)) ? $database_schema_class : '';
$database_migration_class = (isset($database_migration_class)) ? $database_migration_class : '';
$database_update_class = (isset($database_update_class)) ? $database_update_class : '';
$logs_class = (isset($logs_class)) ? $logs_class : '';
$settings_class = (isset($settings_class)) ? $settings_class : '';
$settings_tempfiles_class = (isset($settings_tempfiles_class)) ? $settings_tempfiles_class : '';
$settings_flushcache_class = (isset($settings_flushcache_class)) ? $settings_flushcache_class : '';
?>
<div id="sidebar">
    <div id="sidebar-wrapper">

        <h1 id="sidebar-title"><a href="#">System Admin</a></h1>
        <a id="logolink" href="#"><img id="logo" src="<?PHP echo $media_url; ?>system/images/logo.png" alt="System Admin logo" /><span id="logourl"><?PHP echo $_SERVER['SERVER_NAME']; ?></span></a>

        <div id="profile-links">
            <div style="white-space: nowrap">Hello, <?PHP echo $name; ?></div>
            <br />
            <?PHP echo anchor('/', 'View the Site'); ?> &nbsp;|&nbsp; <?PHP echo anchor('/admin', 'Admin Site'); ?> &nbsp;|&nbsp; <?PHP echo anchor('/system/logout', 'Sign Out'); ?>
        </div> 

        <ul id="main-nav">
            <li><?PHP echo anchor('/system', 'Dashboard', array('class' => 'nav-top-item no-submenu' . $dashboard_class)); ?></li>
            <li><?PHP echo anchor('/system/users', 'User Management', array('class' => 'nav-top-item no-submenu'.$users_class)); ?></li>
            <li>
                <?PHP echo anchor('/system/database', 'Database Management', array('class' => 'nav-top-item' . $database_class)); ?>
                <ul>
                    <li><?PHP echo anchor('/system/database/schema', 'View Database Schema', $database_schema_class); ?></li>
                    <li><?PHP echo anchor('/system/database/migration', 'Local Migration Manager', $database_migration_class); ?></li>
                    <li><?PHP echo anchor('/system/database/update', 'Update Database', $database_update_class); ?></li>
                </ul>
            </li>
            <li><?PHP echo anchor('/system/logs/php', 'PHP Error Logs', array('class' => 'nav-top-item no-submenu' . $logs_class)); ?></li>
            <li><?PHP echo anchor('/system/settings/flushcache', 'Settings', array('class' => 'nav-top-item no-submenu' . $settings_class)); ?></li>
            <li><?PHP echo anchor_popup('/system/check', 'System Check', array('title' => 'Open in Pop Up Window', 'class' => 'nav-top-item no-submenu systemcheck', 'width' => '450', 'height' => '700', 'scrollbars' => 'no', 'status' => 'no', 'resizable' => 'no')); ?></li>
        </ul>
    </div>
</div>
