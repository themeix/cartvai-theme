<?php
/**
 * Cartvai Theme Settings Migration
 *
 * All theme settings are managed in the WordPress Customizer
 * (Appearance > Customize). This file only migrates settings from the
 * legacy "cartvai_theme_settings" option (former admin settings page)
 * into theme mods so no stored setting is lost after the update.
 *
 * @package Cartvai_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Migrate legacy cartvai_theme_settings option values into theme mods.
 *
 * Runs once. Values saved in the old admin settings page are transferred
 * to the matching Customizer settings (stored as theme mods).
 *
 * @return void
 */
function cartvai_theme_migrate_legacy_settings() {
	if ( get_option( 'cartvai_settings_migrated' ) ) {
		return;
	}

	$settings = get_option( 'cartvai_theme_settings', array() );
	if ( ! is_array( $settings ) || empty( $settings ) ) {
		update_option( 'cartvai_settings_migrated', 1 );
		return;
	}

	// Keys stored as booleans in the Customizer.
	$boolean_keys = array(
		'announcement_enabled',
		'popular_categories_enabled',
		'header_order_track_enabled',
		'header_more_enabled',
		'brand_tagline_enabled',
		'homepage_trust_enabled',
		'footer_trust_enabled',
		'shop_page_hide_short_description',
		'shop_page_hide_quantity',
		'shop_page_hide_buy_now',
		'shop_page_hide_add_to_cart',
		'shop_page_hide_off_badge',
		'shop_page_hide_save_badge',
	);

	foreach ( $settings as $key => $value ) {
		// Newsletter settings were removed from the theme; skip them.
		if ( 0 === strpos( $key, 'footer_newsletter' ) ) {
			continue;
		}

		if ( in_array( $key, $boolean_keys, true ) ) {
			set_theme_mod( 'cartvai_' . $key, (bool) $value );
		} else {
			set_theme_mod( 'cartvai_' . $key, $value );
		}
	}

	update_option( 'cartvai_settings_migrated', 1 );
}
add_action( 'admin_init', 'cartvai_theme_migrate_legacy_settings' );

/**
 * Keep the Customizer as the single source of truth: on every admin request
 * (after migration) the legacy option is no longer read by the theme.
 *
 * cartvai_theme_option() in inc/template-tags.php reads theme mods first and
 * only falls back to the legacy option for very old installs that have not
 * been migrated yet.
 *
 * @package Cartvai_Theme
 */

/**
 * Sanitize a hex color with fallback.
 *
 * @param string $color   Raw color value.
 * @param string $default Fallback color.
 * @return string Sanitized hex color.
 */
function cartvai_theme_normalize_hex( $color, $default = '#2563eb' ) {
	$color = sanitize_hex_color( trim( (string) $color ) );
	return ! empty( $color ) ? $color : $default;
}
