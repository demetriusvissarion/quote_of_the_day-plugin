<?php

/**
 * @package QuoteOfTheDayPlugin
 */


///////////////////////////////////////////////////////
// Create the admin menu Quote Settings page and subpages
function quote_of_the_day_plugin_quote_settings()
{
	// Main Settings Page
	add_menu_page(
		'Quote Settings',
		'Quote Settings',
		'manage_options',
		'quote-of-the-day-settings',
		'quote_of_the_day_plugin_quote_settings_page',
		'dashicons-admin-generic', // Icon
		100 // Position in the admin menu
	);

	// Subpage 1: Duration Settings
	add_submenu_page(
		'quote-of-the-day-settings',
		'Duration',
		'Duration',
		'manage_options',
		'quote-of-the-day-duration-settings',
		'quote_of_the_day_plugin_duration_settings_page'
	);

	// Subpage 2: Short Code Settings
	add_submenu_page(
		'quote-of-the-day-settings',
		'Short Code',
		'Short Code',
		'manage_options',
		'quote-of-the-day-shortcode-settings',
		'quote_of_the_day_plugin_shortcode_settings_page'
	);

	// Enqueue JavaScript for the toggle button
	add_action('admin_enqueue_scripts', 'quote_of_the_day_toggle_menu_js');
}
add_action('admin_menu', 'quote_of_the_day_plugin_quote_settings');
///////////////////////////////////////////////////////

///////////////////////////////////////////////////
// Quote Settings Page Callback
function quote_of_the_day_plugin_quote_settings_page()
{
	// Get the current options from the database
	$quote_menu_enabled = get_option('quote_menu_enabled', true);
	$quote_widget_enabled = get_option('quote_widget_enabled', true);

	// Check if the form was submitted
	if (isset($_POST['submit'])) {
		// Check if there are changes
		if (isset($_POST['quote_menu_enabled']) || isset($_POST['quote_widget_enabled'])) {
			// Update the options in the database
			update_option('quote_menu_enabled', $_POST['quote_menu_enabled'] ? true : false);
			update_option('quote_widget_enabled', $_POST['quote_widget_enabled'] ? true : false);

			add_settings_error('quote_settings', 'settings_updated', __('Changes saved.', 'quote_of_the_day_plugin_domain'), 'updated');
		} else {
			add_settings_error('quote_settings', 'no_changes', __('No changes were made.', 'quote_of_the_day_plugin_domain'), 'error');
		}

		$quote_menu_enabled = get_option('quote_menu_enabled', true);
		$quote_widget_enabled = get_option('quote_widget_enabled', true);
	}
	require_once plugin_dir_path(__FILE__) . '../view/quote_settings.php';
}
