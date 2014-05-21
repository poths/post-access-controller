<div class="wrap">
	<?php

	//header
	echo $pac['header_text'];
	
	//breadcrumbs
	echo $pac['breadcrumbs'];
	
	//decode the status
	if( $pac['group_master']->status_code == 'I' ):
		$active = "";
		$inactive = " selected";
	else:
		$active = " selected";
		$inactive = "";
	endif;
	
	//build the group form
	?>
	<h3>Post Access Controller</h3>
	<form action="users.php?page=post-access-controller--process" method="post">
		<table class="form-table">
			<?php
				foreach( $data['fields'] as $field ):
					echo $field;
				endforeach;
			?>
		</table>
		<div class="hide">
			<input type="hidden" name="post_id" value="<?php echo $pac['group_master']->ID; ?>" />
		</div>
		<div class="form-actions">
			<button type="submit" class="button button-large button-primary">Save</button>
			<a href="<?php get_bloginfo('wpurl'); ?>/wp-admin/users.php?page=post-access-controller--main" class="button button-large">Cancel</a>
		</div>
	</form>
	<?php
	
/* End of file */
/* Location: ./post-access-controller/views/group-edit.php */