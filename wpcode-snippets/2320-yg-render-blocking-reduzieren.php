<?php
/**
 * YG: Render-Blocking reduzieren (Performance R6, 2026-11-24)
 *
 * 1. Preconnect to Adobe Fonts. The kit CSS (use.typekit.net) @imports p.typekit.net/p.css, and the
 *    fonts come from use.typekit.net with CORS: three connections the browser can open early.
 * 2. Defer scripts in <head> that nothing depends on (WordPress' strategy API: if a dependent script
 *    is blocking, WordPress keeps them blocking, so this can't break the order).
 * 3. Load stylesheets that only the footer and the click-opened newsletter popup (1362, no triggers)
 *    need without blocking the first paint (media="print" + onload swap, <noscript> fallback).
 *
 * Front end only: not in wp-admin, the Customizer or the Elementor editor/preview.
 */
if ( ! function_exists( 'yg_rb_is_front' ) ) {
	function yg_rb_is_front() {
		if ( is_admin() || wp_doing_ajax() || is_customize_preview() ) {
			return false;
		}
		if ( isset( $_GET['elementor-preview'] ) || isset( $_GET['elementor_library'] ) ) {
			return false;
		}
		return true;
	}
}

add_action( 'wp_head', function () {
	if ( ! yg_rb_is_front() ) {
		return;
	}
	echo '<link rel="preconnect" href="https://use.typekit.net">' . "\n";
	echo '<link rel="preconnect" href="https://use.typekit.net" crossorigin>' . "\n";
	echo '<link rel="preconnect" href="https://p.typekit.net">' . "\n";
}, 0 );

add_action( 'wp_print_scripts', function () {
	if ( ! yg_rb_is_front() ) {
		return;
	}
	foreach ( array( 'linguise_switcher', 'sonaar-music-scrollbar' ) as $handle ) {
		if ( wp_script_is( $handle, 'registered' ) ) {
			wp_script_add_data( $handle, 'strategy', 'defer' );
		}
	}
}, 1 );

add_filter( 'style_loader_tag', function ( $tag, $handle, $href, $media ) {
	static $late = array(
		'fluentform-elementor-widget',
		'fluent-form-styles',
		'fluentform-public-default',
		'jet-instagram-gallery',
		'jet-instagram-gallery-skin',
	);
	if ( ! in_array( $handle, $late, true ) || ! yg_rb_is_front() || false !== strpos( $tag, 'onload=' ) ) {
		return $tag;
	}
	$swap = preg_replace(
		"/media=(['\"])[^'\"]*\\1/",
		"media='print' onload=\"this.media='all';this.onload=null;\"",
		$tag,
		1
	);
	if ( null === $swap || $swap === $tag ) {
		return $tag;
	}
	return $swap . '<noscript>' . trim( $tag ) . "</noscript>\n";
}, 20, 4 );
