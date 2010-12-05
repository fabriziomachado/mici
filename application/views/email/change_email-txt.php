Hi <?PHP if (strlen($username) > 0): ?> <?PHP echo $username; ?><?PHP endif; ?>,

You have changed your email address for <?PHP echo $site_name; ?>.
Follow this link to confirm your new email address:

<?PHP echo site_url('/account/reset_email/'.$user_id.'/'.$new_email_key); ?>


Your new email: <?PHP echo $new_email; ?>


You received this email, because it was requested by a <?PHP echo $site_name; ?> user. If you have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.


Thank you,
The <?PHP echo $site_name; ?> Team