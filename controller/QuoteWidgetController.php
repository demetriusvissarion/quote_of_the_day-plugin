<?php

/**
 * @package QuoteOfTheDayPlugin
 */

///////////////////////////////////////////////////////////////////
// Register the widget
function register_quote_of_the_day_plugin_widget()
{
	register_widget('Quote_Of_The_Day_Plugin_Widget');
}
add_action('widgets_init', 'register_quote_of_the_day_plugin_widget');
