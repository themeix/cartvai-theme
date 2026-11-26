<?php
/**
 * Title: Cartvai - Modern Hero Banner & Promo Cards
 * Slug: cartvai-theme/hero-megastore
 * Categories: cartvai, featured, header
 * Description: High-converting ecommerce hero banner with main featured callout and secondary promotional cards.
 *
 * @package Cartvai_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cartvai_shop_url = function_exists( 'cartvai_get_shop_page_url' ) ? cartvai_get_shop_page_url() : home_url( '/shop/' );
?>
<!-- wp:group {"align":"full","className":"cv-hero-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull cv-hero-section">
	<!-- wp:columns {"align":"wide"} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column {"width":"66.66%"} -->
		<div class="wp-block-column" style="flex-basis:66.66%">
			<!-- wp:group {"className":"cv-hero-main-banner","layout":{"type":"default"}} -->
			<div class="wp-block-group cv-hero-main-banner">
				<!-- wp:paragraph {"className":"cv-hero-badge"} -->
				<p class="cv-hero-badge"><?php esc_html_e( '⚡ LIMITED TIME MEGA SALE • UP TO 50% OFF', 'cartvai' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"level":1,"className":"cv-hero-headline"} -->
				<h1 class="wp-block-heading cv-hero-headline"><?php esc_html_e( 'Next-Gen Tech & Premium Lifestyle Gear', 'cartvai' ); ?></h1>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"className":"cv-hero-subtitle"} -->
				<p class="cv-hero-subtitle"><?php esc_html_e( 'Upgrade your daily routine with top-rated smart devices, audio accessories, and modern essentials at unbeatable prices.', 'cartvai' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons -->
				<div class="wp-block-buttons">
					<!-- wp:button {"className":"cv-hero-btn-primary"} -->
					<div class="wp-block-button cv-hero-btn-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $cartvai_shop_url ); ?>"><?php esc_html_e( 'Shop Collection →', 'cartvai' ); ?></a></div>
					<!-- /wp:button -->

					<!-- wp:button {"className":"cv-hero-btn-secondary"} -->
					<div class="wp-block-button cv-hero-btn-secondary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $cartvai_shop_url ); ?>"><?php esc_html_e( 'View Flash Deals', 'cartvai' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->

				<!-- wp:paragraph {"className":"cv-hero-perks"} -->
				<p class="cv-hero-perks"><?php esc_html_e( '✓ Free Express Delivery • ✓ 30-Day Money-Back • ✓ Official Warranty', 'cartvai' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"33.34%"} -->
		<div class="wp-block-column" style="flex-basis:33.34%">
			<!-- wp:group {"className":"cv-hero-promo-stack","layout":{"type":"default"}} -->
			<div class="wp-block-group cv-hero-promo-stack">
				<!-- wp:group {"className":"cv-hero-promo-card cv-hero-promo-card--blue","layout":{"type":"default"}} -->
				<div class="wp-block-group cv-hero-promo-card cv-hero-promo-card--blue">
					<!-- wp:paragraph {"className":"cv-promo-badge"} -->
					<p class="cv-promo-badge"><?php esc_html_e( '🔥 TRENDING DEAL', 'cartvai' ); ?></p>
					<!-- /wp:paragraph -->

					<!-- wp:heading {"level":3} -->
					<h3 class="wp-block-heading"><?php esc_html_e( 'Wireless Spatial Audio', 'cartvai' ); ?></h3>
					<!-- /wp:heading -->

					<!-- wp:paragraph -->
					<p><?php esc_html_e( 'Immersive acoustic clarity with 40-hour ultra battery life.', 'cartvai' ); ?></p>
					<!-- /wp:paragraph -->

					<!-- wp:buttons -->
					<div class="wp-block-buttons">
						<!-- wp:button {"className":"is-style-fill"} -->
						<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $cartvai_shop_url ); ?>"><?php esc_html_e( 'Shop Now →', 'cartvai' ); ?></a></div>
						<!-- /wp:button -->
					</div>
					<!-- /wp:buttons -->
				</div>
				<!-- /wp:group -->

				<!-- wp:group {"className":"cv-hero-promo-card cv-hero-promo-card--amber","layout":{"type":"default"}} -->
				<div class="wp-block-group cv-hero-promo-card cv-hero-promo-card--amber">
					<!-- wp:paragraph {"className":"cv-promo-badge"} -->
					<p class="cv-promo-badge"><?php esc_html_e( '⚡ FLASH OFFER', 'cartvai' ); ?></p>
					<!-- /wp:paragraph -->

					<!-- wp:heading {"level":3} -->
					<h3 class="wp-block-heading"><?php esc_html_e( 'Smart Wearables & Fit Tech', 'cartvai' ); ?></h3>
					<!-- /wp:heading -->

					<!-- wp:paragraph -->
					<p><?php esc_html_e( 'Real-time health telemetry with crisp AMOLED display.', 'cartvai' ); ?></p>
					<!-- /wp:paragraph -->

					<!-- wp:buttons -->
					<div class="wp-block-buttons">
						<!-- wp:button {"className":"is-style-fill"} -->
						<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $cartvai_shop_url ); ?>"><?php esc_html_e( 'Explore Deals →', 'cartvai' ); ?></a></div>
						<!-- /wp:button -->
					</div>
					<!-- /wp:buttons -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
