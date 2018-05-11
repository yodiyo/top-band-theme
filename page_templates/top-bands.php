<?php
/**
 * Template Name: Top Bands
 *
 * Create archive for top bands
 * Uses Bootstrap's 4 flexbox utilities.
 *
 * @package understrap
 */
global $wp_query;
$id           = $wp_query->post->ID;
$page_content = get_post($id);
$page_content = apply_filters('the_content', $post->post_content);
$the_theme    = wp_get_theme();
get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="full-width-page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="row">

			<div class="col-md-12 content-area" id="primary">

				<main class="site-main" id="main" role="main">

                    <div class="jumbotron jumbotron-fluid">
                        <div class="container">
                            <h1 class="display-4">Top Bands</h1>
                            <p class="lead">A collection of bands and an overview of all their performances (as recorded on <a href="https://www.songkick.com/">Songkick)</a>.</p>
                            <cite>Created by Yorick Brown</cite>
                        </div>
                    </div>
                    <div class="card-columns">
                        <?php
                            $mypost = array( 'post_type' => 'top_bands', );
                            $query = new WP_Query( $mypost );
                        ?>
                        <?php while ( $query->have_posts() ) : $query->the_post();?>
                            
                            <?php get_template_part( 'loop-templates/content-single-api-post', 'page' ); ?>
                            
                        <?php endwhile; // end of the loop. ?>
                    </div>
				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .row end -->

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
