<?php
	
	//localize everything down one level from the $data array
	extract( $data );

	if( is_array( $options ) && count( $options ) ):
	
		echo '<header>'.$list_label.'<button type="button" class="button button-small height-toggle">Show More</button></header>';
			
		echo '<div class="postaccesscontroller-checkbox-well height-standard" data-height="standard">';
		
			//echo '<div class="checkboxes-wrapper">';
						
			foreach( $options as $option ):
			
				echo '<label for="postaccesscontroller_meta_'.$type.'-'.$option->ID.'">';
				
					echo '<input type="checkbox" name="postaccesscontroller_meta_'.$type.'[]" id="postaccesscontroller_meta_'.$type.'-'.$option->ID.'" value="'.$option->ID.'"';
				
					if( is_array( $current ) && in_array( $option->ID, $current ) ):
						echo " checked";
					endif;
				
					echo '>'.$option->$label_field;
					
				echo '</label>';
				
			endforeach;	
			
			//echo '</div>';
			
		echo '</div>';
		
		if( $type == 'user' ):
			$not_type = 'group';
		elseif( $type == 'group' ):
			$not_type = 'user';
		endif;
		
		echo '<input type="hidden" id="postaccesscontroller_meta_'.$not_type.'" name="postaccesscontroller_meta_'.$not_type.'[]" value="">';
		
	endif;
	
/* End of file */
/* Location: ./post-access-controller/views/meta-group.php */