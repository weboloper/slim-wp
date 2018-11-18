<?php

// function shortcode_function()
// {
// 	return    do_shortcode('[wpuf_form id="7"]');
// }
// add_shortcode( 'shortcode', 'shortcode_function' );

add_action('after_setup_theme', 'after_setup_theme_example');

if (! function_exists('after_setup_theme_example')) :

	function after_setup_theme_example() {
		global $wpdb;

 		// $wpdb->query("ALTER TABLE wp_users ADD facility_id INT(1) NOT NULL DEFAULT 1");

		// $row = $wpdb->get_results(  "SELECT * FROM INFORMATION_SCHEMA.COLUMNS
		// WHERE 
		// 	TABLE_SCHEMA = 'slim_wp' 
		// 	AND TABLE_NAME = '{$wpdb->prefix}_users' 
		// 	AND COLUMN_NAME = 'ID' "  );

		$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}_users ");

		if(!isset($row->facility_id)){
			$wpdb->query("ALTER TABLE wp_users ADD facility_id INT(11) NOT NULL DEFAULT 1");
		}

		if(!isset($row->job_id)){
			$wpdb->query("ALTER TABLE wp_users ADD job_id INT(11) NOT NULL DEFAULT 0");
		}
		
		if(!isset($row->section_id)){
			$wpdb->query("ALTER TABLE wp_users ADD section_id INT(11) NOT NULL DEFAULT 0");
		}
		 


	}
endif;


// function enqueue_my_scripts() {

//     // wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array( 'reset' ) );
// 	// wp_enqueue_style( 'reset', get_stylesheet_directory_uri() . '/reset.css' );

// 	// wp_enqueue_script( 'owl-carousel', get_stylesheet_directory_uri() . '/owl.carousel.js', array( 'jquery' ) );
// 	// wp_enqueue_script( 'theme-scripts', get_stylesheet_directory_uri() . '/website-scripts.js', array( 'owl-carousel', 'jquery' ), '1.0', true );

// 	wp_enqueue_style( 'select2-css', get_stylesheet_directory_uri() . '/assets/select2/select2.min.css' , array( 'select2-css' ) );
// 	wp_enqueue_script( 'select2-js', get_stylesheet_directory_uri() . '/assets/select2/select2.min.js', array( 'select2-js', 'jquery' ), '1.0', true );

// }
// add_action( 'admin_enqueue_scripts', 'enqueue_my_scripts' );



// LOADIN ADMIN STYLES
function load_custom_wp_admin_style() {
        wp_register_style( 'selec2_css', get_template_directory_uri() . '/assets/select2/select2.min.css', false, '1.0.0' );
        wp_enqueue_style( 'selec2_css' );

        wp_enqueue_script( 'selec2_js',  get_template_directory_uri() . '/assets/select2/select2.min.js' , array(), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );
    

function admin_inline_js(){
	echo "<script type='text/javascript'>\n";
	echo " $('.select2-select').select2()";
	echo "\n</script>";
}
add_action( 'admin_print_footer_scripts', 'admin_inline_js' );



// https://wordpress.stackexchange.com/questions/128622/how-do-i-create-a-relationship-between-two-custom-post-types
add_action( 'admin_init', 'add_meta_boxes' );
function add_meta_boxes() {
    add_meta_box( 'multiple_metabox', 'Multiple field relationship', 'multiple_field', 'post' );
}

function multiple_field() {
    global $post;

    $selected_relations = get_post_meta( $post->ID, '_field_meta_key', true );
    $selected_relations = is_array($selected_relations) ? $selected_relations : [] ;

    $all_options = get_posts( array(
        'post_type' => 'post',
        'numberposts' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC'
    ) );
    ?>
    <input type="hidden" name="verify_key" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
    <table class="form-table">
    <tr valign="top"><th scope="row">
    <label>Multiple field relationship Label</label></th>
    <td><select multiple name="select_value[]" class="select2-select" style="width:100%">
    <?php foreach ( $all_options as $single_option ) : ?>
        <option value="<?php echo $single_option->ID; ?>"<?php echo (in_array( $single_option->ID, $selected_relations )) ? ' selected="selected"' : ''; ?>><?php echo $single_option->post_title; ?></option>
    <?php endforeach; ?>
    </select></td></tr>
    </table>

    <?php 
}

add_action( 'save_post', 'save_multiple_field' );

function save_multiple_field( $post_id ) {

    // check post type
    if ( 'post' != get_post_type( $post_id ) )
        return $post_id;        

    // verify nonce
    if ( empty( $_POST['verify_key'] ) || !wp_verify_nonce( $_POST['verify_key'], basename( __FILE__ ) ) )
        return $post_id;

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;

    // check permissions
    if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;

    // save
    update_post_meta( $post_id, '_field_meta_key', array_map( 'intval', $_POST['select_value'] ) );

}

// echo "<pre>" ;
// var_dump($wp_filter);
// var_dump($wp_actions);
// echo "</pre>";