<?php
/**
 * Template Name: Works
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
</div>
<div id="primary" class="content-wrapper">
	<main id="main" class="site-main" role="main">
	<ul class="works">
		<?php while ( have_posts() ) : the_post();

				$argsWorks = array(
			'post_type' => 'works',
			'post_status' => 'publish',
			'posts_per_page' => -1
			);
				$homepost = get_posts($argsWorks);
						foreach ($homepost as $post): setup_postdata($post);
		$post_metadata = get_post_meta($post->ID);
			?>
<li class="work">
	<figure>
	<a href="<?php echo get_the_permalink(); ?>">
		<?php the_post_thumbnail(); ?>
		</a>
	</figure>
	<h3><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title( ); ?></a></h3>
	<div class="work-detail">
		<?php echo substr(get_the_content(),0,200).'....'; ?>
	</div>
	<a href="<?php echo get_the_permalink(); ?>" title="Read More" class="readmore"> Read More</a>
</li>
			<?php
				endforeach;	

		 endwhile;?>
</ul>
	</main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_footer(); ?>
