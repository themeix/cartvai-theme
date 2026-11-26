<?php
/**
 * Title: Cartvai - Top Selling Products (2x2 Wide Cards)
 * Slug: cartvai-theme/top-sellers
 * Categories: cartvai
 * Description: Section header and 2x2 wide featured card grid for top selling products.
 *
 * @package Cartvai_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cartvai_shop_url = function_exists( 'cartvai_get_shop_page_url' ) ? cartvai_get_shop_page_url() : home_url( '/shop/' );
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"1rem","bottom":"2.5rem"}}},"className":"cv-home-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide cv-home-section" style="padding-top:1rem;padding-bottom:2.5rem">
	<div class="cv-shelf-header">
		<!-- wp:heading {"level":3,"className":"cv-shelf-title"} -->
		<h3 class="wp-block-heading cv-shelf-title"><?php esc_html_e( 'Top Selling Products', 'cartvai' ); ?></h3>
		<!-- /wp:heading -->
		<a href="<?php echo esc_url( $cartvai_shop_url ); ?>" class="cv-shelf-link">
			<span><?php esc_html_e( 'View All Best Sellers', 'cartvai' ); ?></span>
			<svg class="cv:w-3.5 cv:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
		</a>
	</div>

	<?php
	if ( class_exists( 'CARTVAI_Shop_Page' ) ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Plugin template API returns safe HTML.
		echo CARTVAI_Shop_Page::render_products_grid(
			array(
				'layout'  => 'wide',
				'limit'   => '4',
				'orderby' => 'date',
			)
		);
	}
	?>
</div>
<!-- /wp:group -->
