<?php

    if( ! class_exists( 'WP_List_Table' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    }
    
    class PAC_List_Table extends WP_List_Table {
        
        public function __construct( $tablenav ){
            $this->tablenav = $tablenav;
            global $postaccesscontroller_statuses;
            $this->statuses = &$postaccesscontroller_statuses;
            parent::__construct();
        }

        function get_columns(){
          $columns = array(
            'cb'        => '<input type="checkbox" />',
            'post_title'    => 'Group',
            'ID' => 'ID',
            'post_status'      => 'Status',
            'user_count'    => 'User Count'
          );
          return $columns;
        }
        
        function prepare_items( $data ) {

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $per_page = $data['per_page'];
            $current_page = $this->get_pagenum();
            $this->process_bulk_action();
            $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
            $this->_column_headers = array($columns, $hidden, $sortable);
            $this->data = $data['table_data'];
            $total_items = $data['total_items'];
            $this->set_pagination_args( array(
                                                'total_items' => $total_items,
                                                'per_page'    => $per_page
                                            ) );
            $this->items = $this->data;
        
        }
        function extra_tablenav( $which ) {
            if ( $which == "top" ){
                echo $this->tablenav['top'];
            }
            if ( $which == "bottom" ){
                //The code that goes after the table is there
                //echo"Hi, I'm after the table";
            }
        }

        function get_sortable_columns() {
          $sortable_columns = array(
            'ID'  => array('ID',false),
            'post_title' => array('post_title',false),
            'post_status' => array('post_status',false),
            'user_count'   => array('user_count',false)
          );
          return $sortable_columns;
        }

        function get_bulk_actions() {
            $actions = array(
                            'archive'    => 'Archive'
            );
            return $actions;
        }
        function process_bulk_action() {

            require_once plugin_dir_path( __FILE__ ) . 'db.php';
            $pac_db     = new postaccesscontroller_db();

            //Detect when a bulk action is being triggered...
            if( 'archive' === $this->current_action() ) {

                $result = '<div id="message" class="updated"><p>Groups archived:</p><ul>';

                foreach( $_GET['post_id'] as $post_id ):
                    $results = $pac_db->pac_group_archive_process(array('post_id'=>$post_id));
                    $result .= '<li>'.$results['mstr_rslt'].'</li>';
                endforeach;
                $result .= '</ul></div>';

            }

            echo $result;

        }

        /* -------------------------------------------------------------------------------------------------------------
               COLUMNS
           ------------------------------------------------------------------------------------------------------------- */
        
        function column_default( $item, $column_name ) {
          switch( $column_name ) { 
            case 'ID':
            case 'post_title':
            case 'post_status':
            case 'user_count':
              return $item->$column_name;
            default:
              return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
          }
        }

        function column_cb($item) {
            return sprintf(
                '<input type="checkbox" name="%1$s[]" value="%2$s" />', 'ID', $item->ID
            );    
        }

        function column_post_title($item) {
          $actions = array(
                    'edit'      => sprintf('<a href="?page=%s&post_id=%s">Edit</a>','post-access-controller--edit',$item->ID),
                );

            if( $item->post_status == 'publish' ):
                $actions['delete'] = sprintf('<a href="?page=%s&post_id=%s">Archive</a>','post-access-controller--archive',$item->ID);
            endif;
        
          return sprintf('%1$s %2$s', $item->post_title, $this->row_actions($actions) );
        }

        function column_post_status( $item ){
            return $this->statuses[$item->post_status];
        }

        function column_user_count( $item ){
            return count( explode( '|', $item->post_content ) );
        }


                        
    }

/* End of file */
/* Location: ./post-access-controller/classes/list-table.php */