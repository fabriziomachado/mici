<h1>Login</h1>
<?PHP echo form_open($this->uri->uri_string()); ?>
<table>
	<tr>
		<td><?PHP echo form_label($login_label, $login['id']); ?></td>
		<td><?PHP echo form_input($login); ?></td>
		<td style="color: red;"><?PHP echo form_error($login['name']); ?><?PHP echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
	</tr>
	<tr>
		<td><?PHP echo form_label('Password', $password['id']); ?></td>
		<td><?PHP echo form_password($password); ?></td>
		<td style="color: red;"><?PHP echo form_error($password['name']); ?><?PHP echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></td>
	</tr>
	<tr>
		<td colspan="3">
			<?PHP echo form_checkbox($remember); ?>
			<?PHP echo form_label('Remember me', $remember['id']); ?>
		</td>
	</tr>
</table>
<?PHP echo form_submit('submit', 'Let me in'); ?>
<?PHP echo form_close(); ?>


<p>
<?PHP echo anchor('/', 'Home'); ?> | 
<?PHP echo anchor('/account/forgot_password/', 'Forgot password'); ?> |
<?PHP if ($this->config->item('allow_registration')) echo anchor('/account/register/', 'Register'); ?>
</p>