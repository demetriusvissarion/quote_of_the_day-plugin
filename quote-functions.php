<?php

/**
 * Quote of the Day Plugin - Quote Functions
 */

// ob_start();

// If this file is called directly, abort
defined('ABSPATH') or die("Hello there");

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


// Schedule the quote update every hour
function quote_of_the_day_plugin_schedule_hourly_event()
{
	if (!wp_next_scheduled('quote_of_the_day_update')) {
		wp_schedule_event(time(), 'hourly', 'quote_of_the_day_update');
	}
}
add_action('wp', 'quote_of_the_day_plugin_schedule_hourly_event');

// Update the quote on the scheduled event
function quote_of_the_day_plugin_update_quote()
{
	// Get a new random quote
	$new_quote = quote_of_the_day_plugin_get_random_quote();

	// Update the quote in the database (you can store it as an option or in the database)
	update_option('quote_of_the_day', $new_quote);
}
add_action('quote_of_the_day_update', 'quote_of_the_day_plugin_update_quote');

// // Function to retrieve the current quote from the database
// function quote_of_the_day_plugin_get_current_quote()
// {
// 	return get_option('quote_of_the_day', '');
// }

// ob_end_flush();
