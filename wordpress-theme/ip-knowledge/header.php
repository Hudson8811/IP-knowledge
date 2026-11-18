<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( ipk_asset( 'img/favicon-32x32.png' ) ); ?>">
	<?php wp_head(); ?>
</head>
<body>
<?php wp_body_open(); ?>
<div class="site">
	<header class="site-header">
		<div class="site-header__inner">
			<a class="site-header__logo" href="<?php echo esc_url( ipk_language_url( ipk_current_language() ) ); ?>" aria-label="<?php echo esc_attr( ipk_copy( 'home_label' ) ); ?>">
				<img src="<?php echo esc_url( ipk_asset( 'img/logo.svg' ) ); ?>" width="239" height="42" alt="Knowledge hub by Ivanyan and Partners">
			</a>
			<div class="language-switcher" role="group" aria-label="Language switcher">
				<?php foreach ( array( 'zh' => '中文', 'en' => 'EN' ) as $language => $label ) : ?>
					<?php if ( $language === ipk_current_language() ) : ?>
						<span class="language-switcher__item language-switcher__item--active" lang="<?php echo esc_attr( $language ); ?>" aria-current="page"><?php echo esc_html( $label ); ?></span>
					<?php else : ?>
						<a class="language-switcher__item" href="<?php echo esc_url( ipk_current_language_url( $language ) ); ?>" lang="<?php echo esc_attr( $language ); ?>" hreflang="<?php echo esc_attr( $language ); ?>"><?php echo esc_html( $label ); ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</header>
