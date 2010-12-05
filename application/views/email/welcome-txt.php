Welcome to <?PHP echo $site_name; ?>,

Thanks for joining <?PHP echo $site_name; ?>. We listed your sign in details below. Make sure you keep them safe.
Follow this link to login on the site:

<?PHP echo site_url('/account/login/'); ?>

<?PHP if (strlen($username) > 0): ?>
Your username: <?PHP echo $username; ?>
<?PHP endif; ?>

Your email address: <?PHP echo $email; ?>

Have fun!
The <?PHP echo $site_name; ?> Team