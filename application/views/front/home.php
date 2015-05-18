<div class="list-group">
	<?php if(!empty($posts)) { ?>
		<?php foreach($posts as $val) { ?>
			<a title="View Comments" href="<?php echo $this->config->item('base_url').'viewpost/'.$val['id']; ?>" class="list-group-item">
				<h4 class="list-group-item-heading"><?php echo wordwrap($val['title'], 8, "\n", true); ?></h4>
				<p class="list-group-item-text"><?php echo wordwrap($val['description'], 8, "\n", true); ?></p>
				<p class="list-group-item-text">Posted By : <?php echo $val['createdByName']; ?></p>
				<p class="list-group-item-text">Date : <?php echo $val['date_created']; ?></p>
			</a>
		<?php } ?>
		<?php echo $pager; ?>
	<?php } else { ?>
		<?php if($this->session->userdata('loggeduser')){ ?>
			<?php $loggeduser = unserialize($this->session->userdata('loggeduser'));
			if($loggeduser['user_type_id']==1){ ?>
				<h3>Be the first one to post!</h3>
				<a class="btn btn-info btn-lg" href="<?php echo $this->config->item('base_url').'createnewpost'; ?>" />New Post</a>
			<?php } else { ?>
				<h5>There are currently No Posts!<h5>
			<?php } ?>
		<?php } else { ?>
			<h3>Be the first one to post!</h3>
			<button type="button" class="btn btn-primary" onclick="javascript:window.location='<?php echo $this->config->item('base_url').'signup'; ?>'">Signup</button>
			OR
			<button type="button" class="btn btn-primary" onclick="javascript:window.location='<?php echo $this->config->item('base_url').'login'; ?>'">Login</button>
		<?php } ?>
	<?php } ?>
</div>