<?php
/**
 * API post single content
 *
 * @package understrap
 */

?>
	<div class="card" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h5 class="card-header"><?php $value = the_field( "artist_name" ); ?></h5>
		
		<!-- use featured image for thumbnail -->
		<?php 
			$thumb_id = get_post_thumbnail_id();
			$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
			$thumb_url = $thumb_url_array[0];
		?>
		<?php 
			if (has_post_thumbnail()){
			echo ('<img class="card-img-top" src="' . $thumb_url . '" alt="Card image cap">');
			}
		?>

		<div class="card-body">                               
			<p><span class="label">Number of gigs: </span><span class="value"><?php $value = the_field( "number_of_entries" ); ?></span></p>
			<p><span class="label">First gig date: </span><span class="value"><?php $value = the_field( "first_gig" ); ?></span></p>                               
			<p><span class="label">First venue: </span><span class="value"><?php $value = the_field( "first_venue" ); ?></span></p>
			<p><span class="label">ID: </span><span class="value"><?php $value = the_field( "artist_id" ); ?></span></p>
			<div class="text-center">
				<a href="<?php $value = the_field( "songkick_api_url" ); ?>" class="btn btn-outline-primary">Songkick API</a>
			</div>
		</div>
	</div>
