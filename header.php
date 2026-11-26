<?php
/**
 * The header for Cartvai Base Theme
 *
 * @package Cartvai_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'cv-antialiased cv:bg-gray-50 cv:text-gray-900' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="cv-site cv:min-h-screen cv:flex cv:flex-col">
	<a class="skip-link screen-reader-text cv:sr-only focus:cv:not-sr-only focus:cv:fixed focus:cv:top-4 focus:cv:left-4 focus:cv:z-[999999] focus:cv:p-4 focus:cv:bg-white focus:cv:text-gray-900 focus:cv:rounded-xl focus:cv:shadow-2xl focus:cv:font-bold focus:cv:outline-2 focus:cv:outline-primary" href="#primary">
		<?php esc_html_e( 'Skip to content', 'cartvai' ); ?>
	</a>

	<?php
	$cartvai_header_style = function_exists( 'cartvai_get_header_style' ) ? cartvai_get_header_style() : 'ecommerce';
	get_template_part( 'template-parts/header/' . $cartvai_header_style );
	get_template_part( 'template-parts/components/mobile-menu' );
	?>

	<main id="primary" class="cv-site-main cv:flex-grow">
