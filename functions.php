<?php
/**
 * Magazine Pro.
 *
 * This file adds the functions to the Magazine Pro Theme.
 *
 * @package Magazine
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/magazine/
 */

// Start the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Setup Theme.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

// Set Localization (do not remove).
add_action( 'after_setup_theme', 'magazine_localization_setup' );
function magazine_localization_setup(){
	load_child_theme_textdomain( 'magazine-pro', get_stylesheet_directory() . '/languages' );
}

// Add the theme helper functions.
include_once( get_stylesheet_directory() . '/lib/helper-functions.php' );

// Add the Customizer options.
include_once( get_stylesheet_directory() . '/lib/customize.php' );

// Add the Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Add WooCommerce support.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php' );

// Add the WooCommerce customizer CSS.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php' );

// Include notice to install Genesis Connect for WooCommerce.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php' );

// Child theme (do not remove).
define( 'CHILD_THEME_NAME', __( 'Magazine Pro', 'magazine-pro' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/magazine/' );
define( 'CHILD_THEME_VERSION', '3.2.4' );

// Enqueue required fonts, scripts, and styles.
add_action( 'wp_enqueue_scripts', 'magazine_enqueue_scripts' );
function magazine_enqueue_scripts() {

	wp_enqueue_script( 'magazine-entry-date', get_stylesheet_directory_uri() . '/js/entry-date.js', array( 'jquery' ), '1.0.0' );

	wp_enqueue_style( 'dashicons' );
    
    // Font Awesome Enqueue Scripts
    // wp_enqueue_script( 'fontawesome', get_stylesheet_directory_uri() . '/js/fontawesome.js', array( 'jquery' ), CHILD_THEME_VERSION );
    
    // wp_enqueue_script( 'light', get_stylesheet_directory_uri() . '/js/light.js', array( 'jquery' ), CHILD_THEME_VERSION );
    
    wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Fira+Sans|Open+Sans|Lato', array(), CHILD_THEME_VERSION );
    

	// wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Fira+Sans|Open+Sans|Lato', array(), CHILD_THEME_VERSION );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'magazine-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menus' . $suffix . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script(
		'magazine-responsive-menu',
		'genesis_responsive_menu',
		magazine_responsive_menu_settings()
	);

}

// Define our responsive menu settings.
function magazine_responsive_menu_settings() {

	$settings = array(
		'mainMenu'    => __( 'Menu', 'magazine-pro' ),
		'subMenu'     => __( 'Submenu', 'magazine-pro' ),
		'menuClasses' => array(
			'combine' => array(
				'.nav-primary',
				'.nav-header',
				'.nav-secondary',
			),
		),
	);

	return $settings;

}

// Add HTML5 markup structure.
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

// Add Accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Add viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Add image sizes.
add_image_size( 'home-middle', 630, 350, true );
add_image_size( 'home-top', 750, 420, true );
add_image_size( 'sidebar-thumbnail', 100, 100, true );

// Add support for custom header.
add_theme_support( 'custom-header', array(
	'default-text-color' => '000000',
	'flex-height'        => true,
	'header-selector'    => '.site-title a',
	'header-text'        => false,
	'height'             => 180,
	'width'              => 760,
) );

// Rename menus.
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Before Header Menu', 'magazine-pro' ), 'secondary' => __( 'After Header Menu', 'magazine-pro' ) ) );

// Remove skip link for primary navigation.
add_filter( 'genesis_skip_links_output', 'magazine_skip_links_output' );
function magazine_skip_links_output( $links ) {

	if ( isset( $links['genesis-nav-primary'] ) ) {
		unset( $links['genesis-nav-primary'] );
	}

	$new_links = $links;
	array_splice( $new_links, 1 );

	if ( has_nav_menu( 'secondary' ) ) {
		$new_links['genesis-nav-secondary'] = __( 'Skip to secondary menu', 'magazine-pro' );
	}

	return array_merge( $new_links, $links );

}

// Add ID to secondary navigation.
add_filter( 'genesis_attr_nav-secondary', 'magazine_add_nav_secondary_id' );
function magazine_add_nav_secondary_id( $attributes ) {

	$attributes['id'] = 'genesis-nav-secondary';

	return $attributes;

}

// Reposition the primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );

// Remove output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

// Remove navigation meta box.
add_action( 'genesis_theme_settings_metaboxes', 'magazine_remove_genesis_metaboxes' );
function magazine_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {
	remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'main' );
}

// Add primary-nav class if primary navigation is used.
add_filter( 'body_class', 'magazine_no_nav_class' );
function magazine_no_nav_class( $classes ) {

	$menu_locations = get_theme_mod( 'nav_menu_locations' );

	if ( ! empty( $menu_locations['primary'] ) ) {
		$classes[] = 'primary-nav';
	}

	return $classes;

}

// Customize search form input box text.
add_filter( 'genesis_search_text', 'magazine_search_text' );
function magazine_search_text( $text ) {
	return esc_attr( __( 'Search AML-CFT', 'magazine-pro' ) );
}

// Remove entry meta in entry footer.
add_action( 'genesis_before_entry', 'magazine_remove_entry_meta' );
function magazine_remove_entry_meta() {

	// Remove if not single post.
	if ( ! is_single() ) {
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
	}

}

// Add support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Relocate after entry widget.
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_entry_footer', 'genesis_after_entry_widget_area' );

// Register widget areas.
genesis_register_sidebar( array(
	'id'          => 'home-top',
	'name'        => __( 'Home - Top', 'magazine-pro' ),
	'description' => __( 'This is the top section of the homepage.', 'magazine-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-middle',
	'name'        => __( 'Home - Middle', 'magazine-pro' ),
	'description' => __( 'This is the middle section of the homepage.', 'magazine-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-bottom',
	'name'        => __( 'Home - Bottom', 'magazine-pro' ),
	'description' => __( 'This is the bottom section of the homepage.', 'magazine-pro' ),
) );

// Display author box on single posts
add_filter( 'get_the_author_genesis_author_box_single', '__return_true' );

//* Instant article for knowledge base
add_filter( 'instant_articles_post_types', 'add_post_types', 10,1 );
function add_post_types($post_type_array){
    array_push($post_type_array,'epkb_post_type_1');
    array_push($post_type_array,'epkb_post_type_2');
    array_push($post_type_array,'epkb_post_type_3');
   return $post_type_array;
}

// Remove WooCommerce Updater
remove_action('admin_notices', 'woothemes_updater_notice');

//Add featured image
add_action( 'customize_register', 'themeprefix_customizer_featured_image' );
 
function themeprefix_customizer_featured_image() {

	global $wp_customize;
	
	// Add featured image section to the Customizer
	$wp_customize->add_section(
	'themeprefix_single_image_section',
	array(
		'title'       => __( 'Post and Page Images', 'themeprefix' ),
		'description' => __( 'Choose if you would like to display the featured image above the content on single posts and pages.', 'themeprefix' ),
		'priority' => 200, // puts the customizer section lower down
	)
);

	// Add featured image setting to the Customizer
	$wp_customize->add_setting(
	'themeprefix_single_image_setting',
	array(
		'default'           => true, // set the option as enabled by default
		'capability'        => 'edit_theme_options',
	)
);

	$wp_customize->add_control(
	'themeprefix_single_image_setting',
	array(
		'section'   => 'themeprefix_single_image_section',
		'settings'  => 'themeprefix_single_image_setting',
		'label'     => __( 'Show featured image on posts and pages?', 'themeprefix' ),
		'type'      => 'checkbox'
	)
);

}

// Add featured image on single post
add_action( 'genesis_entry_content', 'themeprefix_featured_image', 1 );
function themeprefix_featured_image() {

	$add_single_image = get_theme_mod( 'themeprefix_single_image_setting', true ); //sets the customizer setting to a variable

	$image = genesis_get_image( array( // more options here -> genesis/lib/functions/image.php
			'format'  => 'html',
			'size'    => 'large',// add in your image size large, medium or thumbnail - for custom see the post
			'context' => '',
			'attr'    => array ( 'class' => 'aligncenter' ), // set a default WP image class
			
		) );

	if ( is_singular() && ( true === $add_single_image ) && has_post_thumbnail() ) {
		if ( $image ) {
			printf( '<div class="featured-image-class">%s</div>', $image ); // wraps the featured image in a div with css class you can control
		}
	}

}

// Enqueue site-wide scripts global.js in magazine-pro/JS
add_action( 'wp_enqueue_scripts', 'jx_enqueue_scripts' );
function jx_enqueue_scripts() {

	wp_enqueue_script( 'global', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), '', true );

}


//remove header from all pages
add_action('get_header', 'jx_remove_header');

function jx_remove_header() {
if (!is_home()) {
remove_action( 'genesis_header', 'genesis_do_header' );
    }
}

//* Modify the Genesis content limit read more link
add_filter( 'get_the_content_more_link', 'sp_read_more_link' );
function sp_read_more_link() {
	return '... <a class="more-link" href="' . get_permalink() . '">Read More &#8594;</a>';
}

