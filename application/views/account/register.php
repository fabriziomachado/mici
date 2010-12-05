<h1>Register</h1>
<?PHP echo form_open($this->uri->uri_string()); ?>
<table>
	<?PHP if ($use_username): ?>
	<tr>
		<td><?PHP echo form_label('Username', $username['id']); ?></td>
		<td><?PHP echo form_input($username); ?></td>
		<td style="color: red;"><?PHP echo form_error($username['name']); ?><?PHP echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?></td>
	</tr>
	<?PHP endif; ?>
	<tr>
		<td><?PHP echo form_label('Email Address', $email['id']); ?></td>
		<td><?PHP echo form_input($email); ?></td>
		<td style="color: red;"><?PHP echo form_error($email['name']); ?><?PHP echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td>
	</tr>
	<tr>
		<td><?PHP echo form_label('Password', $password['id']); ?></td>
		<td><?PHP echo form_password($password); ?></td>
		<td style="color: red;"><?PHP echo form_error($password['name']); ?></td>
	</tr>
	<tr>
		<td><?PHP echo form_label('Confirm Password', $confirm_password['id']); ?></td>
		<td><?PHP echo form_password($confirm_password); ?></td>
		<td style="color: red;"><?PHP echo form_error($confirm_password['name']); ?></td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<?PHP echo $recaptcha_html; ?>
		</td>
	</tr>
</table>
<?PHP echo form_submit('register', 'Register'); ?>
<?PHP echo form_close(); ?>

<p><?PHP echo anchor('/', 'Home'); ?></p>