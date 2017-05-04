<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage vishalGupta
 * @since 1.0
 * @version 1.0
 */

get_header(); 
$post_metadata = get_post_meta($post->ID);
?>

<div class="wrap">
	<div id="primary" class="content">
		<main id="main" class="site-main" role="main">

			<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();
?>
					<div class="about-banner">
	<h2>
		<?php echo get_the_title( );?>

		<span><?php echo $post_metadata['_subTitle_key'][0]; ?></span>
	</h2>
</div>

<div class="wrapper-fig">
<figure>
<?php the_post_thumbnail(); ?>
</figure>
	
</div>
<div class="wrapper">
	<a href="<?php echo $post_metadata['_postCTAlink_key'][0]; ?>" title="<?php echo $post_metadata['_postCTA_key'][0]; ?>" class="launch-website" target="_blank"><?php echo $post_metadata['_postCTA_key'][0]; ?></a>
	<div class="work-content">
	<?php the_content(); ?>
	</div>
</div>
<?php

				endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer();
