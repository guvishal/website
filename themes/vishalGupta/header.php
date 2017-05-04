<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php if(is_home()) bloginfo('name'); else wp_title(''); ?></title>
    <?php wp_head(); ?>


    <meta name="viewport" content="width=device-width">
</head>
<body <?php body_class(); ?>>
<header class="clearfix">
	<h1>
	<a href="/" title="<?php echo bloginfo('name'); ?>">
		<img src="<?php echo get_theme_mod( 'header_logo' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">Vishal Gupta 
	</a>
	</h1>
	<div class="hamburger">
		<span></span>
		<span></span>
		<span></span>
	</div>
						<?php if ( has_nav_menu( 'primary' ) ) : ?>
							<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'twentysixteen' ); ?>">
								<?php
									wp_nav_menu( array(
										'theme_location' => 'primary',
										'menu_class'     => 'primary-menu',
									 ) );
								?>
							</nav><!-- .main-navigation -->
						<?php endif; ?>
</header>

<?php if(is_front_page() || is_home()){ ?>
<div class="banner">
<video autoplay loop>
  <source src="<?php echo get_theme_mod( 'header_video_banner' ); ?>" type="video/mp4">
  Your browser does not support HTML5 video.
</video>
<div class="banner__text">
	<h2><?php echo get_theme_mod( 'header_title' ); ?></h2>
	<span class="banner__text__tag"><?php echo get_theme_mod( 'header_subtitle' ); ?></span>
	<div class="banner__text__cta">
		<span class="banner__text__cta--first"><a href="<?php echo get_post_permalink(get_theme_mod( 'header_cta1_dropdown' )); ?>" title="<?php echo get_theme_mod( 'header_cta1' ); ?>"><?php echo get_theme_mod( 'header_cta1' ); ?></a></span>
		<span class="banner__text__cta--second"><a href="<?php echo get_theme_mod( 'header_cta2_dropdown' ); ?>" title="<?php echo get_theme_mod( 'header_cta2' ); ?>"><?php echo get_theme_mod( 'header_cta2' ); ?></a></span>
	</div>
</div>

<span class="banner__down"></span>
</div>

 <?php } ?>
        

        
     	
       
