<?php
/**
 * Mega Menu Bar — Nav-menu driven
 *
 * Renders the horizontal category navigation bar for the Megastore /
 * MegaBar headers. Items are read directly from the "Mega Menu Bar"
 * nav menu location (Appearance → Menus → Mega Menu Bar).
 *
 * HOW IT WORKS
 * ─────────────────────────────────────────────────────────────────────
 * 1. Go to Appearance → Menus, create or pick a menu and assign it to
 *    the "Mega Menu Bar (Colorful Category Nav)" location.
 * 2. Add any items you want: pages, categories, custom links, etc.
 * 3. If a menu item links to a Cartvai product category
 *    (object = cartvai_product_cat), hovering it automatically opens
 *    a full-width Mega Menu panel showing up to 9 products from that
 *    category in a 3-column thumbnail + price grid.
 * 4. All other items render as simple nav links (no dropdown).
 * 5. Sub-items (child items in the menu) are ignored on the bar itself
 *    but category children are shown as pill filters inside the dropdown.
 *
 * If no menu is assigned yet, a friendly admin-only notice is shown.
 * ─────────────────────────────────────────────────────────────────────
 *
 * @package Cartvai_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Resolve menu items ──────────────────────────────────────────────
$cartvai_mega_menu_items = array();
$cartvai_menu_location   = 'megamenu'; // location slug registered in setup.php

if ( has_nav_menu( $cartvai_menu_location ) ) {
	$cartvai_locations = get_nav_menu_locations();
	if ( ! empty( $cartvai_locations[ $cartvai_menu_location ] ) ) {
		$cartvai_all_items = wp_get_nav_menu_items( $cartvai_locations[ $cartvai_menu_location ] );
		if ( ! empty( $cartvai_all_items ) && ! is_wp_error( $cartvai_all_items ) ) {
			// Separate top-level items from children
			$cartvai_children_map = array();
			foreach ( $cartvai_all_items as $cartvai_item ) {
				if ( (int) $cartvai_item->menu_item_parent !== 0 ) {
					$cartvai_children_map[ (int) $cartvai_item->menu_item_parent ][] = $cartvai_item;
				}
			}
			foreach ( $cartvai_all_items as $cartvai_item ) {
				if ( (int) $cartvai_item->menu_item_parent === 0 ) {
					$cartvai_mega_menu_items[] = array(
						'item'     => $cartvai_item,
						'children' => $cartvai_children_map[ $cartvai_item->ID ] ?? array(),
					);
				}
			}
		}
	}
}

$cartvai_shop_url        = home_url( '/shop/' );
$cartvai_is_cartvai      = class_exists( 'CARTVAI_CPT' );
$cartvai_tax     = $cartvai_is_cartvai ? CARTVAI_CPT::TAXONOMY : '';
$cartvai_has_products_fn = class_exists( 'CARTVAI_Products' );
$cartvai_has_settings_fn = class_exists( 'CARTVAI_Settings' );
?>
<ul class="cv-mega-nav-list cv:flex cv:items-center cv:flex-wrap cv:gap-1 cv:lg:gap-2 cv:m-0 cv:p-0 cv:list-none">

<?php if ( empty( $cartvai_mega_menu_items ) ) : ?>
	<?php if ( is_admin() || current_user_can( 'manage_options' ) ) : ?>
		<li class="cv-mega-nav-item cv:py-2.5">
			<span class="cv-mega-nav-link cv:inline-flex cv:items-center cv:gap-2 cv:px-3.5 cv:py-1.5 cv:rounded-lg cv:text-sm cv:font-semibold cv:text-amber-300 cv:bg-amber-900/40 cv:border cv:border-amber-600/40">
				<svg class="cv:w-4 cv:h-4 cv:shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
				<span>
					<?php esc_html_e( 'Admin tip: Go to', 'cartvai' ); ?>
					<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="cv:underline hover:cv:text-white" style="color:inherit;">
						<?php esc_html_e( 'Appearance → Menus', 'cartvai' ); ?>
					</a>
					<?php esc_html_e( '→ assign a menu to "Mega Menu Bar" to control these nav items.', 'cartvai' ); ?>
				</span>
			</span>
		</li>
	<?php endif; ?>

<?php else : ?>

	<?php foreach ( $cartvai_mega_menu_items as $cartvai_entry ) :
		$cartvai_item         = $cartvai_entry['item'];
		$cartvai_child_items  = $cartvai_entry['children'];
		$cartvai_item_url     = $cartvai_item->url;
		$cartvai_item_title   = $cartvai_item->title;
		$cartvai_item_classes = array_filter( (array) $cartvai_item->classes );
		$cartvai_is_highlight = in_array( 'cv-highlight', $cartvai_item_classes, true )
		             || in_array( 'menu-item-highlight', $cartvai_item_classes, true )
		             || in_array( 'highlight', $cartvai_item_classes, true );

		// Detect if this item is a Cartvai product category term
		$cartvai_is_cat_term = $cartvai_is_cartvai
		            && $cartvai_item->type === 'taxonomy'
		            && $cartvai_item->object === $cartvai_tax;

		// Fetch products when it's a category term
		$cartvai_cat_products = array();
		$cartvai_cat_term     = null;
		$cartvai_has_mega     = false;

		if ( $cartvai_is_cat_term && $cartvai_has_products_fn ) {
			$cartvai_term = get_term( (int) $cartvai_item->object_id, $cartvai_tax );
			if ( $cartvai_term && ! is_wp_error( $cartvai_term ) ) {
				$cartvai_cat_term     = $cartvai_term;
				if ( ! empty( $cartvai_term->name ) ) {
					$cartvai_item_title = $cartvai_term->name;
				}
				$cartvai_cat_products = CARTVAI_Products::get_all(
					array(
						'status'         => 1,
						'category'       => $cartvai_term->slug,
						'limit'          => 9,
						'exclude_hidden' => true,
					)
				);
				$cartvai_has_mega = ! empty( $cartvai_cat_products );
			}
		}

		// Ensure raw slugs like 'home-appliances' or 'kitchen' render with proper Title Case
		if ( strpos( $cartvai_item_title, '-' ) !== false && strtolower( $cartvai_item_title ) === $cartvai_item_title ) {
			$cartvai_item_title = ucwords( str_replace( array( '-', '_' ), ' ', $cartvai_item_title ) );
		} else {
			$cartvai_item_title = ucfirst( $cartvai_item_title );
		}

		// Detect if this is the current page / term
		$cartvai_is_current = in_array( 'current-menu-item', $cartvai_item_classes, true )
		           || in_array( 'current-menu-parent', $cartvai_item_classes, true )
		           || in_array( 'current-menu-ancestor', $cartvai_item_classes, true );

		// Get child product subcategories (for pills in the dropdown header)
		$cartvai_child_subcats = array();
		if ( $cartvai_is_cat_term && $cartvai_is_cartvai && $cartvai_cat_term ) {
			$cartvai_raw_sub = get_terms(
				array(
					'taxonomy'   => $cartvai_tax,
					'parent'     => $cartvai_cat_term->term_id,
					'hide_empty' => false,
					'number'     => 6,
				)
			);
			if ( ! empty( $cartvai_raw_sub ) && ! is_wp_error( $cartvai_raw_sub ) ) {
				$cartvai_child_subcats = $cartvai_raw_sub;
			}
		}
		// Also add any explicit child nav menu items as fallback pills
		$cartvai_nav_child_pills = array();
		if ( ! $cartvai_is_cat_term && ! empty( $cartvai_child_items ) ) {
			$cartvai_nav_child_pills = $cartvai_child_items;
		}
		?>
		<li class="cv-mega-nav-item cv:py-2.5<?php echo $cartvai_is_current ? ' is-active' : ''; ?>">
			<a href="<?php echo esc_url( $cartvai_item_url ); ?>"
				class="cv-mega-nav-link<?php echo $cartvai_is_highlight ? ' cv-mega-nav-link--highlight' : ''; ?> cv:inline-flex cv:items-center cv:gap-1.5 cv:px-3.5 cv:py-1.5 cv:rounded-lg cv:text-sm cv:font-semibold cv:transition-all"
				<?php echo $cartvai_item->target ? 'target="' . esc_attr( $cartvai_item->target ) . '"' : ''; ?>
				<?php echo $cartvai_item->attr_title ? 'title="' . esc_attr( $cartvai_item->attr_title ) . '"' : ''; ?>
			>
				<?php if ( $cartvai_is_highlight ) : ?>
					<span class="cv:inline-block cv:w-2 cv:h-2 cv:rounded-full cv:bg-amber-400 cv:shrink-0"></span>
				<?php endif; ?>
				<span><?php echo esc_html( $cartvai_item_title ); ?></span>
				<?php if ( $cartvai_has_mega || ! empty( $cartvai_child_items ) ) : ?>
					<svg class="cv-mega-chevron cv:w-3.5 cv:h-3.5 cv:opacity-70 cv:transition-transform cv:shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7"></path></svg>
				<?php endif; ?>
			</a>

			<?php if ( $cartvai_has_mega ) : ?>
			<!-- ── Mega Menu Dropdown: Category Products ── -->
			<div class="cv-mega-dropdown" role="region" aria-label="<?php echo esc_attr( $cartvai_item_title ); ?>">
				<div class="cv-mega-dropdown-inner">
					<!-- Header Strip -->
					<div class="cv-mega-dropdown-header">
						<div class="cv:flex cv:items-center cv:flex-wrap cv:gap-3">
							<h3 class="cv:text-base cv:font-bold cv:text-gray-900 cv:m-0 cv:whitespace-nowrap">
								<?php echo esc_html( $cartvai_item_title ); ?>
							</h3>
							<?php if ( ! empty( $cartvai_child_subcats ) ) : ?>
								<div class="cv:flex cv:items-center cv:flex-wrap cv:gap-1.5 cv:pl-3 cv:border-l cv:border-gray-200">
									<?php foreach ( $cartvai_child_subcats as $cartvai_subcat ) :
										$cartvai_sub_link = get_term_link( $cartvai_subcat );
										if ( is_wp_error( $cartvai_sub_link ) ) continue;
										?>
										<a href="<?php echo esc_url( $cartvai_sub_link ); ?>" class="cv:text-xs cv:font-semibold cv:px-2.5 cv:py-1 cv:rounded-md cv:bg-white cv:border cv:border-gray-200 cv:text-gray-700 hover:cv:border-primary hover:cv:text-primary cv:transition-colors cv:whitespace-nowrap">
											<?php echo esc_html( $cartvai_subcat->name ); ?>
										</a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
						<a href="<?php echo esc_url( $cartvai_item_url ); ?>" class="cv-mega-view-all">
							<span><?php esc_html_e( 'View All', 'cartvai' ); ?></span>
							<svg class="cv:w-4 cv:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
						</a>
					</div>

					<!-- 3-Column Product Grid -->
					<div class="cv-mega-product-grid">
						<?php foreach ( $cartvai_cat_products as $cartvai_prod ) :
							$cartvai_prod_url        = get_permalink( $cartvai_prod->id );
							$cartvai_effective_price = CARTVAI_Products::get_price( $cartvai_prod );
							$cartvai_regular_price   = (float) $cartvai_prod->price;
							$cartvai_is_on_sale      = $cartvai_effective_price < $cartvai_regular_price && $cartvai_regular_price > 0;
							$cartvai_formatted_price = $cartvai_has_settings_fn ? CARTVAI_Settings::format_price( $cartvai_effective_price ) : number_format( $cartvai_effective_price, 2 );
							$cartvai_formatted_reg   = $cartvai_has_settings_fn ? CARTVAI_Settings::format_price( $cartvai_regular_price ) : number_format( $cartvai_regular_price, 2 );
							?>
							<a href="<?php echo esc_url( $cartvai_prod_url ); ?>" class="cv-mega-product-card">
								<div class="cv-mega-product-thumb">
									<?php if ( ! empty( $cartvai_prod->image ) ) : ?>
										<img src="<?php echo esc_url( $cartvai_prod->image ); ?>" alt="<?php echo esc_attr( $cartvai_prod->title ); ?>" loading="lazy" width="52" height="52" />
									<?php else : ?>
										<span class="cv-mega-product-placeholder">
											<svg class="cv:w-5 cv:h-5 cv:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
										</span>
									<?php endif; ?>
								</div>
								<div class="cv-mega-product-info">
									<div class="cv-mega-product-title"><?php echo esc_html( $cartvai_prod->title ); ?></div>
									<div class="cv-mega-product-price-row">
										<span class="cv-mega-product-price"><?php echo esc_html( $cartvai_formatted_price ); ?></span>
										<?php if ( $cartvai_is_on_sale ) : ?>
											<del class="cv-mega-product-reg"><?php echo esc_html( $cartvai_formatted_reg ); ?></del>
										<?php endif; ?>
									</div>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<?php elseif ( ! empty( $cartvai_child_items ) ) : ?>
			<!-- ── Simple Dropdown: Nav child items (no category) ── -->
			<div class="cv-mega-dropdown cv-mega-dropdown--simple" role="region" aria-label="<?php echo esc_attr( $cartvai_item_title ); ?>">
				<div class="cv-mega-dropdown-inner">
					<div class="cv-simple-dropdown-list">
						<?php foreach ( $cartvai_child_items as $cartvai_child ) : ?>
							<a href="<?php echo esc_url( $cartvai_child->url ); ?>" class="cv-simple-dropdown-item"
								<?php echo $cartvai_child->target ? 'target="' . esc_attr( $cartvai_child->target ) . '"' : ''; ?>
							>
								<?php echo esc_html( $cartvai_child->title ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php endif; ?>

		</li>
	<?php endforeach; ?>
<?php endif; ?>

</ul>
