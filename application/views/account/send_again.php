<?PHP echo form_open($this->uri->uri_string()); ?>
<table>
	<tr>
		<td><?PHP echo form_label('Email Address', $email['id']); ?></td>
		<td><?PHP echo form_input($email); ?></td>
		<td style="color: red;"><?PHP echo form_error($email['name']); ?><?PHP echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td>
	</tr>
</table>
<?PHP echo form_submit('send', 'Send'); ?>
<?PHP echo form_close(); ?>

<p><?PHP echo anchor('/', 'Home'); ?></p>