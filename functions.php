<?php 

add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles() {
	
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'));

    if ( is_rtl() ) 
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
   		
   	wp_enqueue_script( 'settings', get_stylesheet_directory_uri() . '/js/settings.js', array(), false, true);

   	wp_localize_script( 'settings', 'localized', 
			array( 
				'page_id' => get_the_ID(),
				'site_url' => get_template_directory_uri(),
				'ajax_url' => admin_url('admin-ajax.php')
			)
		);
}



/* customize login screen */
function cabinet_images_custom_login_page() {
    echo '<style type="text/css">
        .login h1 a { background-image:url("'. get_stylesheet_directory_uri().'/images/logo.png") !important; height: 100px !important; width: 100% !important; margin: 0 auto !important; background-size: contain !important; }
		h1 a:focus { outline: 0 !important; box-shadow: none; }
        body.login { background-image:url("'. get_stylesheet_directory_uri().'/images/banner.jpg") !important; background-repeat: no-repeat !important; background-attachment: fixed !important; background-position: center !important; background-size: cover !important; position: relative; z-index: 999;}
  		body.login:before { background-color: rgba(0,0,0,0.4); position: absolute; width: 100%; height: 100%; left: 0; top: 0; content: ""; z-index: -1; }
  		.login form {
  			background: rgba(255,255,255, 0.2) !important;
  		}
		.login form .input, .login form input[type=checkbox], .login input[type=text] {
			background: transparent !important;
			color: #ddd;
		}
		.login label {
			color: #DDD !important;
		}
		.login #login_error, .login .message {
			color: #ddd;
			margin-top: 20px;
			background: rgba(255,255,255, 0.2) !important;
		}
		#login {
		    padding: 7% 0 0;
		}
    </style>';
}
add_action('login_head', 'cabinet_images_custom_login_page', 99);
function cabinet_login_logo_url_title() {
 	return 'Business Simple';
}
add_filter( 'login_headertitle', 'cabinet_login_logo_url_title' );
function cabinet_login_logo_url() {
	return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'cabinet_login_logo_url' );



// FUNCTION FOR DOOR-------------------------------------------------

function load_doors_func(){
	$term_id = $_POST['term_id'];

	$child_filter = '';
	$children = get_term_children($term_id, "filter_category");
	if(is_array($children) && !empty($children)){
		foreach ($children as $child) {
			$term = get_term_by( 'id', $child, "filter_category");
			$child_filter .= "<li data-child=\"child\" data-id=\"{$term->term_id}\">{$term->name}</li>";
		}
	}

	$terms = get_terms( 'filter_category', array(
	    'hide_empty' => true,
	) );
	$term_ids = wp_list_pluck($terms, 'term_id');
	$term_id = $term_id ? $term_id : $term_ids;

	$posts_array = get_posts(
	    array(
	        'posts_per_page' => -1,
	        'post_type' => 'attachment',
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'filter_category',
	                'field' => 'term_id',
	                'terms' => $term_id,
	            )
	        )
	    )
	);

	$gallery = '<ul class="door_gallery">';


	foreach ($posts_array as $post) {
		$cat = get_the_terms($post->ID, 'filter_category');
		$names = wp_list_pluck($cat, 'name');
		$names_str = join(" / ", $names);
		$attachment_title = get_the_title($post->ID);
		$image_src = wp_get_attachment_image_src($post->ID, 'full');
		$gallery .= "<li><img src=\"{$image_src[0]}\"/><p class=\"image_title\"><strong>{$names_str}</strong> / {$attachment_title}</p></li>";
	}
	$gallery .= '</ul>';

	echo json_encode(array("child_filter" => $child_filter, "gallery" => $gallery));

	die();
}
add_action('wp_ajax_load_doors', 'load_doors_func');
add_action('wp_ajax_nopriv_load_doors', 'load_doors_func');



if ( ! function_exists( 'style_taxonomy_func' ) ) {

// Register Custom Taxonomy
function style_taxonomy_func() {

	$labels = array(
		'name'                       => 'Filter Category',
		'singular_name'              => 'Filter Category',
		'menu_name'                  => 'Filter Categorys',
		'all_items'                  => 'All Filter Categorys',
		'parent_item'                => 'Parent Filter Category',
		'parent_item_colon'          => 'Parent Filter Category:',
		'new_item_name'              => 'New Filter Category Name',
		'add_new_item'               => 'Add New Filter Category',
		'edit_item'                  => 'Edit Filter Category',
		'update_item'                => 'Update Filter Category',
		'view_item'                  => 'View Filter Category',
		'separate_items_with_commas' => 'Separate Filter Categorys with commas',
		'add_or_remove_items'        => 'Add or remove filter categorys',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Filter Categorys',
		'search_items'               => 'Search Filter Categorys',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No styles',
		'items_list'                 => 'Filter Categorys list',
		'items_list_navigation'      => 'Filter Categorys list navigation',
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'filter_category', array( 'attachment' ), $args );

}
add_action( 'init', 'style_taxonomy_func', 0 );

}
