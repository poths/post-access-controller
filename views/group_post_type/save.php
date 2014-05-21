<?php

	//header
	echo $results['header_text'];
		
	echo '<div id="message" class="updated"><p>'.$results['master_line'].'</p><ul>';
	echo '<li><strong>Users added: </strong>'.$results['dtl_ins_cnt'].'</li>';
	echo '<li><strong>Users updated: </strong>'.$results['dtl_upd_cnt'].'</li>';
	echo '</ul></div>';
	
	?>
	<div class="form-control">
		<a href="<?php get_bloginfo('wpurl'); ?>/wp-admin/users.php?page=post-access-controller--main" class="button button-large button-primary">Back to Group Listing</a>
	</div>
	<?php
	
/* End of file */
/* Location: ./post-access-controller/views/group-save.php */