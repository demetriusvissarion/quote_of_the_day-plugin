<?php

/**
 * @package QuoteOfTheDayPlugin
 */

require_once __DIR__ . '/../model/QuotesSettingsModel.php';

class QuoteSettingsController
{
	public $model;

	public function __construct($model)
	{
		$this->model = $model;

		// var_dump($model);

		// Create the admin menu "Quote Settings" page and subpages
		add_action('admin_menu', array($model, 'quote_of_the_day_plugin_quote_settings'));

		// Register duration settings
		add_action('admin_init', array($model, 'quote_of_the_day_plugin_register_duration_settings'));

		// Enqueue the toggle menu JavaScript in the existing function
		add_action('admin_enqueue_scripts', array($model, 'quote_of_the_day_toggle_menu_js'));
	}
}

// Instantiate the Quotes Settings Controller
$controller = new QuoteSettingsController($model);
