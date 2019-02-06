<?php 
/*
Template Name: Doors
*/
get_header();

	global $post;
  	$pattern = '/\[vc_gallery.+?]/m';
  	$image_pattern = '/(\d+,)+(\d+)/m';
  	$images_ids = null;
	
	if ( has_shortcode( $post->post_content, 'vc_gallery' ) ) { 
		preg_match($pattern, $post->post_content, $shortcode);
		preg_match($image_pattern, $shortcode[0], $images);
		$images_ids = explode(",", $images[0]);
	}


	function load_parent_filter(){

			$terms = get_terms( 'filter_category', array(
			    'hide_empty' => true,
			) );

		   array_walk($terms, function($term){
				if($term->parent === 0 ){
					echo "<li data-id=\"{$term->term_id}\">{$term->name}</li>";
				}
		   });

	}

	?>

	<div class="container gallery_container">
		<div class="row">
			<div class="filter-wrapper">
				<div class="filter-wrapper__parent door-filter">
					<ul>
						<li class="bold">All</li>
						<?php load_parent_filter(); ?>
					</ul>
				</div>
				<div class="filter-wrapper__child door-filter">

				</div>
			</div>
			<?php 
			
			
			 ?>
			<div id="load-door-images">
			</div>
		</div>
	</div>

<?php

 ?> 

<?php get_footer();?>
