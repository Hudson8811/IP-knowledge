<?php
/**
 * Theme bootstrap for IP Knowledge.
 *
 * Long-form legal copy is maintained in the normal WordPress editor. ACF
 * controls the surrounding presentation data: hero, cards, sidebar, files and contacts.
 */
defined( 'ABSPATH' ) || exit;

function ipk_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array(
		'footer_en' => 'Footer menu — English',
		'footer_zh' => 'Footer menu — Chinese',
	) );
}
add_action( 'after_setup_theme', 'ipk_setup' );

function ipk_document_title( $title ) {
	if ( is_singular() ) {
		$singular_title = get_the_title( get_queried_object_id() );

		if ( $singular_title ) {
			return $singular_title;
		}
	}

	return $title;
}
add_filter( 'pre_get_document_title', 'ipk_document_title' );

function ipk_tinymce_color_palette( $settings ) {
	$colors = array(
		'000000', 'Black', '993300', 'Burnt orange', '333300', 'Dark olive', '003300', 'Dark green',
		'003366', 'Dark azure', '000080', 'Navy blue', '333399', 'Indigo', '333333', 'Very dark gray',
		'800000', 'Maroon', 'FF6600', 'Orange', '808000', 'Olive', '008000', 'Green',
		'008080', 'Teal', '0000FF', 'Blue', '666699', 'Grayish blue', '808080', 'Gray',
		'FF0000', 'Red', 'FF9900', 'Amber', '99CC00', 'Yellow green', '339966', 'Sea green',
		'33CCCC', 'Turquoise', '3366FF', 'Royal blue', '800080', 'Purple', '969696', 'Medium gray',
		'FF00FF', 'Magenta', 'FFCC00', 'Gold', 'FFFF00', 'Yellow', '00FF00', 'Lime',
		'00FFFF', 'Aqua', '00CCFF', 'Sky blue', '993366', 'Red violet', 'C0C0C0', 'Silver',
		'FF99CC', 'Pink', 'FFCC99', 'Peach', 'FFFF99', 'Light yellow', 'CCFFCC', 'Pale green',
		'CCFFFF', 'Pale cyan', '99CCFF', 'Light sky blue', 'CC99FF', 'Plum', 'FFFFFF', 'White',
		'F04E00', 'Firm orange',
	);

	$settings['textcolor_map']  = wp_json_encode( $colors );
	$settings['textcolor_rows'] = 6;

	return $settings;
}
add_filter( 'tiny_mce_before_init', 'ipk_tinymce_color_palette' );

// Keep the built-in classic post editor without installing an additional plugin.
add_filter( 'use_block_editor_for_post', '__return_false', 10 );

// Change only the labels displayed in the WordPress admin sidebar.
function ipk_rename_admin_sidebar_items() {
	global $menu;

	foreach ( $menu as $position => $item ) {
		if ( isset( $item[2] ) && 'edit.php' === $item[2] ) {
			$menu[ $position ][0] = 'Статьи';
		}

		if ( isset( $item[2] ) && 'edit.php?post_type=page' === $item[2] ) {
			$menu[ $position ][0] = 'Главная';
		}
	}
}
add_action( 'admin_menu', 'ipk_rename_admin_sidebar_items', 999 );

function ipk_footer_menu_location() {
	return 'en' === ipk_current_language() ? 'footer_en' : 'footer_zh';
}

function ipk_footer_menu_link_attributes( $attributes, $item, $args ) {
	if ( isset( $args->theme_location ) && 0 === strpos( $args->theme_location, 'footer_' ) ) {
		$attributes['class'] = trim( ( isset( $attributes['class'] ) ? $attributes['class'] . ' ' : '' ) . 'site-footer__link' );
	}

	return $attributes;
}
add_filter( 'nav_menu_link_attributes', 'ipk_footer_menu_link_attributes', 10, 3 );

function ipk_footer_menu_fallback() {
	$language = ipk_current_language();
	?>
	<a class="site-footer__link" href="<?php echo esc_url( ipk_language_url( $language ) ); ?>#articles"><?php echo esc_html( ipk_copy( 'articles' ) ); ?></a>
	<a class="site-footer__link" href="https://ivanyan.partners/en/"><?php echo esc_html( ipk_copy( 'about' ) ); ?></a>
	<a class="site-footer__link" href="#"><?php echo esc_html( ipk_copy( 'privacy' ) ); ?></a>
	<a class="site-footer__link" href="#"><?php echo esc_html( ipk_copy( 'cookies' ) ); ?></a>
	<?php
}

function ipk_create_footer_menu( $name, $location, $items ) {
	$menu = wp_get_nav_menu_object( $name );
	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $name );
		if ( is_wp_error( $menu_id ) ) {
			return;
		}

		foreach ( $items as $item ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => $item['title'],
				'menu-item-url'    => $item['url'],
				'menu-item-status' => 'publish',
				'menu-item-type'   => 'custom',
			) );
		}
	} else {
		$menu_id = (int) $menu->term_id;
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locations[ $location ] ) ) {
		$locations[ $location ] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}

function ipk_seed_footer_menus() {
	if ( get_option( 'ipk_footer_menus_seeded' ) ) {
		return;
	}

	ipk_create_footer_menu( 'Footer — English', 'footer_en', array(
		array( 'title' => 'articles', 'url' => ipk_language_url( 'en' ) . '#articles' ),
		array( 'title' => 'about us', 'url' => 'https://ivanyan.partners/en/' ),
		array( 'title' => 'privacy policy', 'url' => '#' ),
		array( 'title' => 'cookies policy', 'url' => '#' ),
	) );
	ipk_create_footer_menu( 'Footer — Chinese', 'footer_zh', array(
		array( 'title' => '文章', 'url' => ipk_language_url( 'zh' ) . '#articles' ),
		array( 'title' => '关于我们', 'url' => 'https://ivanyan.partners/en/' ),
		array( 'title' => '隐私政策', 'url' => '#' ),
		array( 'title' => 'Cookie 政策', 'url' => '#' ),
	) );

	update_option( 'ipk_footer_menus_seeded', 1, false );
}
add_action( 'init', 'ipk_seed_footer_menus', 30 );

function ipk_enqueue_assets() {
	$version = wp_get_theme()->get( 'Version' );
	$menu_script = 'assets/js/article-menu.js';
	wp_enqueue_style( 'ip-knowledge', get_stylesheet_uri(), array(), $version );
	wp_enqueue_script( 'ip-knowledge-menu', get_theme_file_uri( $menu_script ), array(), filemtime( get_theme_file_path( $menu_script ) ), true );
}
add_action( 'wp_enqueue_scripts', 'ipk_enqueue_assets' );

function ipk_asset( $path ) {
	return get_theme_file_uri( 'assets/' . ltrim( $path, '/' ) );
}

/**
 * The article-card illustrations are trusted theme assets. Permit SVG uploads
 * only for administrators so they can be managed through the ACF media fields.
 */
function ipk_allow_admin_svg_uploads( $mimes ) {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}

	return $mimes;
}
add_filter( 'upload_mimes', 'ipk_allow_admin_svg_uploads' );

function ipk_current_language() {
	return function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : 'zh';
}

function ipk_language_url( $language ) {
	return function_exists( 'pll_home_url' ) ? pll_home_url( $language ) : ( 'en' === $language ? home_url( '/en/' ) : home_url( '/' ) );
}

/**
 * Returns the equivalent language version of the current article where one
 * exists. A language home page is the deliberate fallback for an untranslated
 * article, rather than sending a visitor to the article in the wrong language.
 */
function ipk_current_language_url( $language ) {
	if ( is_singular( 'post' ) && function_exists( 'pll_get_post' ) ) {
		$translation_id = pll_get_post( get_queried_object_id(), $language );

		if ( $translation_id && 'publish' === get_post_status( $translation_id ) ) {
			return get_permalink( $translation_id );
		}
	}

	return ipk_language_url( $language );
}

function ipk_translation_url( $post_id, $language ) {
	if ( function_exists( 'pll_get_post' ) ) {
		$translation_id = pll_get_post( $post_id, $language );
		if ( $translation_id && 'publish' === get_post_status( $translation_id ) ) {
			return get_permalink( $translation_id );
		}
	}
	return ipk_language_url( $language );
}

function ipk_field( $field_name, $post_id = false, $default = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $field_name, $post_id ) : get_post_meta( $post_id ?: get_the_ID(), $field_name, true );
	return ( null === $value || false === $value || '' === $value ) ? $default : $value;
}

function ipk_copy( $key ) {
	$language = ipk_current_language();
	$copy     = array(
		'zh' => array(
			'home_label' => '法律知识中心首页', 'articles' => '文章', 'all_articles' => '全部文章', 'open_menu' => '打开文章菜单', 'close_menu' => '关闭文章菜单', 'switch_to' => '切换至', 'pdf' => 'PDF', 'contacts' => '联系我们', 'footer_hub' => '/ 法律知识中心', 'footer_tagline' => '俄罗斯复杂投资项目的首选律师事务所', 'about' => '关于我们', 'privacy' => '隐私政策', 'cookies' => 'Cookie 政策', 'read_more' => '阅读全文', 'firm_name' => '伊万扬与合伙人律师事务所（俄罗斯）', 'not_found_title' => '页面未找到', 'not_found_text' => '您访问的页面不存在或已被移除。', 'not_found_link' => '返回首页',
		),
		'en' => array(
			'home_label' => 'Knowledge hub home', 'articles' => 'articles', 'all_articles' => 'all articles', 'open_menu' => 'Open article menu', 'close_menu' => 'Close article menu', 'switch_to' => 'switch to', 'pdf' => 'PDF', 'contacts' => 'contacts', 'footer_hub' => '/ knowledge hub', 'footer_tagline' => 'first choice law firm for complex investments in Russia', 'about' => 'about us', 'privacy' => 'privacy policy', 'cookies' => 'cookies policy', 'read_more' => 'read full article', 'firm_name' => 'Ivanyan and Partners (Russia)', 'not_found_title' => 'Page not found', 'not_found_text' => 'The page you are looking for does not exist or has been moved.', 'not_found_link' => 'Go to home',
		),
	);
	return isset( $copy[ $language ][ $key ] ) ? $copy[ $language ][ $key ] : $copy['en'][ $key ];
}

function ipk_register_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }
	acf_add_local_field_group( array(
		'key' => 'group_ipk_home', 'title' => 'Knowledge Hub — home page', 'position' => 'normal', 'style' => 'seamless',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ) ) ),
		'fields' => array(
			array( 'key' => 'field_ipk_hero_title', 'label' => 'Hero title', 'name' => 'hero_title', 'type' => 'text', 'required' => 1 ),
			array( 'key' => 'field_ipk_hero_subtitle', 'label' => 'Hero subtitle', 'name' => 'hero_subtitle', 'type' => 'text' ),
			array( 'key' => 'field_ipk_hero_description', 'label' => 'Hero description (desktop)', 'name' => 'hero_description', 'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0 ),
			array( 'key' => 'field_ipk_hero_mobile', 'label' => 'Hero description (mobile)', 'name' => 'hero_description_mobile', 'type' => 'textarea', 'rows' => 3 ),
			array( 'key' => 'field_ipk_hero_link', 'label' => 'Hero article', 'name' => 'hero_link', 'type' => 'post_object', 'post_type' => array( 'post' ), 'return_format' => 'id', 'ui' => 1 ),
			array( 'key' => 'field_ipk_cards', 'label' => 'Article cards', 'name' => 'home_cards', 'type' => 'repeater', 'layout' => 'block', 'button_label' => 'Add article card', 'sub_fields' => array(
				array( 'key' => 'field_ipk_card_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text', 'required' => 1 ),
				array( 'key' => 'field_ipk_card_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3 ),
				array( 'key' => 'field_ipk_card_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'thumbnail' ),
				array( 'key' => 'field_ipk_card_article', 'label' => 'Article', 'name' => 'article', 'type' => 'post_object', 'post_type' => array( 'post' ), 'return_format' => 'id', 'ui' => 1 ),
			) ),
		),
	) );
	acf_add_local_field_group( array(
		'key' => 'group_ipk_article', 'title' => 'Knowledge Hub — article settings', 'position' => 'normal', 'style' => 'seamless',
		'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'post' ) ) ),
		'fields' => array(
			array( 'key' => 'field_ipk_article_content', 'label' => 'Article content', 'name' => 'article_content', 'type' => 'flexible_content', 'button_label' => 'Add block', 'layouts' => array(
				array( 'key' => 'layout_ipk_text', 'name' => 'text', 'label' => 'Text block', 'display' => 'block', 'sub_fields' => array(
					array( 'key' => 'field_ipk_text_content', 'label' => 'Text', 'name' => 'content', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'full', 'media_upload' => 1 ),
				) ),
				array( 'key' => 'layout_ipk_quote', 'name' => 'quote', 'label' => 'Quote block', 'display' => 'block', 'sub_fields' => array(
					array( 'key' => 'field_ipk_quote_content', 'label' => 'Quote', 'name' => 'content', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'full', 'media_upload' => 1 ),
				) ),
			) ),
			array( 'key' => 'field_ipk_sidebar_title', 'label' => 'Sidebar title', 'name' => 'sidebar_title', 'type' => 'textarea', 'rows' => 2 ),
			array( 'key' => 'field_ipk_menu_title', 'label' => 'Menu title', 'name' => 'article_menu_title', 'type' => 'text', 'instructions' => 'Short title for the article list in the sidebar.' ),
			array( 'key' => 'field_ipk_sidebar_intro', 'label' => 'Sidebar intro', 'name' => 'sidebar_intro', 'type' => 'text', 'default_value' => 'General Overview by' ),
			array( 'key' => 'field_ipk_article_authors', 'label' => 'Authors', 'name' => 'article_authors', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Add author', 'sub_fields' => array(
				array( 'key' => 'field_ipk_author_name', 'label' => 'Full name', 'name' => 'name', 'type' => 'text', 'required' => 1 ),
				array( 'key' => 'field_ipk_author_url', 'label' => 'Profile URL', 'name' => 'profile_url', 'type' => 'url' ),
				array( 'key' => 'field_ipk_author_role', 'label' => 'Role', 'name' => 'role', 'type' => 'text' ),
			) ),
			array( 'key' => 'field_ipk_pdf_url', 'label' => 'PDF file', 'name' => 'pdf_url', 'type' => 'file', 'return_format' => 'url', 'mime_types' => 'pdf' ),
			array( 'key' => 'field_ipk_contacts', 'label' => 'Contacts', 'name' => 'article_contacts', 'type' => 'repeater', 'layout' => 'table', 'button_label' => 'Add contact', 'sub_fields' => array(
				array( 'key' => 'field_ipk_contact_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text', 'required' => 1 ),
				array( 'key' => 'field_ipk_contact_role', 'label' => 'Role', 'name' => 'role', 'type' => 'text' ),
				array( 'key' => 'field_ipk_contact_photo', 'label' => 'Photo', 'name' => 'photo', 'type' => 'image', 'return_format' => 'id', 'preview_size' => 'thumbnail' ),
				array( 'key' => 'field_ipk_contact_link', 'label' => 'Profile URL', 'name' => 'profile_url', 'type' => 'url' ),
				array( 'key' => 'field_ipk_contact_email', 'label' => 'Email', 'name' => 'email', 'type' => 'email' ),
				array( 'key' => 'field_ipk_contact_phone', 'label' => 'Phone', 'name' => 'phone', 'type' => 'text' ),
			) ),
		),
	) );
}
add_action( 'acf/init', 'ipk_register_acf_fields' );

/**
 * Move the original editor content into the Article content flexible field once.
 * Existing quote wrappers become separate quote blocks; all remaining markup is
 * kept in text blocks in its original order.
 */
function ipk_article_content_to_blocks( $content ) {
	$blocks = array();
	$offset = 0;
	$pattern = '#<div\\b(?=[^>]*\\bclass\\s*=\\s*(["\\\'])[^"\\\']*\\bquote\\b[^"\\\']*\\1)[^>]*>(.*?)</div>#is';

	if ( preg_match_all( $pattern, $content, $quotes, PREG_OFFSET_CAPTURE ) ) {
		foreach ( $quotes[0] as $index => $quote ) {
			$before = trim( substr( $content, $offset, $quote[1] - $offset ) );
			if ( '' !== $before ) {
				$blocks[] = array( 'acf_fc_layout' => 'text', 'content' => $before );
			}

			$quote_content = trim( $quotes[2][ $index ][0] );
			if ( '' !== $quote_content ) {
				$blocks[] = array( 'acf_fc_layout' => 'quote', 'content' => $quote_content );
			}

			$offset = $quote[1] + strlen( $quote[0] );
		}
	}

	$remaining = trim( substr( $content, $offset ) );
	if ( '' !== $remaining ) {
		$blocks[] = array( 'acf_fc_layout' => 'text', 'content' => $remaining );
	}

	return $blocks;
}

function ipk_migrate_article_content_to_builder() {
	if ( ! function_exists( 'update_field' ) || get_option( 'ipk_chinese_article_content_migrated' ) ) {
		return;
	}

	$articles = array_filter( array_map( 'get_post', array( 27, 29 ) ) );

	foreach ( $articles as $article ) {
		$content = trim( (string) $article->post_content );
		if ( '' === $content || get_post_meta( $article->ID, 'article_content', true ) ) {
			continue;
		}

		$blocks = ipk_article_content_to_blocks( $content );
		if ( ! $blocks ) {
			continue;
		}

		update_field( 'field_ipk_article_content', $blocks, $article->ID );
		wp_update_post( array( 'ID' => $article->ID, 'post_content' => '' ) );
	}

	update_option( 'ipk_chinese_article_content_migrated', 1, false );
}
add_action( 'init', 'ipk_migrate_article_content_to_builder', 25 );

function ipk_seed_post( $postarr ) {
	$existing = get_page_by_path( $postarr['post_name'], OBJECT, $postarr['post_type'] );
	return $existing ? (int) $existing->ID : (int) wp_insert_post( $postarr );
}

function ipk_article_html( $file ) {
	$path = get_theme_file_path( 'data/' . $file );
	if ( ! file_exists( $path ) ) { return ''; }
	return wp_kses_post( str_replace( 'assets/img/', get_theme_file_uri( 'assets/img/' ), (string) file_get_contents( $path ) ) );
}

function ipk_seed_initial_content() {
	if ( get_option( 'ipk_content_seeded' ) || ! function_exists( 'pll_set_post_language' ) ) { return; }
	$home_zh = ipk_seed_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => '法律知识中心', 'post_name' => 'home', 'post_content' => '' ) );
	$home_en = ipk_seed_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Knowledge hub', 'post_name' => 'knowledge-hub', 'post_content' => '' ) );
	$guide = ipk_seed_post( array( 'post_type' => 'post', 'post_status' => 'publish', 'post_title' => 'Doing Business in Russia 2026', 'post_name' => 'doing-business-russia-2026', 'post_content' => ipk_article_html( 'doing-business.html' ) ) );
	$currency = ipk_seed_post( array( 'post_type' => 'post', 'post_status' => 'publish', 'post_title' => 'Currency Control in Russia 2026', 'post_name' => 'currency-control-russia-2026', 'post_content' => ipk_article_html( 'currency-control.html' ) ) );
	pll_set_post_language( $home_zh, 'zh' ); pll_set_post_language( $home_en, 'en' ); pll_set_post_language( $guide, 'en' ); pll_set_post_language( $currency, 'en' );
	pll_save_post_translations( array( 'zh' => $home_zh, 'en' => $home_en ) );
	update_option( 'show_on_front', 'page' ); update_option( 'page_on_front', $home_zh );
	$zh_cards = array(
		array( 'title' => '外汇管制', 'description' => '俄罗斯的外汇管制法规历来复杂且严格，规则繁多，违规行为可能受到严厉处罚。', 'icon' => 0, 'article' => $currency ),
		array( 'title' => '数据保护', 'description' => '俄罗斯的数据保护与隐私基本原则及监管框架，与中国、欧盟或美国等地区存在显著差异。', 'icon' => 0, 'article' => $currency ),
		array( 'title' => '制裁', 'description' => '俄罗斯的制裁和反制裁制度持续变化，交易结构需要提前评估。', 'icon' => 0, 'article' => $guide ),
		array( 'title' => '劳动关系', 'description' => '进入俄罗斯市场时，劳动关系与移民合规需要同步规划。', 'icon' => 0, 'article' => $guide ),
		array( 'title' => '税务', 'description' => '投资结构与业务模式应提前考虑俄罗斯税务规则。', 'icon' => 0, 'article' => $guide ),
		array( 'title' => '争议解决', 'description' => '合同安排应明确适用法律、争议解决方式及执行风险。', 'icon' => 0, 'article' => $guide ),
	);
	$en_cards = array(
		array( 'title' => 'Currency control', 'description' => 'Russian currency control regulations have historically been both complicated and strict, with complex rules and severe penalties for non-compliance.', 'icon' => 0, 'article' => $currency ),
		array( 'title' => 'Data protection', 'description' => 'Russia’s general data protection and privacy principles and regulatory framework differ substantially from the PRC, EU and US.', 'icon' => 0, 'article' => $currency ),
		array( 'title' => 'Sanctions', 'description' => 'Counter-sanctions and restrictions require a careful review of the parties and the transaction structure.', 'icon' => 0, 'article' => $guide ),
		array( 'title' => 'Employment', 'description' => 'Employment and immigration compliance should be planned alongside the market-entry strategy.', 'icon' => 0, 'article' => $guide ),
		array( 'title' => 'Tax', 'description' => 'Russian tax rules should be considered early when choosing the investment structure and business model.', 'icon' => 0, 'article' => $guide ),
		array( 'title' => 'Dispute resolution', 'description' => 'Contracts should address governing law, dispute resolution and enforcement risk from the outset.', 'icon' => 0, 'article' => $guide ),
	);
	if ( function_exists( 'update_field' ) ) {
		update_field( 'field_ipk_hero_title', "2026年\n俄罗斯营商指南", $home_zh ); update_field( 'field_ipk_hero_subtitle', '中国企业法律指南', $home_zh ); update_field( 'field_ipk_hero_description', '<strong>俄罗斯经济韧性强</strong>、自然资源丰富，消费市场不断增长。西方品牌的退出为<strong>中国企业</strong>拓展业务带来了难得机遇，涉及消费品、电子商务、科技、基础设施和能源等多个领域。', $home_zh ); update_field( 'field_ipk_hero_mobile', '概述中国企业与俄罗斯开展业务及在俄经营时需要关注的主要法律问题', $home_zh ); update_field( 'field_ipk_hero_link', $guide, $home_zh ); update_field( 'field_ipk_cards', $zh_cards, $home_zh );
		update_field( 'field_ipk_hero_title', "Doing Business\nin Russia 2026", $home_en ); update_field( 'field_ipk_hero_subtitle', 'A Legal Guide for Chinese Enterprises', $home_en ); update_field( 'field_ipk_hero_description', '<strong>Russia enjoys</strong> a resilient economy, abundant natural resources and a growing consumer market. Withdrawal by Western brands has opened up a rare opportunity for <strong>Chinese companies</strong> to expand into sectors ranging from consumer goods and e-commerce to technology, infrastructure and energy.', $home_en ); update_field( 'field_ipk_hero_mobile', 'A general overview of the key legal considerations for Chinese companies dealing with Russia and doing business in the country.', $home_en ); update_field( 'field_ipk_hero_link', $guide, $home_en ); update_field( 'field_ipk_cards', $en_cards, $home_en );
		foreach ( array( $guide, $currency ) as $article_id ) {
			update_field( 'field_ipk_sidebar_title', get_the_title( $article_id ), $article_id ); update_field( 'field_ipk_sidebar_intro', 'General Overview by|Nikolay Simoyanov /counsel|Mark Rovinskiy /partner', $article_id );
			update_field( 'field_ipk_contacts', array(
				array( 'name' => 'nikolay simoyanov', 'role' => 'counsel', 'photo' => 0, 'profile_url' => 'https://ivanyan.partners/en/people/NikolaySimoyanov/', 'email' => 'n.simoyanov@iplf.ru', 'phone' => '+7 495 647 0046' ),
				array( 'name' => 'mark rovinskiy', 'role' => 'partner', 'photo' => 0, 'profile_url' => 'https://ivanyan.partners/en/people/MarkRovinskiy/', 'email' => 'm.rovinskiy@iplf.ru', 'phone' => '+7 495 647 0046' ),
			), $article_id );
		}
	}
	update_option( 'ipk_content_seeded', 1, false );
}
add_action( 'admin_init', 'ipk_seed_initial_content' );

function ipk_fix_article_links( $content ) {
	if ( ! is_singular( 'post' ) ) { return $content; }
	$currency = get_page_by_path( 'currency-control-russia-2026', OBJECT, 'post' );
	if ( $currency ) {
		$content = str_replace( 'href="currency-control.html"', 'href="' . esc_url( get_permalink( $currency ) ) . '"', $content );
	}
	return $content;
}
add_filter( 'the_content', 'ipk_fix_article_links', 20 );
