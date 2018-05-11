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

		<?php
			$request = wp_remote_get( 'https://pippinsplugins.com/edd-api/products' );
			if( is_wp_error( $request ) ) {
				return false; // Bail early
			}
			$body = wp_remote_retrieve_body( $request );
			$data = json_decode( $body );
			if( ! empty( $data ) ) {
				
				echo '<ul>';
				foreach( $data->products as $product ) {
					echo '<li>';
						echo '<a href="' . esc_url( $product->info->link ) . '">' . $product->info->title . '</a>';
					echo '</li>';
				}
				echo '</ul>';
			}
		?>

		<?php
			$url = "http://api.songkick.com/api/3.0/search/artists.json?apikey=gcRqmcFu6yhD6dus&query=the rolling stones" ;
			$response = wp_remote_get($url);
			if (is_wp_error($response) || !isset($response['body'])) return; // bad response
			// the good stuff
			$body = wp_remote_retrieve_body($response);
			$http_code = wp_remote_retrieve_response_code( $response );
			print ($http_code);
			// decode the data
			$data = json_decode($body, true);
			// final remote data
			if( ! empty( $data ) ) {
				//artistId = $data["resultsPage"]["results"]["artist"][0]["id"];
				echo $data[0];
				foreach( $data->resultsPage as $result ){
					echo '<p>';
					echo  $result ;
					echo '</p>';
				}
			}

			$total = $data->resultsPage;
			if (isset($data->resultsPage->results->event) && is_array($data->resultsPage->results->event)) {
				$events = $data->resultsPage->results->event;
			} else {
				$events = array();
			}
			var_dump($data);
			echo ($events);
			foreach( $events as $event ){
				echo '<p>';
				echo  $event ;
				echo '</p>';
			}

			return array('events' => $events, 'total' => $total);

			

		?>

	</div>
