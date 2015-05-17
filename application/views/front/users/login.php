<script type="text/javascript">
function frmcheck(form) {
	var email = form.elements.email.value
	if(form.elements.email.value=='') {
		alert("Please enter Email.");
		form.elements.email.focus();
		return false;
	}
	
	var emailreg = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	if(!emailreg.test(email)) {
		alert('Please enter a valid Email address.');
		form.elements.email.focus();
		return false;
	}
	
	if(form.elements.password.value=='') {
		alert("Please enter Password.");
		form.elements.password.focus();
		return false;
	}
}
</script>

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice; ?></p>
<?php endif;?>
<h2>Login</h2>
<form class="fb_sign_up" action="<?php echo $this->config->item('base_url_two').'login'; ?>" method="post" accept-charset="utf-8" onsubmit="return frmcheck(this);">
	<div class="form-group">
		<input class="form-control" type="text" placeholder="Email" name='email' id='email' />
	</div>
	<div class="form-group">
		<input class="form-control" type="password" placeholder="Password" name="password" id="password" />
	</div>
	<input type="submit" name="submit" id="submit" value="Login" class="btn btn-default" />
</form>