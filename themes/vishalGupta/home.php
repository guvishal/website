<?php
/**
* Template Name: Home page
*
* @package vishalGupta
* @subpackage Vishal_Gupta
* @since Vishal Gupta 1.0
*/

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
// Start the loop.
		while ( have_posts() ) : the_post();


		$argsHome = array(
			'post_type' => 'home',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'post_status' => 'publish',
			'posts_per_page' => -1
			);

		$homepost = get_posts($argsHome);
		foreach ($homepost as $hpost): setup_postdata($hpost);
		$post_metadata = get_post_meta($hpost->ID);
		$homecond = $post_metadata['_homechoice_key'][0];

		if($homecond == "Post"){
			?>
			<div class="post-content">
				<div class="post-wrapper">
					<h3>My Works</h3>
					<div class="flexslider">
					<ul class="slides">

						<?php
						$args = array(
							'post_type' => $post_metadata['_posttype_key'][0],
							'post_status' => 'publish',
							'posts_per_page' => 3
							);
						$resouce = get_posts($args);
						foreach ($resouce as $post): setup_postdata($post);
						?>					<li>
						<figure>
							<?php
							if(has_post_thumbnail()){
								the_post_thumbnail();
							} else{
								echo '<a href="'. get_permalink().'" title="'.get_the_title().'""><img src="'. get_option('default_image').'" alt="blog"></a>';
							}

							?>	
						</figure>
						<div class="work-content">
							<article>
								<h4><?php echo '<a href="'. get_permalink().'" title="'.get_the_title().'"">'.get_the_title().'</a>';?></h4>

								<div><?php echo substr(get_the_content(),0,150).'....'; ?></div>
								<a href="<?php echo get_the_permalink(); ?>" title="Read More" class="read_more">Read More</a>
							</article>
						</div>

					</a>					</li>
					<?php
					endforeach;	
					wp_reset_query();	
					?>				

				</ul>
				</div>
				<?php
				if($post_metadata['_postCTA_key'][0]){
					?>
					<a href="<?php echo $post_metadata['_postCTAlink_key'][0];  ?>" title="<?php echo $post_metadata['_postCTA_key'][0];  ?>"> <?php echo $post_metadata['_postCTA_key'][0];  ?></a>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}else if($homecond == "About"){
		?>
		<div class="about-content">
			<div class="about-wrapper">
				<h3>About Me</h3>
				<?php if($post_metadata['_aboutImage_key'][0]){ ?>
				<figure>
					<img src="<?php echo $post_metadata['_aboutImage_key'][0]; ?>" alt="Profile Img">
				</figure>					
				<?php }?>

				<div class="about-content">
					<?php echo wpautop($post_metadata['_aboutText_key'][0]); ?>
				</div>
				<?php
				if($post_metadata['_aboutCTA_key'][0]){
					?>
					<a href="<?php echo $post_metadata['_aboutCTAlink_key'][0];  ?>" title="<?php echo $post_metadata['_aboutCTA_key'][0];  ?>"> <?php echo $post_metadata['_aboutCTA_key'][0];  ?></a>
					<?php
				}
				?>
			</div>
		</div>
		<?php } else if ($homecond == "Technology") { ?>
		<div class="tech-content">
			<div class="tech-wrapper">
				<div class="text">
					<h3>Technologies</h3>
				</div>

				<figure><img src="<?php echo $post_metadata['_techc_key'][0]; ?>" alt="techonology"></figure>

				<?php
				if($post_metadata['_postCTA_key'][0]){
					?>
					<a href="<?php echo $post_metadata['_postCTAlink_key'][0];  ?>" title="<?php echo $post_metadata['_postCTA_key'][0];  ?>"> <?php echo $post_metadata['_postCTA_key'][0];  ?></a>
					<?php
				}
				?>
			</div>
		</div>

		<?php }
		endforeach;		

		endwhile;
		?>

	</main><!-- .site-main -->


</div><!-- .content-area -->

<?php get_footer(); ?>
