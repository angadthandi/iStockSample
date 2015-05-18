<?php echo 'Welcome '.ucfirst($loggeduser['name']).'!'; ?>
<?php if($loggeduser['user_type_id']==1){ ?>
	<p class="text-right">
		<a class="btn btn-info btn-lg" href="<?php echo $this->config->item('base_url').'createnewpost'; ?>" />New Post</a>
	</p>
<?php } ?>

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice; ?></p>
<?php endif;?>

<h2>My Post(s)</h2>
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
		No Posts Found!
	<?php } ?>
</div>