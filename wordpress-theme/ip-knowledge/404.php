<?php
get_header();
?>

<main>
	<section class="not-found" aria-labelledby="not-found-title">
		<p class="not-found__code">404</p>
		<h1 class="not-found__title" id="not-found-title"><?php echo esc_html( ipk_copy( 'not_found_title' ) ); ?></h1>
		<p class="not-found__text"><?php echo esc_html( ipk_copy( 'not_found_text' ) ); ?></p>
		<a class="not-found__link" href="<?php echo esc_url( ipk_language_url( ipk_current_language() ) ); ?>"><?php echo esc_html( ipk_copy( 'not_found_link' ) ); ?></a>
	</section>
</main>

<?php
get_footer();
