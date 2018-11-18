<?php

// function shortcode_function()
// {
// 	return    do_shortcode('[wpuf_form id="7"]');
// }
// add_shortcode( 'shortcode', 'shortcode_function' );

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

add_action('after_setup_theme', 'after_setup_theme_example');


add_action( 'admin_init', 'add_meta_boxes' );
function add_meta_boxes() {
    add_meta_box( 'some_metabox', 'Movies Relationship', 'post', 'posts' );
}

function movies_field() {
    global $post;
    $selected_movies = get_post_meta( $post->ID, '_movies', true );
    $all_movies = get_posts( array(
        'post_type' => 'movies',
        'numberposts' => -1,
        'orderby' => 'post_title',
        'order' => 'ASC'
    ) );
    ?>
    <input type="hidden" name="movies_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
    <table class="form-table">
    <tr valign="top"><th scope="row">
    <label for="movies">Movies</label></th>
    <td><select multiple name="movies">
    <?php foreach ( $all_movies as $movie ) : ?>
        <option value="<?php echo $movie->ID; ?>"<?php echo (in_array( $movie->ID, $selected_movies )) ? ' selected="selected"' : ''; ?>><?php echo $movie->post_title; ?></option>
    <?php endforeach; ?>
    </select></td></tr>
    </table>

    <?php 
}

add_action( 'save_post', 'save_movie_field' );

function save_movie_field( $post_id ) {

    // only run this for series
    if ( 'series' != get_post_type( $post_id ) )
        return $post_id;        

    // verify nonce
    if ( empty( $_POST['movies_nonce'] ) || !wp_verify_nonce( $_POST['movies_nonce'], basename( __FILE__ ) ) )
        return $post_id;

    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;

    // check permissions
    if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;

    // save
    update_post_meta( $post_id, '_movies', array_map( 'intval', $_POST['movies'] ) );

}

// echo "<pre>" ;
// var_dump($wp_filter);
// var_dump($wp_actions);
// echo "</pre>";