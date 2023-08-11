<?php

/**
 * @package QuoteOfTheDayPlugin
 */

// Create the admin menu "Quote Settings" page and subpages
add_action('admin_menu', 'quote_of_the_day_plugin_quote_settings');

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
add_action('admin_init', 'quote_of_the_day_plugin_register_duration_settings');
/////////////////////////////////////////////////////////////////

// Enqueue the toggle menu JavaScript in the existing function
add_action('admin_enqueue_scripts', 'quote_of_the_day_toggle_menu_js');

/////////////////////////////////////////////////////////////////
// Subpage 2: Short Code Settings Callback
function quote_of_the_day_plugin_shortcode_settings_page()
{
	require_once plugin_dir_path(__FILE__) . '../view/short-code.php';
}
/////////////////////////////////////////////////////////////////

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
