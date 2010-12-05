<?PHP echo form_open($this->uri->uri_string()); ?>
<table>
	<tr>
		<td><?PHP echo form_label($login_label, $login['id']); ?></td>
		<td><?PHP echo form_input($login); ?></td>
		<td style="color: red;"><?PHP echo form_error($login['name']); ?><?PHP echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
	</tr>
</table>
<?PHP echo form_submit('reset', 'Get a new password'); ?>
<?PHP echo form_close(); ?>

<p><?PHP echo anchor('/', 'Home'); ?></p>