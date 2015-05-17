<div class="list-group">
	<?php if(!empty($posts)) { ?>
		<?php foreach($posts as $val) { ?>
			<a href="<?php echo $this->config->item('base_url').'viewpost/'.$val['id']; ?>" class="list-group-item">
				<h4 class="list-group-item-heading"><?php echo $val['title']; ?></h4>
				<p class="list-group-item-text"><?php echo $val['description']; ?></p>
				<p class="list-group-item-text">Posted By : <?php echo $val['createdByName']; ?></p>
				<p class="list-group-item-text">Date : <?php echo $val['date_created']; ?></p>
			</a>
		<?php } ?>
		<?php echo $pager; ?>
	<?php } else { ?>
		No Posts Found!
	<?php } ?>
</div>