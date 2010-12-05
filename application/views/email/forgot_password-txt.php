Hi <?PHP if (strlen($username) > 0): ?> <?PHP echo $username; ?><?PHP endif; ?>,

Forgot your password, huh? No big deal.
To create a new password, just follow this link:

<?PHP echo site_url('/account/reset_password/'.$user_id.'/'.$new_pass_key); ?>


You received this email, because it was requested by a <?PHP echo $site_name; ?> user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same.


Thank you,
The <?PHP echo $site_name; ?> Team