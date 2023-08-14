<?php

/**
 * @package QuoteOfTheDayPlugin
 */

require_once __DIR__ . '/../model/QuotesSettingsModel.php';

// Instantiate the Quotes Settings Model
$model = new QuotesSettingsModel();

// Create the admin menu "Quote Settings" page and subpages
add_action('admin_menu', array($model, 'quote_of_the_day_plugin_quote_settings'));

// Register duration settings
add_action('admin_init', array($model, 'quote_of_the_day_plugin_register_duration_settings'));

// Enqueue the toggle menu JavaScript in the existing function
add_action('admin_enqueue_scripts', array($model, 'quote_of_the_day_toggle_menu_js'));

class QuoteSettingsController
{
	/////////////////////////////////////////////////////////////////
	// Subpage 1: Duration Settings Callback
	function quote_of_the_day_plugin_duration_settings_page()
	{
		// Check if settings were saved and display a success message
		if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
			add_settings_error('quote_duration_settings', 'settings_updated', __('Changes saved.', 'quote_of_the_day_plugin_domain'), 'updated');
		}

		require_once plugin_dir_path(__FILE__) . '../view/duration.php';
	}
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
	/////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////
	// Subpage 2: Short Code Settings Callback
	function quote_of_the_day_plugin_shortcode_settings_page()
	{
		require_once plugin_dir_path(__FILE__) . '../view/short-code.php';
	}
	/////////////////////////////////////////////////////////////////
}
