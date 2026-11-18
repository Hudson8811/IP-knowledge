<?php
get_header();

while ( have_posts() ) :
	the_post();
	$post_id       = get_the_ID();
	$sidebar_title = ipk_field( 'sidebar_title', $post_id, get_the_title() );
	$sidebar_intro = ipk_field( 'sidebar_intro', $post_id, 'General Overview by' );
	$authors       = ipk_field( 'article_authors', $post_id, array() );
	$pdf_url       = ipk_field( 'pdf_url', $post_id );
	if ( is_array( $pdf_url ) && isset( $pdf_url['url'] ) ) {
		$pdf_url = $pdf_url['url'];
	} elseif ( is_numeric( $pdf_url ) ) {
		$pdf_url = wp_get_attachment_url( (int) $pdf_url );
	}
	$contacts      = ipk_field( 'article_contacts', $post_id, array() );
	$article_posts = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'ASC', 'lang' => ipk_current_language() ) );
	if ( ! $article_posts ) {
		$article_posts = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'ASC' ) );
	}
	$legacy_intro_rows = array_filter( array_map( 'trim', explode( '|', $sidebar_intro ) ) );
	$sidebar_intro_label = $legacy_intro_rows ? reset( $legacy_intro_rows ) : '';
	$default_contacts = array(
		array( 'name' => 'nikolay simoyanov', 'role' => 'counsel', 'profile_url' => 'https://ivanyan.partners/en/people/NikolaySimoyanov/', 'email' => 'n.simoyanov@iplf.ru', 'phone' => '+7 495 647 0046', 'photo' => 0 ),
		array( 'name' => 'mark rovinskiy', 'role' => 'partner', 'profile_url' => 'https://ivanyan.partners/en/people/MarkRovinskiy/', 'email' => 'm.rovinskiy@iplf.ru', 'phone' => '+7 495 647 0046', 'photo' => 0 ),
	);
	if ( ! $contacts ) { $contacts = $default_contacts; }
?>
<main>
	<div class="article-layout">
		<button class="article-menu-backdrop" type="button" aria-label="<?php echo esc_attr( ipk_copy( 'close_menu' ) ); ?>" data-article-menu-backdrop hidden></button>
		<aside class="article-sidebar">
			<button class="article-menu-toggle" type="button" aria-controls="article-menu" aria-expanded="false" data-article-menu-toggle>
				<span class="article-menu-toggle__line"></span><span class="article-menu-toggle__line"></span><span class="article-menu-toggle__line"></span>
				<span class="visually-hidden"><?php echo esc_html( ipk_copy( 'open_menu' ) ); ?></span>
			</button>
			<h1 class="article-sidebar__title"><?php echo nl2br( esc_html( $sidebar_title ) ); ?></h1>
			<?php if ( $authors ) : ?>
				<p class="article-sidebar__meta"><?php echo esc_html( $sidebar_intro_label ); ?><?php foreach ( $authors as $author ) : ?><?php $author_name = isset( $author['name'] ) ? $author['name'] : ''; $author_url = isset( $author['profile_url'] ) ? $author['profile_url'] : ''; $author_role = isset( $author['role'] ) ? $author['role'] : ''; ?><?php if ( $author_name ) : ?><br><?php if ( $author_url ) : ?><a class="article-sidebar__author" href="<?php echo esc_url( $author_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $author_name ); ?></a><?php else : ?><?php echo esc_html( $author_name ); ?><?php endif; ?><?php echo $author_role ? ' ' . esc_html( $author_role ) : ''; ?><?php endif; ?><?php endforeach; ?></p>
			<?php elseif ( $legacy_intro_rows ) : ?>
				<p class="article-sidebar__meta"><?php foreach ( $legacy_intro_rows as $index => $row ) : ?><?php if ( $index > 0 ) : ?><br><?php endif; ?><?php echo esc_html( $row ); ?><?php endforeach; ?></p>
			<?php endif; ?>
			<?php if ( $pdf_url ) : ?>
				<a class="article-sidebar__pdf" href="<?php echo esc_url( $pdf_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( ipk_copy( 'pdf' ) ); ?></a>
			<?php endif; ?>
			<nav class="article-menu" id="article-menu" aria-label="<?php echo esc_attr( ipk_copy( 'articles' ) ); ?>" data-article-menu>
				<button class="article-menu__close" type="button" aria-label="<?php echo esc_attr( ipk_copy( 'close_menu' ) ); ?>" data-article-menu-close><img class="article-menu__close-icon" src="<?php echo esc_url( ipk_asset( 'img/close.svg' ) ); ?>" width="21" height="21" alt="" aria-hidden="true"></button>
				<p class="article-menu__title"><span class="article-menu__title-desktop"><?php echo esc_html( ipk_copy( 'all_articles' ) ); ?></span><span class="article-menu__title-mobile"><?php echo esc_html( ipk_copy( 'articles' ) ); ?></span></p>
				<ul class="article-menu__list">
					<?php foreach ( $article_posts as $menu_post ) : ?>
						<?php $menu_title = get_post_meta( $menu_post->ID, 'article_menu_title', true ) ?: get_the_title( $menu_post ); ?>
						<li class="article-menu__item"><a class="article-menu__link<?php echo $menu_post->ID === $post_id ? ' article-menu__link--active' : ''; ?>" href="<?php echo esc_url( get_permalink( $menu_post ) ); ?>"><?php echo esc_html( $menu_title ); ?></a></li>
					<?php endforeach; ?>
				</ul>
				<div class="article-menu__switch"><p class="article-menu__switch-title"><?php echo esc_html( ipk_copy( 'switch_to' ) ); ?></p><a class="article-menu__switch-link" href="https://ivanyan.partners/en/"><?php echo esc_html( ipk_copy( 'firm_name' ) ); ?></a></div>
			</nav>
		</aside>
		<div class="article-content">
			<div class="text-style">
				<?php if ( function_exists( 'have_rows' ) && have_rows( 'article_content', $post_id ) ) : ?>
					<?php while ( have_rows( 'article_content', $post_id ) ) : the_row(); ?>
						<?php $block_content = (string) get_sub_field( 'content' ); ?>
						<?php if ( '' !== trim( $block_content ) ) : ?>
							<?php if ( 'quote' === get_row_layout() ) : ?><div class="quote"><?php endif; ?>
							<?php echo wp_kses_post( apply_filters( 'the_content', $block_content ) ); ?>
							<?php if ( 'quote' === get_row_layout() ) : ?></div><?php endif; ?>
						<?php endif; ?>
					<?php endwhile; ?>
				<?php endif; ?>
			</div>
			<section class="article-contacts" id="contacts">
				<h2 class="article-contacts__title"><?php echo esc_html( ipk_copy( 'contacts' ) ); ?></h2>
				<div class="article-contacts__grid">
					<?php foreach ( $contacts as $index => $contact ) :
						$photo_id = isset( $contact['photo'] ) ? (int) $contact['photo'] : 0;
						$photo    = $photo_id ? wp_get_attachment_image_url( $photo_id, 'large' ) : ipk_asset( 'img/' . ( 0 === $index ? 'nikolay-simoyanov.png' : 'mark-rovinskiy.jpg' ) );
						$link     = ! empty( $contact['profile_url'] ) ? $contact['profile_url'] : '#';
					?>
						<article class="article-contacts__card">
							<a class="article-contacts__photo-frame" href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener"><img class="article-contacts__photo" src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $contact['name'] ); ?>"></a>
							<h3 class="article-contacts__name"><?php echo esc_html( $contact['name'] ); ?></h3>
							<?php if ( ! empty( $contact['role'] ) ) : ?><p class="article-contacts__role"><?php echo esc_html( $contact['role'] ); ?></p><?php endif; ?>
							<?php if ( ! empty( $contact['email'] ) || ! empty( $contact['phone'] ) ) : ?><p class="article-contacts__details"><?php if ( ! empty( $contact['email'] ) ) : ?><a href="mailto:<?php echo antispambot( esc_attr( $contact['email'] ) ); ?>"><?php echo antispambot( esc_html( $contact['email'] ) ); ?></a><?php endif; ?><?php if ( ! empty( $contact['email'] ) && ! empty( $contact['phone'] ) ) : ?><br><?php endif; ?><?php if ( ! empty( $contact['phone'] ) ) : ?><a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $contact['phone'] ) ); ?>"><?php echo esc_html( $contact['phone'] ); ?></a><?php endif; ?></p><?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			</section>
		</div>
	</div>
</main>
<?php endwhile; get_footer(); ?>
