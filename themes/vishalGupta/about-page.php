<?php
/**
 * Template Name: About
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage VishalGupta
 * @since Vishal Gupta 1.0
 */

get_header(); 
$post_metadata = get_post_meta($post->ID);?>

<div class="about-banner">
	<h2>
		<?php echo get_the_title( );?>

		<span><?php echo $post_metadata['_SubTitle_key'][0]; ?></span>
	</h2>
	<figure class='about-pic'>
		<?php echo get_the_post_thumbnail(); ?>
	</figure>
</div>
<div id="primary" class="about-content-wrapper">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post();

		the_content(); 

		 endwhile;?>

	</main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_footer(); ?>
