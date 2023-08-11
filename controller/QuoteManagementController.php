<?php

/**
 * @package QuoteOfTheDayPlugin
 */


////////////////////////////////////////////////////
// Register the "Quotes Management" custom post type
add_action('init', 'quote_of_the_day_plugin_register_post_type');

// Create the admin panel "Quotes Management" page
add_action('admin_menu', 'quote_of_the_day_plugin_quotes_management');

// Callback function to display the "Quotes Management" page
function quote_of_the_day_manage_quotes_page()
{
	$quote_menu_enabled = get_option('quote_menu_enabled', true);

	if ($quote_menu_enabled) {
		require_once plugin_dir_path(__FILE__) . '../view/quotes_management.php';
	}
}
