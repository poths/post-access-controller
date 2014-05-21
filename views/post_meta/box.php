
<?php

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'postaccesscontroller_sec_field', 'postaccesscontroller_sec_field_nonce' );

	// Display the form, using the current value.
	echo '<table class="form-table post-visibility--';
	echo get_option('enable_post_visibility');
	echo '">';
	echo $ctrl_type;
	echo '<tr>';
	echo '<td colspan="2" class="postaccesscontroller-details" data-spinner-src="/wp-admin/images/spinner-2x.gif">';
	echo '<img src="/wp-admin/images/spinner-2x.gif" />';
	echo '</td>';
	echo '</tr>';
	echo $msg_type;
	echo '<tr>';
	echo '<td colspan="2" class="postaccesscontroller-noacs-msg">';
    	echo '<div class="postaccesscontroller-noacs-std-msg '.$std_msg_class.'"><strong>Default message:</strong><br>'.get_option('access_denied_message').'</div>';
		echo '<div class="postaccesscontroller-noacs-custom-msg '.$custom_msg_class.'"><strong>Custom message:</strong><br>';
		    echo '<textarea id="postaccesscontroller-noacs-custom-msg" name="postaccesscontroller_noacs_custom_msg">'.$data['postaccesscontroller_noacs_custom_msg'].'</textarea>';
    	echo '</div>';
	echo '</td>';
	echo '</tr>';
    echo '</table>';

/* End of file */
/* Location: ./post-access-controller/meta-box.php */