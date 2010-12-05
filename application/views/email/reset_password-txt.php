Hi <?PHP if (strlen($username) > 0): ?> <?PHP echo $username; ?><?PHP endif; ?>,

You have changed your password.
Please, keep it in your records so you don't forget it.
<?PHP if (strlen($username) > 0): ?>

Your username: <?PHP echo $username; ?>
<?PHP endif; ?>

Your email address: <?PHP echo $email; ?>

Thank you,
The <?PHP echo $site_name; ?> Team