Welcome to <?PHP echo $site_name; ?>,

Thanks for joining <?PHP echo $site_name; ?>. We listed your sign in details below, make sure you keep them safe.
To verify your email address, please follow this link:

<?PHP echo site_url('/account/activate/'.$user_id.'/'.$new_email_key); ?>


Please verify your email within <?PHP echo $activation_period; ?> hours, otherwise your registration will become invalid and you will have to register again.

<?PHP if (strlen($username) > 0): ?>
Your username: <?PHP echo $username; ?>
<?PHP endif; ?>

Your email address: <?PHP echo $email; ?>


Have fun!
The <?PHP echo $site_name; ?> Team