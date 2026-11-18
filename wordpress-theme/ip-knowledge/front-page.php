<?php
get_header();

$hero_title       = ipk_field( 'hero_title', get_the_ID(), get_the_title() );
$hero_subtitle    = ipk_field( 'hero_subtitle', get_the_ID() );
$hero_description = ipk_field( 'hero_description', get_the_ID() );
$hero_mobile      = ipk_field( 'hero_description_mobile', get_the_ID() );
$hero_article     = (int) ipk_field( 'hero_link', get_the_ID() );
$hero_url         = $hero_article ? ipk_translation_url( $hero_article, ipk_current_language() ) : '#articles';
$cards            = ipk_field( 'home_cards', get_the_ID(), array() );
?>
<main>
	<section class="main-banner" aria-labelledby="main-banner-title">
		<img class="main-banner__map" src="<?php echo esc_url( ipk_asset( 'img/russia-map-banner.svg' ) ); ?>" alt="" aria-hidden="true">
		<a class="main-banner__link" href="<?php echo esc_url( $hero_url ); ?>">
			<h1 class="main-banner__title" id="main-banner-title"><?php echo nl2br( esc_html( $hero_title ) ); ?></h1>
			<?php if ( $hero_subtitle ) : ?><p class="main-banner__subtitle"><?php echo esc_html( $hero_subtitle ); ?></p><?php endif; ?>
			<?php if ( $hero_description ) : ?><div class="main-banner__description main-banner__description--desktop"><?php echo wp_kses_post( $hero_description ); ?><span class="arrow-link" aria-hidden="true"><img class="arrow-link__icon" src="<?php echo esc_url( ipk_asset( 'img/read-more.svg' ) ); ?>" width="24" height="24" alt=""></span></div><?php endif; ?>
			<?php if ( $hero_mobile ) : ?><p class="main-banner__description main-banner__description--mobile"><?php echo esc_html( $hero_mobile ); ?> <span class="arrow-link" aria-hidden="true"><img class="arrow-link__icon" src="<?php echo esc_url( ipk_asset( 'img/read-more.svg' ) ); ?>" width="24" height="24" alt=""></span></p><?php endif; ?>
		</a>
	</section>
	<?php if ( $cards ) : ?>
		<section class="articles-preview" id="articles" aria-label="<?php echo esc_attr( ipk_copy( 'articles' ) ); ?>">
			<?php foreach ( $cards as $index => $card ) :
				$article_id = isset( $card['article'] ) ? (int) $card['article'] : 0;
				$icon_id    = isset( $card['icon'] ) ? (int) $card['icon'] : 0;
				$icon       = $icon_id ? wp_get_attachment_image_url( $icon_id, 'full' ) : '';
				$url        = $article_id ? ipk_translation_url( $article_id, ipk_current_language() ) : '#';
			?>
				<a class="article-card" href="<?php echo esc_url( $url ); ?>">
					<?php if ( $icon ) : ?>
						<img class="article-card__icon" src="<?php echo esc_url( $icon ); ?>" alt="">
					<?php endif; ?>
					<div class="article-card__body">
						<h2 class="article-card__title"><?php echo esc_html( $card['title'] ); ?></h2>
						<p class="article-card__description"><?php echo esc_html( $card['description'] ); ?><span class="article-card__arrow arrow-link" aria-hidden="true"><img class="arrow-link__icon" src="<?php echo esc_url( ipk_asset( 'img/read-more.svg' ) ); ?>" width="24" height="24" alt=""></span></p>
					</div>
				</a>
			<?php endforeach; ?>
		</section>
	<?php endif; ?>
</main>
<?php get_footer(); ?>
