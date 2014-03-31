<?php

    class postaccesscontroller_ui{

        /* ---------------------------------------------------------------------------------
                   FORMS
           --------------------------------------------------------------------------------- */

    	public function generate_checkbox_well( $args ){
            return $this->_form_input_checkbox_well( $args );
    	}

    	public function generate_form_table_line( $field_label, $field_type, $field_args ){

    		$function = '_form_input_'.$field_type;
    		$field_code = $this->$function( $field_args );

    		$return  = '<tr>';
    		$return .= '<th>'.$field_label.'</th>';
    		$return .= '<td class="'.$field_class.'">'.$field_code.'</td>';
    		$return .= '</tr>';
    		return $return;
    	}

    	private function _form_input_text( $args ){
    		extract( $args );
    		$return = "<input type='text' name='$name' class='$class' value='$current_value' />";
    		return $return;
    	}

    	private function _form_input_textarea( $args ){
    		extract( $args );
    		$return = "<textarea name='$name' class='$class'>$current_value</textarea>";
    		return $return;
    	}

    	private function _form_input_drop_down( $args ){
    		extract( $args );
    		$return  = "<select name='$name' class='$class' id='$id'>";
    		foreach( $values as $value => $label ):
    			$return .= "<option value='$value'";
    			if( $value == $current_value ):
    				$return .= ' selected';
    			endif;
    			$return .= ">$label</option>";
    		endforeach;
    		$return .= '</select>';

    		return $return;
    	}

    	private function _form_input_checkbox( $args ){

    		extract( $args );

    		$return = '';

			foreach( $options as $option ):
				$return .= "<div><label for='$name-".$option['value']."'>";
				if( $option['selected'] == 'Y' ):
					$checked = ' checked';
				else:
					$checked = '';
				endif;
				$return .= "<input type='checkbox' name='".$name."[]' id='$name-".$option['value']."' value='".$option['value']."' style='width: auto' $checked>";
				$return .= $option['label']."</label></div>";
			endforeach;

			return $return;

    	}

        private function _form_input_checkbox_well( $args ){

            extract( $args );

            $return = '<div class="postaccesscontroller-checkbox-well">';

            foreach( $options as $option ):
                $return .= "<label for='$name-".$option['value']."'>";
                if( $option['selected'] == 'Y' ):
                    $checked = ' checked';
                else:
                    $checked = '';
                endif;
                $return .= "<input type='checkbox' name='".$name."[]' id='$name-".$option['value']."' value='".$option['value']."' $checked>";
                $return .= $option['label']."</label>";
            endforeach;

            $return .= '</div>';

            return $return;

        }        

        /* ---------------------------------------------------------------------------------
                   MISC
           --------------------------------------------------------------------------------- */

        public function generate_extra_tablenav( $data ){
            if( is_array( $data ) ){
                $return = '<ul class="subsubsub">';
                $return .= '<li><strong>Filters:</strong></li>';
                foreach( $data as $nav ):
                    $return .= '<li><a href="'.$nav['href'].'">'.$nav['label'].' <span class="count">('.$nav['count'].')</span></a></li>';
                endforeach;
                $return .= '</ul><!-- /.subsubsub -->';
            }
            return $return;
        }

        public function generate_breadcrumbs( $data ){

            $return = '<div class="breadcrumbs"><ul>';

            foreach( $data as $crumb ):
                $return .= '<li>';
                if( empty( $crumb['href'] ) ):
                    $return .= $crumb['label'];
                else:
                    $return .= '<a href="'.$crumb['href'].'">';
                    $return .= $crumb['label'];
                    $return .= '</a>';
                endif; 
                $return .= '</li>';
            endforeach;

            $return .= '</ul></div>';
            return $return;
    
        }

	}