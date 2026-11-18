<?php
get_header();
?>
<main>
	<div class="article-layout">
		<div class="article-content"><div class="text-style">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); the_content(); endwhile; else : ?><p><?php esc_html_e( 'No content found.', 'ip-knowledge' ); ?></p><?php endif; ?>
		</div></div>
	</div>
</main>
<?php get_footer(); ?>
