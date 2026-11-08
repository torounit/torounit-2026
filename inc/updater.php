<?php
/**
 * Theme updates delivered from GitHub Releases.
 *
 * The `Update URI` header in style.css points at github.com, so WordPress core
 * (6.1+) skips wordpress.org for this theme and asks the
 * `update_themes_github.com` filter instead. The release workflow publishes
 * `update.json` and the theme zip as assets of each GitHub Release.
 */

const TOROUNIT_2026_UPDATE_MANIFEST_URL = 'https://github.com/torounit/torounit-2026/releases/latest/download/update.json';
const TOROUNIT_2026_UPDATE_CACHE_KEY    = 'torounit_2026_update_manifest';

/**
 * Fetch the latest release manifest, cached in a transient.
 *
 * @return array|null Manifest data, or null when unavailable.
 */
function torounit_2026_get_update_manifest(): ?array {
	$cached = get_site_transient( TOROUNIT_2026_UPDATE_CACHE_KEY );
	if ( is_array( $cached ) ) {
		return $cached ?: null;
	}

	$response = wp_remote_get( TOROUNIT_2026_UPDATE_MANIFEST_URL, array( 'timeout' => 10 ) );
	$manifest = json_decode( wp_remote_retrieve_body( $response ), true );

	if (
		is_wp_error( $response )
		|| 200 !== wp_remote_retrieve_response_code( $response )
		|| ! is_array( $manifest )
		|| empty( $manifest['version'] ) || ! is_string( $manifest['version'] )
		|| empty( $manifest['package'] ) || ! is_string( $manifest['package'] )
	) {
		// Remember the failure briefly so a GitHub outage doesn't slow every check.
		set_site_transient( TOROUNIT_2026_UPDATE_CACHE_KEY, array(), 15 * MINUTE_IN_SECONDS );
		return null;
	}

	set_site_transient( TOROUNIT_2026_UPDATE_CACHE_KEY, $manifest, HOUR_IN_SECONDS );
	return $manifest;
}

add_filter( 'update_themes_github.com', function ( $update, $theme_data, $theme_stylesheet ) {
	if ( get_template() !== $theme_stylesheet ) {
		return $update;
	}

	$manifest = torounit_2026_get_update_manifest();
	if ( ! $manifest ) {
		return $update;
	}

	return array(
		'theme'        => $theme_stylesheet,
		'version'      => $manifest['version'],
		'package'      => $manifest['package'],
		'url'          => $manifest['url'] ?? 'https://github.com/torounit/torounit-2026',
		'requires'     => $manifest['requires'] ?? '',
		'requires_php' => $manifest['requires_php'] ?? '',
		'tested'       => $manifest['tested'] ?? '',
	);
}, 10, 3 );

/*
 * Always auto-update this theme in the background.
 */
add_filter( 'auto_update_theme', function ( $update, $item ) {
	if ( isset( $item->theme ) && get_template() === $item->theme ) {
		return true;
	}
	return $update;
}, 10, 2 );

/*
 * Keep the installed directory name when the zip's top-level folder differs
 * (e.g. the theme was originally installed under another folder name).
 */
add_filter( 'upgrader_source_selection', function ( $source, $remote_source, $upgrader, $hook_extra = array() ) {
	global $wp_filesystem;

	if ( is_wp_error( $source ) || ( $hook_extra['theme'] ?? null ) !== get_template() ) {
		return $source;
	}

	$expected = trailingslashit( $remote_source ) . get_template() . '/';
	if ( trailingslashit( $source ) === $expected ) {
		return $source;
	}

	if ( ! $wp_filesystem->move( $source, $expected, true ) ) {
		return new WP_Error( 'torounit_2026_rename_failed', __( 'Could not rename the theme folder during update.' ) );
	}

	return $expected;
}, 10, 4 );
