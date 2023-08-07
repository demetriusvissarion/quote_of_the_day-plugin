<?php

/**
 * @package QuoteOfTheDayPlugin
 */
/*
Plugin Name: QuoteOfTheDay Plugin
Plugin URI: http://www.demetriusvissarion.com
Description: Custom Plugin
Version: 1.0.0
Author: Demetrius Vissarion
Author URI: https://github.com/demetriusvissarion
Licence: GPLv2 or later
Text Domain: quote_of_the_day-plugin
*/

/*
Copyright (c) 2023 Demetrius Vissarion

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

// If this file is called directly, abort
defined('ABSPATH') or die("Hello there");

// Enqueue necessary CSS styles
// function quote_of_the_day_plugin_enqueue_styles()
// {
// 	wp_enqueue_style('quote-of-the-day-admin', plugins_url('admin-style.css', __FILE__));
// }
// add_action('admin_enqueue_scripts', 'quote_of_the_day_plugin_enqueue_styles');

// Create the admin panel page
function quote_of_the_day_plugin_admin_menu()
{
	add_menu_page(
		'Widget: Quote of the Day',
		'Widget: Quote of the Day',
		'manage_options',
		'quote-of-the-day-settings',
		'quote_of_the_day_plugin_settings_page'
	);
}
add_action('admin_menu', 'quote_of_the_day_plugin_admin_menu');

// Admin settings page callback
function quote_of_the_day_plugin_settings_page()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	// Save the quote when the form is submitted
	if (isset($_POST['quote_of_the_day_submit'])) {
		// Verify the nonce to ensure security
		if (!isset($_POST['quote_of_the_day_nonce']) || !wp_verify_nonce($_POST['quote_of_the_day_nonce'], 'quote_of_the_day_settings')) {
			wp_die('Invalid nonce.');
		}

		$quote = isset($_POST['quote']) ? sanitize_textarea_field($_POST['quote']) : '';
		update_option('quote_of_the_day', $quote);
	}

	// Retrieve the saved quote
	$saved_quote = get_option('quote_of_the_day', '');

	// Display the settings page HTML
?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<form method="post">
			<label for="quote"><?php esc_html_e('Quote:', 'quote_of_the_day_plugin_domain'); ?></label>
			<textarea class="large-text" rows="5" cols="50" id="quote" name="quote"><?php echo esc_textarea($saved_quote); ?></textarea>
			<?php wp_nonce_field('quote_of_the_day_settings', 'quote_of_the_day_nonce'); ?>
			<input type="submit" name="quote_of_the_day_submit" class="button button-primary" value="<?php esc_attr_e('Save', 'quote_of_the_day_plugin_domain'); ?>" />
		</form>
	</div>
	<?php
}

// Function to sanitize and display a quote
function quote_of_the_day_plugin_get_random_quote()
{
	// Get your list of quotes (you can store them in an array or retrieve from the database)
	$quotes = array(
		'The important thing is not to stop questioning. - Albert Einstein',
		'The good thing about science is that it\'s true whether or not you believe in it. - Neil deGrasse Tyson',
		'The saddest aspect of life right now is that science gathers knowledge faster than society gathers wisdom. - Isaac Asimov',
		'The greatest enemy of knowledge is not ignorance, it is the illusion of knowledge. - Stephen Hawking',
		'The universe is under no obligation to make sense to you. - Neil deGrasse Tyson',
		'Science is not only a disciple of reason but, also, one of romance and passion. - Stephen Hawking',
		'The most beautiful thing we can experience is the mysterious. - Albert Einstein',
		'Somewhere, something incredible is waiting to be known. - Carl Sagan',
		'We are all connected; To each other, biologically. To the earth, chemically. To the rest of the universe atomically. - Neil deGrasse Tyson',
		'The cosmos is within us. We are made of star-stuff. We are a way for the universe to know itself. - Carl Sagan',
		'We are just an advanced breed of monkeys on a minor planet of a very average star. But we can understand the Universe. That makes us something very special. - Stephen Hawking',
		'The science of today is the technology of tomorrow. - Edward Teller',
		'Science knows no country because knowledge belongs to humanity, and is the torch which illuminates the world. - Louis Pasteur',
		'Science is the great antidote to the poison of enthusiasm and superstition. - Adam Smith',
		'Science is simply common sense at its best, that is, rigidly accurate in observation, and merciless to fallacy in logic. - Thomas Huxley',
		'The science of government is my duty. - Benjamin Franklin',
		'The most exciting phrase to hear in science, the one that heralds new discoveries, is not \'Eureka!\' but \'That\'s funny... - Isaac Asimov',
		'The art and science of asking questions is the source of all knowledge. - Thomas Berger',
		'I have no special talent. I am only passionately curious. - Albert Einstein',
		'The more I study science, the more I believe in God. - Albert Einstein'
	);

	// Sanitize the random quote before displaying it
	$random_quote = wp_kses_post($quotes[array_rand($quotes)]);

	return $random_quote;
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


// Create the Quote of the Day Widget
class Quote_Of_The_Day_Plugin_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct(
			'quote_of_the_day_plugin',
			__('Widget: Quote of the Day', 'quote_of_the_day_plugin_domain'),
			array('description' => __('Displays a random quote of the day', 'quote_of_the_day_plugin_domain'))
		);
	}

	public function widget($args, $instance)
	{
		echo $args['before_widget'];
		echo $args['before_title'] . esc_html__('Widget: Quote of the Day', 'quote_of_the_day_plugin_domain') . $args['after_title'];
		echo '<div class="quote-of-the-day">' . quote_of_the_day_plugin_get_random_quote() . '</div>';
		echo $args['after_widget'];
	}

	public function form($instance)
	{
		// Retrieve the currently saved widget settings
		$current_quote = isset($instance['quote']) ? $instance['quote'] : '';

		// Display the widget settings form
	?>
		<p>
			<label for="<?php echo $this->get_field_id('quote'); ?>"><?php esc_html_e('Quote:', 'quote_of_the_day_plugin_domain'); ?></label>
			<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('quote'); ?>" name="<?php echo $this->get_field_name('quote'); ?>"><?php echo esc_textarea($current_quote); ?></textarea>
		</p>
<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['quote'] = (!empty($new_instance['quote'])) ? sanitize_textarea_field($new_instance['quote']) : '';

		return $instance;
	}
}

// Register the widget
function register_quote_of_the_day_plugin_widget()
{
	register_widget('Quote_Of_The_Day_Plugin_Widget');
}
add_action('widgets_init', 'register_quote_of_the_day_plugin_widget');
