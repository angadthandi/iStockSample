<div class="list-group">
	<?php if(!empty($users)) { ?>
		<?php foreach($users as $val) { ?>
			<a href="javascript:void(0);" class="list-group-item">
				<h4 class="list-group-item-heading"><?php echo ucfirst($val['name']); ?></h4>
				<p class="list-group-item-text"><?php echo $val['email']; ?></p>
				<p class="list-group-item-text"><?php echo ucfirst($val['userType']); ?></p>
				<p class="list-group-item-text">Join Date : <?php echo $val['date_created']; ?></p>
			</a>
		<?php } ?>
		<?php echo $pager; ?>
	<?php } else { ?>
		No User(s) Found!
	<?php } ?>
</div>