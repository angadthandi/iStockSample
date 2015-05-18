<script type="text/javascript">
function frmcheck(form) {
	/* VALIDATION STARTS HERE. */
	var Title = $("#title");
	var Description = $("#description");
	
	
	if( trim_all( Title.val() ) == "" ){
		alert("Please enter Title.");
		Title.focus();
		return false;
	}
	if( trim_all( Description.val() ) == "" ){
		alert("Please enter Description.");
		Description.focus();
		return false;
	}
	
}
</script>

<!-- display msg -->
<?php if(isset($msg) && !empty($msg)) { ?>
<p class="notice"><?php echo $msg; ?></p>
<?php } ?>
<h2>Create New Post</h2>
<form name="post_form" action="<?php echo $this->config->item('base_url').'createnewpost'; ?>" method="post" accept-charset="utf-8" onsubmit="return frmcheck(this);">
	<div class="form-group">
		<input class="form-control" type="text" placeholder="Title" name="title" id="title" value="<?php echo $this->input->post('title'); ?>" maxlength="200" />
	</div>
	<div class="form-group">
		<textarea class="form-control" placeholder="Description..." name="description" id="description"><?php echo $this->input->post('description'); ?></textarea>
	</div>
	<input type="submit" value="Create" class="btn btn-default" name="submit" id="submit" />
	<input type="button" value="Cancel" class="btn btn-default" name="cancel" id="cancel" onclick="javascript:window.location='<?php echo $this->config->item('base_url').'dashboard'; ?>'" />
</form>