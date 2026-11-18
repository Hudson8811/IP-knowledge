	<footer class="site-footer">
		<div class="site-footer__inner">
			<div class="site-footer__brand">
				<p class="site-footer__title"><img class="site-footer__logo" src="<?php echo esc_url( ipk_asset( 'img/footer-logo.svg' ) ); ?>" width="302" height="20" alt="Ivanyan &amp; Partners"><span><?php echo esc_html( ipk_copy( 'footer_hub' ) ); ?></span></p>
				<p class="site-footer__tagline"><?php echo esc_html( ipk_copy( 'footer_tagline' ) ); ?></p>
			</div>
			<nav class="site-footer__nav" aria-label="Footer navigation">
				<?php wp_nav_menu( array( 'theme_location' => ipk_footer_menu_location(), 'container' => false, 'items_wrap' => '%3$s', 'depth' => 1, 'fallback_cb' => 'ipk_footer_menu_fallback' ) ); ?>
			</nav>
			<p class="site-footer__copyright">© Ivanyan and Partners, <?php echo esc_html( gmdate( 'Y' ) ); ?></p>
		</div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
