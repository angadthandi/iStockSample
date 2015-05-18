<script type="text/javascript">
function frmcheck(form) {
	/* VALIDATION STARTS HERE. */
	var oldPassword = $("#oldPassword");
	var newPassword = $("#newPassword");
	var confirmPassword = $("#confirmPassword");
	
	
	if( trim_all( oldPassword.val() ) == "" ){
		alert("Please enter your Old Password.");
		oldPassword.focus();
		return false;
	}
	if( trim_all( newPassword.val() ) == "" ){
		alert("Please enter your New Password.");
		newPassword.focus();
		return false;
	}
	else if( newPassword.val().length < 6 ){
		alert("New Password must be of minimum 6 characters.");
		newPassword.focus();
		return false;
	}
	else if( trim_all( confirmPassword.val() ) == "" ){
		alert("Please enter Confirm Password.");
		confirmPassword.focus();
		return false;
	}	
	else if( newPassword.val() != confirmPassword.val()){
		alert("New Password & Confirm Password do not match.");
		confirmPassword.focus();
		return false;
	}
	
}
</script>

<!-- display msg -->
<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice; ?></p>
<?php endif;?>
<h2>Change Password</h2>
<form name="change_password_form" action="<?php echo $this->config->item('base_url').'changepassword'; ?>" method="post" accept-charset="utf-8" onsubmit="return frmcheck(this);">
	<div class="form-group">
		<input class="form-control" type="password" placeholder="Old Password" name="oldPassword" value="" id="oldPassword" maxlength="20" />
	</div>
	<div class="form-group">
		<input class="form-control" type="password" placeholder="New Password" name="newPassword" value="" id="newPassword" maxlength="20" />
	</div>
	<div class="form-group">
		<input class="form-control" type="password" placeholder="Confirm Password" name="confirmPassword" value="" id="confirmPassword" />
	</div>
	<input type="submit" value="Save" class="btn btn-default" name="submit" id="submit" />
	<input type="button" value="Cancel" class="btn btn-default" name="cancel" id="cancel" onclick="javascript:window.location='<?php echo $this->config->item('base_url').'dashboard'; ?>'" />
</form>