<?php
/**
 * API post single content
 *
 * @package understrap
 */

?>
	<div class="card" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
			$url = "http://api.songkick.com/api/3.0/search/artists.json?apikey=gcRqmcFu6yhD6dus";
			$query =  "&query=";
			$artist = get_field( "artist_name" );
			$apiPath = $url . $query . $artist;
			$response = wp_remote_get($apiPath);
			if (is_wp_error($response) || !isset($response['body'])) return; // bad response
			// the good stuff
			$body = wp_remote_retrieve_body($response);
			// decode the data
			$data = json_decode($body, true);

			// fetch artist id
			$artistId = $data["resultsPage"]["results"]["artist"][0]["id"];
			// artist on songkick
			$artistSongkick = $data["resultsPage"]["results"]["artist"][0]["uri"];
			
			// get artist gig data
			$gigData = "http://api.songkick.com/api/3.0/artists/" . $artistId . "/gigography.json?apikey=gcRqmcFu6yhD6dus";
			$gigDataResponse = wp_remote_get($gigData);
			if (is_wp_error($gigDataResponse) || !isset($gigDataResponse['body'])) return; // bad response
			// the good stuff
			$gigDataBody = wp_remote_retrieve_body($gigDataResponse);
			$gigDataData = json_decode($gigDataBody, true);
			// number of gigs
			$numberEntries = $gigDataData["resultsPage"]["totalEntries"];
			// first gig - venue
			$first = $gigDataData["resultsPage"]["results"]["event"][0];
			$firstGig = $first["start"]["date"];
			$firstVenue = $first["venue"]["displayName"];
			$firstCity = $first["venue"]["metroArea"]["displayName"];

			//get image from Skiddle
			$artistSearch = "https://www.skiddle.com/api/v1/artists/?name=" . $artist . "&api_key=e2e207702b2025c607f8eceff533f1e0";
			$artistSearchResponse = wp_remote_get($artistSearch);
			if (is_wp_error($artistSearchResponse) || !isset($artistSearchResponse['body'])) return; // bad response
			// the good stuff
			$artistSearchBody = wp_remote_retrieve_body($artistSearchResponse);
			$artistSearchData = json_decode($artistSearchBody, true);
			// get artist image
			$artistImg = $artistSearchData["results"][0]["imageurl"];
		?>
		
		<h5 class="card-header"><?php echo get_field( "artist_name" ) ?></h5>
		
		<!-- use featured image for thumbnail -->
		<?php 
			$thumb_id = get_post_thumbnail_id();
			$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
			$thumb_url = $thumb_url_array[0];
		?>
		<?php 
			if (has_post_thumbnail()){
				echo ('<img class="card-img-top" src="' . $thumb_url . '" alt="' . get_field( "artist_name" ) . '">');
			} elseif ($artistImg){
				echo ('<img class="card-img-top" src="' . $artistImg . '" alt="' . get_field( "artist_name" ) . '">');
			};
		?>

		<div class="card-body">
			<p><span class="label">Number of gigs: </span><span class="value"><?php echo $numberEntries; ?></span></p>
			<p><span class="label">First gig date: </span><span class="value"><?php echo date("jS F, Y", strtotime($firstGig)); ?></span></p>                               
			<p><span class="label">First venue: </span><span class="value"><?php echo $firstVenue . ", " . $firstCity; ?></span></p>
			<p><span class="label">ID: </span><span class="value"><?php echo $artistId; ?></span></p>
			<div class="text-center">
				<a href="<?php echo $artistSongkick; ?>" class="btn btn-outline-primary"><?php echo get_field( "artist_name" ); ?> on Songkick</a>
			</div>
		</div>
	</div>
