
	
	<?php wp_footer(); ?>
	<footer>
		<div class="footer-wrapper">
									<?php if ( has_nav_menu( 'social' ) ) : ?>
								<?php
									wp_nav_menu( array(
										'theme_location' => 'social',
										'menu_class'     => 'social-menu',
									 ) );
								?>

						<?php endif; ?>

						<div class="contact-detail">
							<ul>
								<li><a href="tel:<?php echo get_theme_mod('footer_contact_no');?>" title="<?php echo get_theme_mod('footer_contact_no'); ?>"><?php echo get_theme_mod('footer_contact_no'); ?></a></li>
								<li><a href="mailto:<?php echo get_theme_mod('footer_email_id');?>" title="<?php echo get_theme_mod('footer_email_id'); ?>"><?php echo get_theme_mod('footer_email_id'); ?></a></li>
							</ul>
						</div>
		</div>
		<?php if(is_front_page() || is_home()){ ?>
		<div class="overlay start"><img src="<?php echo get_theme_mod( 'header_logo' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></div>
		<?php } ?>
	</footer>
</body>
</html>