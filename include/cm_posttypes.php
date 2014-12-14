<?php

/* ***********************************************************************************************
**
**
**	Curriculum custom post type
**
**	content type for curriculums with independant menu item
**
**
*********************************************************************************************** */
add_action('init', 'curriculum_register');

function curriculum_register() {

	$labels = array(
		'name' => _x('Curriculum', 'post type general name'),
		'singular_name' => _x('curriculum', 'curriculum'),
		'add_new' => _x('Add New', 'curriculum'),
		'add_new_item' => __('Add New Curriculum'),
		'edit_item' => __('Edit Curriculum'),
		'new_item' => __('New Curriculum'),
		'view_item' => __('View Curriculum'),
		'search_items' => __('Search Curriculum'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => get_stylesheet_directory_uri() . '/images/curriculum.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail', 'custom-fields'),
		'taxonomies' => array('cur_category')
	  );

	register_post_type( 'curriculum' , $args );
	flush_rewrite_rules();
}

add_action('init', 'cycle_register');

function cycle_register() {

	$labels = array(
		'name' => _x('Cycle', 'post type general name'),
		'singular_name' => _x('Cycle', 'cycle'),
		'add_new' => _x('Add New Cycle', 'cycle'),
		'add_new_item' => __('Add New Cycle'),
		'edit_item' => __('Edit Cycle'),
		'new_item' => __('New Cycle'),
		'view_item' => __('View Cycles'),
		'search_items' => __('Search Cycles'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => 'edit.php?post_type=curriculum',
		'query_var' => true,
		'menu_icon' => get_stylesheet_directory_uri() . '/images/curriculum.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title'),
	  );

	register_post_type( 'cycle' , $args );
	flush_rewrite_rules();
}

// Taxonomy for curriculum!

add_action( 'init', 'create_curriculum_taxonomies', 0 );

function create_curriculum_taxonomies() {

    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name' => _x( 'Curriculum Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Categories' ),
        'all_items' => __( 'All Categories' ),
        'parent_item' => __( 'Parent Category' ),
        'parent_item_colon' => __( 'Parent Category:' ),
        'edit_item' => __( 'Edit Category' ),
        'update_item' => __( 'Update Category' ),
        'add_new_item' => __( 'Add New Category' ),
        'new_item_name' => __( 'New Categoy Name' ),
    );

    register_taxonomy( 'cur_category', array( 'curriculum' ), array(
        'hierarchical' => true,
        'labels' => $labels, /* NOTICE: Here is where the $labels variable is used */
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'cur_category' ),
    ));
}





// Add columns to post manage page
add_filter('manage_edit-curriculum_columns', 'curr_columns');
function curr_columns($columns) {
    $columns['cycle'] = 'Cycle';
	unset($columns['tags']);
    return $columns;
}
add_action('manage_posts_custom_column',  'curr_show_columns');

function curr_show_columns($name) {
    global $post;
    switch ($name) {
        case 'cycle':
            $cycle = get_field('cycle', $post->ID);
			if($cycle){
				$cy_string = '';
				foreach($cycle as $cy_id) $cy_string .= get_the_title($cy_id).', ';
				$cy_string = trim($cy_string, ', ');
				echo $cy_string;
			} // if cycle
    } // switch
	//echo  '<pre>'.print_r(get_post_meta($post->ID), true).'</pre>';
}
