<?php

/**
 * Quote of the Day Plugin - Enqueue Scripts
 */

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
/////////////////////////////////////////////////////////


// Enqueue necessary JavaScript for the admin datepicker
function quote_of_the_day_admin_datepicker_js()
{
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('quote-of-the-day-datepicker', plugins_url('/assets/js/datepicker.js', __FILE__), array('jquery', 'jquery-ui-datepicker'), '', true);

	wp_localize_script('quote-of-the-day-datepicker', 'datepickerL10n', array(
		'closeText'         => __('Done', 'quote_of_the_day_plugin_domain'),
		'currentText'       => __('Today', 'quote_of_the_day_plugin_domain'),
		'monthNames'        => array(
			__('January', 'quote_of_the_day_plugin_domain'),
			__('February', 'quote_of_the_day_plugin_domain'),
			__('March', 'quote_of_the_day_plugin_domain'),
			__('April', 'quote_of_the_day_plugin_domain'),
			__('May', 'quote_of_the_day_plugin_domain'),
			__('June', 'quote_of_the_day_plugin_domain'),
			__('July', 'quote_of_the_day_plugin_domain'),
			__('August', 'quote_of_the_day_plugin_domain'),
			__('September', 'quote_of_the_day_plugin_domain'),
			__('October', 'quote_of_the_day_plugin_domain'),
			__('November', 'quote_of_the_day_plugin_domain'),
			__('December', 'quote_of_the_day_plugin_domain'),
		),
		'monthNamesShort'   => array(
			__('Jan', 'quote_of_the_day_plugin_domain'),
			__('Feb', 'quote_of_the_day_plugin_domain'),
			__('Mar', 'quote_of_the_day_plugin_domain'),
			__('Apr', 'quote_of_the_day_plugin_domain'),
			__('May', 'quote_of_the_day_plugin_domain'),
			__('Jun', 'quote_of_the_day_plugin_domain'),
			__('Jul', 'quote_of_the_day_plugin_domain'),
			__('Aug', 'quote_of_the_day_plugin_domain'),
			__('Sep', 'quote_of_the_day_plugin_domain'),
			__('Oct', 'quote_of_the_day_plugin_domain'),
			__('Nov', 'quote_of_the_day_plugin_domain'),
			__('Dec', 'quote_of_the_day_plugin_domain'),
		),
		'dayNames'          => array(
			__('Sunday', 'quote_of_the_day_plugin_domain'),
			__('Monday', 'quote_of_the_day_plugin_domain'),
			__('Tuesday', 'quote_of_the_day_plugin_domain'),
			__('Wednesday', 'quote_of_the_day_plugin_domain'),
			__('Thursday', 'quote_of_the_day_plugin_domain'),
			__('Friday', 'quote_of_the_day_plugin_domain'),
			__('Saturday', 'quote_of_the_day_plugin_domain'),
		),
		'dayNamesShort'     => array(
			__('Sun', 'quote_of_the_day_plugin_domain'),
			__('Mon', 'quote_of_the_day_plugin_domain'),
			__('Tue', 'quote_of_the_day_plugin_domain'),
			__('Wed', 'quote_of_the_day_plugin_domain'),
			__('Thu', 'quote_of_the_day_plugin_domain'),
			__('Fri', 'quote_of_the_day_plugin_domain'),
			__('Sat', 'quote_of_the_day_plugin_domain'),
		),
		'dayNamesMin'       => array(
			__('Su', 'quote_of_the_day_plugin_domain'),
			__('Mo', 'quote_of_the_day_plugin_domain'),
			__('Tu', 'quote_of_the_day_plugin_domain'),
			__('We', 'quote_of_the_day_plugin_domain'),
			__('Th', 'quote_of_the_day_plugin_domain'),
			__('Fr', 'quote_of_the_day_plugin_domain'),
			__('Sa', 'quote_of_the_day_plugin_domain'),
		),
		'weekHeader'        => __('Wk', 'quote_of_the_day_plugin_domain'),
		'dateFormat'        => __('yy-mm-dd', 'quote_of_the_day_plugin_domain'),
		'firstDay'          => get_option('start_of_week'),
		'isRTL'             => is_rtl(),
		'showMonthAfterYear' => false,
		'yearSuffix'        => '',
		'prevText'          => __('Previous', 'quote_of_the_day_plugin_domain'),
		'nextText'          => __('Next', 'quote_of_the_day_plugin_domain'),
		'currentText'       => __('Today', 'quote_of_the_day_plugin_domain'),
		'monthYearText'     => __('Choose a month and year', 'quote_of_the_day_plugin_domain'),
		'yearSuffix'        => ''
	));
}
