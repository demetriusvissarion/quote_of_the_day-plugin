<?php

/**
 * @package QuoteOfTheDayPlugin
 */


/////////////////////////////////////////////////////// => QuoteSettingsModel.php
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

/////////////////////////////////////////////////// => QuoteSettingsModel.php
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

///////////////////////////////////////////////////////// => QuoteSettingsModel.php
// Enqueue JavaScript for the toggle buttons in Quote Settings
function quote_of_the_day_toggle_menu_js($hook)
{
	if ($hook === 'toplevel_page_quote-of-the-day-settings') {
		// Enqueue Bootstrap Switch CSS and JS
		wp_enqueue_style('bootstrap-switch', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css');
		wp_enqueue_script('bootstrap-switch', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js', array('jquery'), '3.3.4', true);

		// Enqueue your custom JavaScript
		wp_enqueue_script('quote-of-the-day-toggle-menu', plugin_dir_url(__FILE__) . '../view/toggle-menu.js', array('jquery'), '1.0', true);
	}
}

// Enqueue the toggle menu JavaScript in the existing function
add_action('admin_enqueue_scripts', 'quote_of_the_day_toggle_menu_js');


////////////////////////////////////////////////////// => QuoteSettingsModel.php
// Subpage 1: Duration Settings Callback
function quote_of_the_day_plugin_duration_settings_page()
{
	// Check if settings were saved and display a success message
	if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
		add_settings_error('quote_duration_settings', 'settings_updated', __('Changes saved.', 'quote_of_the_day_plugin_domain'), 'updated');
	}

	require_once plugin_dir_path(__FILE__) . '../view/duration.php';
}

function quote_of_the_day_plugin_register_duration_settings()
{
	add_settings_section(
		'quote_of_the_day_duration_section',
		__('', 'quote_of_the_day_plugin_domain'),
		'quote_of_the_day_plugin_duration_section_callback',
		'quote-of-the-day-duration-settings'
	);

	add_settings_field(
		'quote_duration_days',
		__('Quote Display Duration (Days)', 'quote_of_the_day_plugin_domain'),
		'quote_of_the_day_plugin_duration_field_callback',
		'quote-of-the-day-duration-settings',
		'quote_of_the_day_duration_section',
		array('unit' => 'day')
	);

	add_settings_field(
		'quote_duration_hours',
		__('Quote Display Duration (Hours)', 'quote_of_the_day_plugin_domain'),
		'quote_of_the_day_plugin_duration_field_callback',
		'quote-of-the-day-duration-settings',
		'quote_of_the_day_duration_section',
		array('unit' => 'hour')
	);

	add_settings_field(
		'quote_duration_minutes',
		__('Quote Display Duration (Minutes)', 'quote_of_the_day_plugin_domain'),
		'quote_of_the_day_plugin_duration_field_callback',
		'quote-of-the-day-duration-settings',
		'quote_of_the_day_duration_section',
		array('unit' => 'minute')
	);

	register_setting('quote_of_the_day_duration_group', 'quote_duration', 'quote_of_the_day_validate_duration');
}
add_action('admin_init', 'quote_of_the_day_plugin_register_duration_settings');

function quote_of_the_day_plugin_duration_section_callback()
{
	echo '<p>' . __('Set the duration for changing the quote:', 'quote_of_the_day_plugin_domain') . '</p>';
}

function quote_of_the_day_plugin_duration_field_callback($args)
{
	$duration = get_option('quote_duration', array('day' => 0, 'hour' => 0, 'minute' => 0));
	$value = isset($duration[$args['unit']]) ? $duration[$args['unit']] : 0;

	echo '<input type="number" min="0" name="quote_duration[' . esc_attr($args['unit']) . ']" value="' . esc_attr($value) . '" />';
}

///////////////////////////////////////////////////////////////// => QuoteSettingsModel.php
// Subpage 2: Short Code Settings Callback
function quote_of_the_day_plugin_shortcode_settings_page()
{
	require_once plugin_dir_path(__FILE__) . '../view/short-code.php';
}

///////////////////////////////////////////////////////////////// => QuoteSettingsModel.php
// Function to sanitize and display a quote
function quote_of_the_day_plugin_get_random_quote()
{
	// Get quotes from the 'quote' custom post type
	$args = array(
		'post_type'      => 'quote',
		'posts_per_page' => -1,
	);

	$quote_posts = get_posts($args);

	if (!$quote_posts) {
		return ''; // Return empty string if no quotes are found
	}

	// Get a random quote post
	$random_quote_post = $quote_posts[array_rand($quote_posts)];

	// Get the content of the quote post
	$quote_content = apply_filters('the_content', $random_quote_post->post_content);

	// Sanitize the quote content before displaying it
	$sanitized_quote_content = wp_kses_post($quote_content);

	return $sanitized_quote_content;
}
