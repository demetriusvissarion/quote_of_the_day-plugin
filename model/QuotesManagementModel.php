<?php

/**
 * @package QuoteOfTheDayPlugin
 */


////////////////////////////////////////////////////
// Register the "Quotes Management" custom post type
function quote_of_the_day_plugin_register_post_type()
{
	$labels = array(
		'name'               => __('Quotes', 'quote_of_the_day_plugin_domain'),
		'singular_name'      => __('Quote', 'quote_of_the_day_plugin_domain'),
		'menu_name'          => __('Quotes', 'quote_of_the_day_plugin_domain'),
		'add_new'            => __('Add New', 'quote_of_the_day_plugin_domain'),
		'add_new_item'       => __('Add New Quote', 'quote_of_the_day_plugin_domain'),
		'edit'               => __('Edit', 'quote_of_the_day_plugin_domain'),
		'edit_item'          => __('Edit Quote', 'quote_of_the_day_plugin_domain'),
		'new_item'           => __('New Quote', 'quote_of_the_day_plugin_domain'),
		'view'               => __('View Quote', 'quote_of_the_day_plugin_domain'),
		'view_item'          => __('View Quote', 'quote_of_the_day_plugin_domain'),
		'search_items'       => __('Search Quotes', 'quote_of_the_day_plugin_domain'),
		'not_found'          => __('No quotes found', 'quote_of_the_day_plugin_domain'),
		'not_found_in_trash' => __('No quotes found in Trash', 'quote_of_the_day_plugin_domain'),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'has_archive'         => true,
		'publicly_queryable'  => true,
		'query_var'           => true,
		'rewrite'             => array('slug' => 'quotes'),
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'menu_position'       => 20,
		'supports'            => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
		'show_in_menu'        => false,
	);

	register_post_type('quote', $args);
}


// Create the admin panel "Quotes Management" page
function quote_of_the_day_plugin_quotes_management()
{
	$quote_menu_enabled = get_option('quote_menu_enabled', true);

	add_menu_page(
		'Quotes',                // Page Title
		'Quotes Management',                // Menu Title
		'manage_options',
		'quote-of-the-day-quotes', // Menu Slug
		'quote_of_the_day_manage_quotes_page', // Callback function to display the content
		'dashicons-format-quote', // Icon
		30 // Position in the admin menu
	);

	if (!$quote_menu_enabled) {
		remove_menu_page('quote-of-the-day-quotes');
	}
}
