<?php

/**
 * @package QuoteOfTheDayPlugin
 */

require_once __DIR__ . '/../model/QuotesManagementModel.php';

class QuoteManagementController
{
	public $management_model;

	public function __construct($management_model)
	{
		$this->management_model = $management_model;

		// Register the "Quotes Management" custom post type
		add_action('init', array($management_model, 'quote_of_the_day_plugin_register_post_type'));

		// Create the admin panel "Quotes Management" page
		add_action('admin_menu', array($management_model, 'quote_of_the_day_plugin_quotes_management'));
	}
}

// Instantiate the Quote Management Controller
$controller = new QuoteManagementController($management_model);
