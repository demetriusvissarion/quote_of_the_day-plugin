<?php

/**
 * Quote of the Day Plugin - Enqueue Scripts
 */

// ob_start();

// If this file is called directly, abort
defined('ABSPATH') or die("Hello there");

// Enqueue necessary CSS styles
function quote_of_the_day_plugin_enqueue_styles()
{
	wp_enqueue_style('quote-of-the-day-plugin-styles', plugins_url('/assets/css/styles.css', __FILE__));
	wp_enqueue_style('bootstrap-switch-css', plugins_url('/assets/css/bootstrap-switch.min.css', __FILE__));
}

/////////////////////////////////////////////////////////
// Enqueue JavaScript for the toggle button
function quote_of_the_day_toggle_menu_js($hook)
{
	if ($hook === 'toplevel_page_quote-of-the-day-settings') {
		// Enqueue Bootstrap Switch CSS and JS
		wp_enqueue_style('bootstrap-switch', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css');
		wp_enqueue_script('bootstrap-switch', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js', array('jquery'), '3.3.4', true);

		// Enqueue your custom JavaScript
		wp_enqueue_script('quote-of-the-day-toggle-menu', plugin_dir_url(__FILE__) . 'toggle-menu.js', array('jquery'), '1.0', true);
	}
}

// Enqueue the toggle menu JavaScript in the existing function
add_action('admin_enqueue_scripts', 'quote_of_the_day_toggle_menu_js');

// ob_end_flush();
