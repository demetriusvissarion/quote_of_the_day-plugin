<?php

/**
 * @package QuoteOfTheDayPlugin
 */


///////////////////////////////////////////////////////
class QuotesSettingsModel
{
	// Create the admin menu "Quote Settings" page and subpages
	public function quote_of_the_day_plugin_quote_settings()
	{
		// Main Settings Page
		add_menu_page(
			'Quote Settings',
			'Quote Settings',
			'manage_options',
			'quote-of-the-day-settings',
			array($this, 'quote_of_the_day_plugin_quote_settings_page'),
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
			array($this, 'quote_of_the_day_plugin_duration_settings_page'),
		);

		// Subpage 2: Short Code Settings
		add_submenu_page(
			'quote-of-the-day-settings',
			'Short Code',
			'Short Code',
			'manage_options',
			'quote-of-the-day-shortcode-settings',
			array($this, 'quote_of_the_day_plugin_shortcode_settings_page'),
		);
	}

	/////////////////////////////////////////////////////////////////
	// Subpage 1: Duration Settings Callback
	public function quote_of_the_day_plugin_duration_settings_page()
	{
		// Check if settings were saved and display a success message
		if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
			add_settings_error('quote_duration_settings', 'settings_updated', __('Changes saved.', 'quote_of_the_day_plugin_domain'), 'updated');
		}

		require_once plugin_dir_path(__FILE__) . '../view/duration.php';
	}

	public function quote_of_the_day_plugin_duration_section_callback()
	{
		echo '<p>' . __('Set the duration for changing the quote:', 'quote_of_the_day_plugin_domain') . '</p>';
	}

	public function quote_of_the_day_plugin_duration_field_callback($args)
	{
		$duration = get_option('quote_duration', array('day' => 0, 'hour' => 0, 'minute' => 0));
		$value = isset($duration[$args['unit']]) ? $duration[$args['unit']] : 0;

		echo '<input type="number" min="0" name="quote_duration[' . esc_attr($args['unit']) . ']" value="' . esc_attr($value) . '" />';
	}
	/////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////
	// Subpage 2: Short Code Settings Callback
	public function quote_of_the_day_plugin_shortcode_settings_page()
	{
		require_once plugin_dir_path(__FILE__) . '../view/short-code.php';
	}

	public function widget_form()
	{
		ob_start();
		require_once plugin_dir_path(__FILE__) . '../view/short-code.php';
		return ob_get_clean();
	}

	/////////////////////////////////////////////////////////////////

	// "Quote Settings" Page Callback
	public function quote_of_the_day_plugin_quote_settings_page()
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

	// Enqueue JavaScript for the toggle buttons in the "Quote Settings" page
	public function quote_of_the_day_toggle_menu_js($hook)
	{
		if ($hook === 'toplevel_page_quote-of-the-day-settings') {
			// Enqueue Bootstrap Switch CSS and JS
			wp_enqueue_style('bootstrap-switch', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css');
			wp_enqueue_script('bootstrap-switch', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js', array('jquery'), '3.3.4', true);

			// Enqueue your custom JavaScript
			wp_enqueue_script('quote-of-the-day-toggle-menu', plugin_dir_url(__FILE__) . '../view/toggle-menu.js', array('jquery'), '1.0', true);
		}
	}

	//////////////////////////////////////////////////////
	// Subpage 1: Duration Settings Callback
	public function quote_of_the_day_plugin_register_duration_settings()
	{
		add_settings_section(
			'quote_of_the_day_duration_section',
			__('', 'quote_of_the_day_plugin_domain'),
			array($this, 'quote_of_the_day_plugin_duration_section_callback'),
			'quote-of-the-day-duration-settings'
		);

		add_settings_field(
			'quote_duration_days',
			__('Quote Display Duration (Days)', 'quote_of_the_day_plugin_domain'),
			array($this, 'quote_of_the_day_plugin_duration_field_callback'),
			'quote-of-the-day-duration-settings',
			'quote_of_the_day_duration_section',
			array('unit' => 'day')
		);

		add_settings_field(
			'quote_duration_hours',
			__('Quote Display Duration (Hours)', 'quote_of_the_day_plugin_domain'),
			array($this, 'quote_of_the_day_plugin_duration_field_callback'),
			'quote-of-the-day-duration-settings',
			'quote_of_the_day_duration_section',
			array('unit' => 'hour')
		);

		add_settings_field(
			'quote_duration_minutes',
			__('Quote Display Duration (Minutes)', 'quote_of_the_day_plugin_domain'),
			array($this, 'quote_of_the_day_plugin_duration_field_callback'),
			'quote-of-the-day-duration-settings',
			'quote_of_the_day_duration_section',
			array('unit' => 'minute')
		);

		register_setting('quote_of_the_day_duration_group', 'quote_duration', 'quote_of_the_day_validate_duration');
	}
}

// Instantiate the Quotes Settings Model
$settings_model = new QuotesSettingsModel();
