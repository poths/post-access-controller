<?php

    class postaccesscontroller_db{

        public function __construct(){
            global $wpdb;
            $this->wpdb = &$wpdb;
            global $postaccesscontroller_statuses;
            $this->statuses = &$postaccesscontroller_statuses;
        }

        /*
        * Saves the post access controller "group" post type
        */
        public function pac_group_form_process( $data ){

            $post_data = array(
                'post_title'     => $data['post_title'],
                'post_content'   => implode( '|', array_values( $data['post_content'] ) ),
                'post_status'    => $data['post_status'],
                'post_type'      => 'pstacsctrlr_grp'
            );

            //are we adding a new post?
            if( empty( $data['post_id'] ) ):

            	//this will return an object with an error if there is a problem
            	//or the new ID if it worked
                $return          = wp_insert_post( $post_data, true );

            	//check what came back
                if( is_object( $return ) ){

                	//capture the errors
                    foreach( $return->errors as $error ):
                        if( count( $error ) > 0 ):
                            $errors .= implode('<br>',$error);
                        endif;
                    endforeach;

                    //return those errors for display
                    $results = array( 'error'            => $errors
                                     );

                //everything went alright
                }else{

                	//set this so we can just do the "save users" part with the same variable
                	//whether we added a new post or updated an existing one
                	$return_id = $return;

                    $results = array( 'ID'               => $return_id
                                     ,'result'           => 'Group "' . $data['post_title'] . '" created successfully'
                                     ,'txn_type'         => 'INS'
                                     );
                }

            //or updating an existing one?
            else:
                $post_data['ID'] = $data['post_id'];
                $return_id       = wp_update_post( $post_data );
                if( $return_id == 0 ){
                    $results = array( 'ID'               => $data['ID']
                                     ,'error'            => 'Something happened that was not what we wanted (pac_group_form_process)'
                                     );
                }else{
                    $results = array( 'ID'               => $return_id
                                     ,'result'           => 'Group "' . $data['post_title'] . '" saved successfully'
                                     ,'txn_type'         => 'UPD'
                                     );
                }
            endif;

			//we want multiple records for the meta user so we delete all of them and re-write the currently indicated ones
			delete_post_meta( $return_id, 'postaccesscontroller_group_user' );

        	//now that we've saved the group we need to save each user to post meta for that ID
        	foreach( array_values( $data['post_content'] ) as $user ):

				add_post_meta( $return_id, 'postaccesscontroller_group_user', $user );

			endforeach;

            return $results;

        }

        /*
        * Marks the "group" post as trash
        */
		public function pac_group_archive_process( $data ){

            $post_data = array(
                'ID'             => $data['post_id'],
                'post_status'    => 'trash'
            );
            $return_id       = wp_update_post( $post_data );
            if( $return_id == 0 ){
                $results = array( 'ID'               => $data['ID']
                                 ,'error'            => 'Something happened that was not what we wanted (pac_group_archive_process)'
                                 );
            }else{
                $results = array( 'ID'               => $return_id
                                 ,'result'           => 'Group archived successfully'
                                 ,'txn_type'         => 'UPD'
                                 );
            }

            return $results;

        }

        public function group_master_lkup( $data ){

            //localize
            extract( $data );

            $return['group_master']     = get_post( $post_id );

            if( $include_users == 'Y' ):

                //create and (more importantly) cache the array of current users in the group
                $current_users          = get_post_meta( $post_id, 'postaccesscontroller_group_user' );

                //start by getting ALL users
                $users                  = get_users();

                //go through and create an array of all users with their ID, their display name and a selected indicator
                foreach( $users as $user ):

                    //are they in the current group
                    if( in_array( $user->ID, $current_users ) ):
                        $selected = 'Y';
                    else:
                        $selected = 'N';
                    endif;

                    $return['users'][] = array( 'value'    => $user->ID
                                               ,'label'    => $user->display_name
                                               ,'selected' => $selected );

                endforeach;

            endif;

            return $return;
        }

        public function meta_options_lkup( $data ){

            if( $data['type'] == 'user' ):

                $options    = get_users();

            elseif( $data['type'] == 'group' ):

                $options    = $this->group_masters_lkup();

            endif;

            return $options;

        }

        public function user_groups_lkup( $data ){

            //localize
            extract( $data );

            $group_masters        = $this->group_masters_lkup();

            foreach( $group_masters as $group ):

                if( in_array( $user_id, get_post_meta( $group->ID, 'postaccesscontroller_group_user' ) ) ):
                    $selected = 'Y';
                else:
                    $selected = 'N';
                endif;

                $return[]         = array( 'value'    => $group->ID
                                          ,'label'    => $group->post_title
                                          ,'selected' => $selected );
            endforeach;

            return $return;
        }

        private function group_masters_lkup( $data = array() ){

            $defaults = array(
                           'post_type'      => 'pstacsctrlr_grp'
                          ,'post_status'    => array_keys( $this->statuses )
                          ,'orderby'        => 'title'
                          ,'posts_per_page' => -1
                          );

            //merge the defaults with the incoming data but do the data second so it overwrites any defaults
            $group_masters    = get_posts( array_merge( $defaults, $data ) );

            return $group_masters;
        }

        /*
        * Saving the post access controller data that is part of the user profile form
        */
        public function pac_user_form_process( $data ){

            $user_id          = $data['user_id'];
            $requested_groups = $data['post_id'];

            //get all the groups and the "selected" indicator
            $group_masters              = $this->user_groups_lkup(array('user_id'=>$user_id));

            //loop through those
            foreach( $group_masters as $group ):

                //check to see if our current user is already in that array
                if( $group['selected'] == 'Y' ):

                    //if they are then we should check if they should STAY in that array
                    if( !in_array( $group['value'], $requested_groups ) ):

                        //so the user is in a group but now they are not according to the requested group data
                        //so remove them and then re-save that group
                        $this->pac_grp_single_user_upd( $group['value'], $user_id, 'REMOVE' );

                    endif;

                //if they are NOT in the current group
                else:

                    //check to see if they should be now
                    if( in_array( $group['value'], $requested_groups ) ):

                        //add them
                        $this->pac_grp_single_user_upd( $group['value'], $user_id, 'ADD' );

                    endif;

                endif;

            endforeach;

        }

        private function pac_grp_single_user_upd( $group_id, $user_id, $prcs_type ){

            //get all the "user" meta records
            $users = get_post_meta( $group_id, 'postaccesscontroller_group_user' );

            //are we adding or removing?
            if( $prcs_type == 'ADD' ):

            	//does the user already exist?
                if( in_array( $user_id, $users ) ):

                    $return['rslt_code'] = 'SUCCESS';
                    $return['rslt_desc'] = 'No action needed';

                //guess not so we should add them
                else:

                	//this shouldn't really ever return false because we're not setting the unique parm to true but never hurts to check
                    if( add_post_meta( $group_id, 'postaccesscontroller_group_user', $user_id ) ):
    	                $return['rslt_code'] = 'SUCCESS';
	                    $return['rslt_desc'] = 'User added';
                	else:
    	                $return['rslt_code'] = 'ERROR';
	                    $return['rslt_desc'] = 'User was not able to be added successfully';
            		endif;

                endif;

            elseif( $prcs_type == 'REMOVE' ):

                //try to find the key for this user (ie see if they are already in the group)
                $key = array_search($user_id, $users);

            	//if they aren't then we don't need to do anything
                if( $key === false ):

                    $return['rslt_code'] = 'SUCCESS';
                    $return['rslt_desc'] = 'No action needed';

                //guess they are so we need to remove them
                else:

                    //just delete that user's meta record
                    if( delete_post_meta( $group_id, 'postaccesscontroller_group_user', $user_id ) ):
    	                $return['rslt_code'] = 'SUCCESS';
	                    $return['rslt_desc'] = 'User removed';
                	else:
    	                $return['rslt_code'] = 'ERROR';
	                    $return['rslt_desc'] = 'User was not able to be removed successfully';
            		endif;

                endif;

            endif;

        }

        public function group_listing_data_lkup( $data ){

            //run this here so we can get the total count
            $return['total_items'] = count( $this->group_masters_lkup() );

            //probably add more logic before doing this one to sort, filter, etc.
            $return['display_data'] = $this->group_masters_lkup();
            return $return;

        }

        public function pac_grp_mstr_sts_cnt_lkup(){

            //query for each status
            foreach( $this->statuses as $status => $label ):

                $args = array( 'post_type' => 'pstacsctrlr_grp','post_status'      => $status );

                $groups = $this->group_masters_lkup($args);

                $status_counts[$status] = count($groups);

            endforeach;

            $groups = $this->group_masters_lkup();

            $status_counts['all'] = count($groups);

            return $status_counts;
        }

        public function post_access_allow_check( $post_obj ){

            if( get_post_meta( $post_obj->ID, 'postaccesscontroller_ctrl_type', true ) == 'user' ){
                if( is_user_logged_in() ):
                    $users = get_post_meta( $post_obj->ID, 'postaccesscontroller_meta_user' );
                    if( in_array( get_current_user_id(), $users ) ):
                        return TRUE;
                    else:
                        return FALSE;
                    endif;
                else:
                    return FALSE;
                endif;
            }
            if( get_post_meta( $post_obj->ID, 'postaccesscontroller_ctrl_type', true ) == 'group' ){
                if( is_user_logged_in() ):
                    foreach( get_post_meta( $post_obj->ID, 'postaccesscontroller_meta_group' ) as $grp_post_id ):
                        $users = get_post_meta( $grp_post_id, 'postaccesscontroller_group_user' );
                        if( in_array( get_current_user_id(), $users ) ):
                            return TRUE;
                        endif;
                    endforeach;
                    return FALSE;
                else:
                    return FALSE;
                endif;
            }
            return TRUE;

        }

    }


/* End of file */
/* Location: ./post-access-controller/classes/db.php */