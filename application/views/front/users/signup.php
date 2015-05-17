<script type="text/javascript">
function frmcheck(form) {
	/* VALIDATION STARTS HERE. */
	var Name = $("#name");
	var Email = $("#email");
	var Password = $("#password");
	var Confirm_Password = $("#confirm_password");
	var UserType = $("#userType");
	var emailreg = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	
	
	if( trim_all( Name.val() ) == "" ){
		alert("Please enter Name.");
		Name.focus();
		return false;
	}
	if( trim_all( Name.val() ) != "" ){
		var iChars = "1234567890.!@#$%^&*()+=-[]\\\';,/{}|\":<>?";
		for (var i = 0; i < document.signup_form.Name.value.length; i++) {
			if (iChars.indexOf(document.signup_form.Name.value.charAt(i)) != -1) {
				alert ("Only alphabets are allowed in Name.");
				document.signup_form.Name.focus();
				return false;
			}
		}
	}
	if( trim_all( Email.val() ) == "" ){
		alert("Please enter Email.");
		Email.focus();
		return false;
	}
	else if(!emailreg.test( Email.val() )) {
		alert('Please enter a valid Email address.');
		Email.focus();
		return false;
	}
	if( trim_all( Password.val() ) == "" ){
		alert("Please enter Password.");
		Password.focus();
		return false;
	}
	else if( Password.val().length < 6 ){
		alert("Passsword must be minimum 6 characters.");
		Password.focus();
		return false;
	}
	else if( trim_all( Confirm_Password.val() ) == "" ){
		alert("Please enter Confirm Password.");
		Confirm_Password.focus();
		return false;
	}	
	else if( Password.val() != Confirm_Password.val()){
		alert("Password & Confirm Password doesn't match.");
		Confirm_Password.focus();
		return false;
	}
	if( trim_all( UserType.val() ) == "" ){
		alert("Please select UserType.");
		UserType.focus();
		return false;
	}
	
}
</script>

<!-- display msg -->
<?php if(isset($msg) && !empty($msg)) { ?>
<p class="notice"><?php echo $msg; ?></p>
<?php } ?>
<h2>Registration</h2>
<form name="signup_form" action="<?php echo $this->config->item('base_url').'signup'; ?>" method="post" accept-charset="utf-8" onsubmit="return frmcheck(this);">
	<div class="form-group">
		<input class="form-control" type="text" placeholder="Name" name="name" id="name" value="<?php echo $this->input->post('name'); ?>" />
	</div>
	<div class="form-group">
		<input class="form-control" type="text" placeholder="Email" name="email" id="email" value="<?php echo $this->input->post('email'); ?>" />
	</div>
	<div class="form-group">
		<input class="form-control" type="password" placeholder="Password" name="password" value="" id="password" />
	</div>
	<div class="form-group">
		<input class="form-control" type="password" placeholder="Confirm Password" name="confirm_password" value="" id="confirm_password" />
	</div>
	<div class="form-group">
		<select class="form-control" name="userType" id="userType">
		<option value="">Select UserType</option>
		<?php foreach($userTypes as $type):?>
		<option <?php if($this->input->post('userType')==$type['id']){ echo 'selected="selected"'; } ?> value="<?php echo $type['id']; ?>"><?php echo ucfirst($type['user_type']); ?></option>
		<?php endforeach; ?>
		</select>
	</div>
	<input type="submit" value="Signup" class="btn btn-default" name="submit" id="submit" />
</form>