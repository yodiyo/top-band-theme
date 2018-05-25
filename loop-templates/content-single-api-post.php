<?php
/**
 * API post single content
 *
 * @package understrap
 */

?>
	<div class="card" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
			$url = "http://api.songkick.com/api/3.0/search/artists.json?apikey=[YOUR SONGKICK API KEY]";
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
			$artistId = get_field( "artist_id" ) ? get_field( "artist_id" ) : $data["resultsPage"]["results"]["artist"][0]["id"];
			
			// artist on songkick
			$artistSongkick = get_field( "songkick_api_url" ) ? get_field( "songkick_api_url" ) : $data["resultsPage"]["results"]["artist"][0]["uri"];
			
			// get artist gig data
			$gigData = "http://api.songkick.com/api/3.0/artists/" . $artistId . "/gigography.json?apikey=[YOUR SONGKICK API KEY]";
			$gigDataResponse = wp_remote_get($gigData);
			if (is_wp_error($gigDataResponse) || !isset($gigDataResponse['body'])) return; // bad response
			// the good stuff
			$gigDataBody = wp_remote_retrieve_body($gigDataResponse);
			$gigDataData = json_decode($gigDataBody, true);
			
			// number of gigs
			$numberEntries = get_field( "number_of_entries" ) ? get_field( "number_of_entries" ) : $gigDataData["resultsPage"]["totalEntries"];
			
			// first gig
			$first = $gigDataData["resultsPage"]["results"]["event"][0];
			// first gig date (pretty format for api data)
			$firstGig = get_field( "first_gig" ) ? get_field( "first_gig" ) : date("jS F, Y", strtotime($first["start"]["date"]));
			// first venue
			$firstCity = $first["venue"]["metroArea"]["displayName"];			
			$firstVenue = get_field( "first_venue" ) ? get_field( "first_venue" ) : $first["venue"]["displayName"] . ", " .  $firstCity;
			
			//get image from Skiddle
			$artistSearch = "https://www.skiddle.com/api/v1/artists/?name=" . $artist . "&api_key=[YOUR SKIDDLE API KEY]";
			$artistSearchResponse = wp_remote_get($artistSearch);
			if (is_wp_error($artistSearchResponse) || !isset($artistSearchResponse['body'])) return; // bad response
			// the good stuff
			$artistSearchBody = wp_remote_retrieve_body($artistSearchResponse);
			$artistSearchData = json_decode($artistSearchBody, true);
			
			// get artist image
			$artistImg = $artistSearchData["results"][0]["imageurl"];
		?>
		
		<h5 class="card-header"><?php echo $artist; ?></h5>
		
		<!-- use featured image for thumbnail -->
		<?php 
			$thumb_id = get_post_thumbnail_id();
			$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
			$thumb_url = $thumb_url_array[0];
		?>
		<?php 
			if (has_post_thumbnail()){
				echo ('<img class="card-img-top" src="' . $thumb_url . '" alt="' . $artist . '">');
			} elseif ($artistImg){
				echo ('<img class="card-img-top" src="' . $artistImg . '" alt="' . $artist . '">');
			};
		?>

		<div class="card-body">
			<p><span class="label">Number of gigs: </span><span class="value"><?php echo $numberEntries; ?></span></p>
			<p><span class="label">First gig date: </span><span class="value"><?php echo $firstGig; ?></span></p>                               
			<p><span class="label">First venue: </span><span class="value"><?php echo $firstVenue; ?></span></p>
			<p><span class="label">ID: </span><span class="value"><?php echo $artistId; ?></span></p>
			<div class="text-center">
				<a href="<?php echo $artistSongkick; ?>" class="btn btn-outline-primary"><?php echo $artist; ?> on Songkick</a>
			</div>
		</div>
	</div>
