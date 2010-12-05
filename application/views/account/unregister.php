<?PHP echo form_open($this->uri->uri_string()); ?>
<table>
	<tr>
		<td><?PHP echo form_label('Password', $password['id']); ?></td>
		<td><?PHP echo form_password($password); ?></td>
		<td style="color: red;"><?PHP echo form_error($password['name']); ?><?PHP echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></td>
	</tr>
</table>
<?PHP echo form_submit('cancel', 'Delete account'); ?>
<?PHP echo form_close(); ?>

<p><?PHP echo anchor('/', 'Home'); ?></p>