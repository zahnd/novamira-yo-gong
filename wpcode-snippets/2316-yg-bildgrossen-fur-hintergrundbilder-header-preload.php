<?php
/**
 * YG: Bildgrössen für Hintergrundbilder + Preload des Header-Bilds (2026-11-24).
 *
 * 1. Elementor ignores a background image's "Image Resolution" when the image comes from a
 *    dynamic tag (post-featured-image, jet-post-custom-image): the tag's value replaces the
 *    setting, so the original upload is delivered (up to 4.9 MB for a card). While Elementor
 *    builds the CSS, this applies the element's own stored size (background_image[size]).
 * 2. Preloads the header image (the LCP image) on singular pages, so the browser does not
 *    wait for the CSS to discover it. Uses the same size as the header templates (header_1920).
 *
 * Repo: novamira-yo-gong, docs/performance-audit-2026-11.md (R1, R2).
 */
add_action( 'elementor/element/before_parse_css', function ( $css_file, $element ) {
	$GLOBALS['yg_bg_parse_element'] = $element;
}, 10, 2 );

add_action( 'elementor/element/parse_css', function () {
	$GLOBALS['yg_bg_parse_element'] = null;
}, 10, 0 );

add_filter( 'elementor/files/css/property', function ( $value, $css_property, $matches, $control ) {
	if ( ! is_array( $value ) || empty( $value['id'] ) || ! empty( $value['size'] ) ) {
		return $value;
	}
	if ( empty( $control['has_sizes'] ) || empty( $control['name'] ) || 'URL' !== ( $matches[2] ?? '' ) ) {
		return $value;
	}
	$element = $GLOBALS['yg_bg_parse_element'] ?? null;
	if ( ! is_object( $element ) || ! method_exists( $element, 'get_settings' ) ) {
		return $value;
	}
	$stored = $element->get_settings( $control['name'] );
	if ( is_array( $stored ) && ! empty( $stored['size'] ) ) {
		$value['size'] = $stored['size'];
	}
	return $value;
}, 20, 4 );

add_action( 'wp_head', function () {
	if ( is_admin() || ! is_singular() ) {
		return;
	}
	$post_id  = get_queried_object_id();
	$by_type  = array(
		'page'  => 'jet-meta-pages-image-banner',
		'post'  => 'jet-meta-retreats-image-banner',
		'event' => 'jet-cpt-event-banner-image',
	);
	$image_id = 0;
	$type     = get_post_type( $post_id );
	if ( isset( $by_type[ $type ] ) ) {
		$raw = get_post_meta( $post_id, $by_type[ $type ], true );
		if ( is_array( $raw ) ) {
			$raw = $raw['id'] ?? ( $raw['url'] ?? '' );
		}
		if ( is_numeric( $raw ) ) {
			$image_id = (int) $raw;
		} elseif ( is_string( $raw ) && '' !== $raw ) {
			$image_id = attachment_url_to_postid( $raw );
		}
	}
	// Pages without the header template (e.g. Home): the first top-level container's background.
	if ( ! $image_id && in_array( get_page_template_slug( $post_id ), array( 'elementor_header_footer', 'elementor_canvas' ), true ) ) {
		$data = json_decode( (string) get_post_meta( $post_id, '_elementor_data', true ), true );
		if ( ! empty( $data[0]['settings']['background_image']['id'] ) ) {
			$image_id = (int) $data[0]['settings']['background_image']['id'];
		}
	}
	if ( ! $image_id ) {
		return;
	}
	$url = wp_get_attachment_image_url( $image_id, 'header_1920' );
	if ( $url ) {
		printf( '<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n", esc_url( $url ) );
	}
}, 1 );
