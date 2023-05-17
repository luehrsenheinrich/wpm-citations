<?php
/**
 * The header for the multisite user activation page.
 *
 * This is the template that displays all of the <head> section. This file exists to
 * be as barebones as possible, because during multisite user activation, the theme
 * is loaded without any plugins. This means that the theme cannot use any functions
 * from plugins.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package lhpbpt
 */

namespace WpMunich\lhpbpt;

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
