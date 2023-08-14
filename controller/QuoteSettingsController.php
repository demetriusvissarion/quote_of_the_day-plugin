<?php

/**
 * @package QuoteOfTheDayPlugin
 */

require_once __DIR__ . '/../model/QuotesSettingsModel.php';

class QuoteSettingsController
{
	public $settings_model;

	public function __construct($settings_model)
	{
		$this->settings_model = $settings_model;

		// var_dump($settings_model);

		// Create the admin menu "Quote Settings" page and subpages
		add_action('admin_menu', array($settings_model, 'quote_of_the_day_plugin_quote_settings'));

		// Register duration settings
		add_action('admin_init', array($settings_model, 'quote_of_the_day_plugin_register_duration_settings'));

		// Enqueue the toggle menu JavaScript in the existing function
		add_action('admin_enqueue_scripts', array($settings_model, 'quote_of_the_day_toggle_menu_js'));
	}
}

// Instantiate the Quotes Settings Controller
$controller = new QuoteSettingsController($settings_model);
