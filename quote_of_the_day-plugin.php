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

///////////////////////////////////////////////////////
// Create the admin panel page and subpages
function quote_of_the_day_plugin_admin_menu()
{
	// Main Settings Page
	add_menu_page(
		'Quote Settings',
		'Quote Settings',
		'manage_options',
		'quote-of-the-day-settings',
		'quote_of_the_day_plugin_settings_page',
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
		'quote_of_the_day_plugin_duration_settings_page'
	);

	// Subpage 2: Short Code Settings
	add_submenu_page(
		'quote-of-the-day-settings',
		'Short Code',
		'Short Code',
		'manage_options',
		'quote-of-the-day-shortcode-settings',
		'quote_of_the_day_plugin_shortcode_settings_page'
	);

	// Enqueue JavaScript for the toggle button
	add_action('admin_enqueue_scripts', 'quote_of_the_day_toggle_menu_js');
}
add_action('admin_menu', 'quote_of_the_day_plugin_admin_menu');

///////////////////////////////////////////////////
// Main Settings Page Callback
function quote_of_the_day_plugin_settings_page()
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
?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<p><?php esc_html_e('Welcome to the Quote Settings! Here you can manage various options for the Quote of the Day plugin.', 'quote_of_the_day_plugin_domain'); ?></p>

		<form method="post" action="">
			<?php wp_nonce_field('quote_settings_nonce', 'quote_settings_nonce'); ?>

			<!-- ON/OFF switch button for Quotes Management Menu -->
			<p>
				<label class="bootstrap-switch-label">
					<input type="hidden" name="quote_menu_enabled" value="0">
					<input type="checkbox" id="quote_menu_enabled" name="quote_menu_enabled" value="1" <?php checked($quote_menu_enabled, true); ?>>
					<?php esc_html_e('Quotes Management Menu', 'quote_of_the_day_plugin_domain'); ?>
				</label>
			</p>

			<!-- ON/OFF switch button for Quotes Widget -->
			<p>
				<label class="bootstrap-switch-label">
					<input type="hidden" name="quote_widget_enabled" value="0">
					<input type="checkbox" id="quote_widget_enabled" name="quote_widget_enabled" value="1" <?php checked($quote_widget_enabled, true); ?>>
					<?php esc_html_e('Quotes Widget', 'quote_of_the_day_plugin_domain'); ?>
				</label>
			</p>

			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'quote_of_the_day_plugin_domain'); ?>">
			</p>
		</form>
		<?php settings_errors('quote_settings'); ?>
	</div>
<?php
}


// Register plugin settings
function quote_of_the_day_register_settings()
{
	register_setting('quote_settings_group', 'quote_menu_enabled');
	register_setting('quote_settings_group', 'quote_widget_enabled');
}
add_action('admin_init', 'quote_of_the_day_register_settings');

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


//////////////////////////////////////////////////////
// Subpage 1: Duration Settings Callback
function quote_of_the_day_plugin_duration_settings_page()
{
	// Check if settings were saved and display a success message
	if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
		add_settings_error('quote_duration_settings', 'settings_updated', __('Changes saved.', 'quote_of_the_day_plugin_domain'), 'updated');
	}

?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<?php settings_errors('quote_duration_settings'); ?>
		<form method="post" action="options.php">
			<?php
			settings_fields('quote_of_the_day_duration_group');
			do_settings_sections('quote-of-the-day-duration-settings');
			submit_button();
			?>
		</form>
	</div>
<?php
}

function quote_of_the_day_plugin_register_duration_settings()
{
	add_settings_section(
		'quote_of_the_day_duration_section',
		__('', 'quote_of_the_day_plugin_domain'),
		'quote_of_the_day_plugin_duration_section_callback',
		'quote-of-the-day-duration-settings'
	);

	add_settings_field(
		'quote_duration_days',
		__('Quote Display Duration (Days)', 'quote_of_the_day_plugin_domain'),
		'quote_of_the_day_plugin_duration_field_callback',
		'quote-of-the-day-duration-settings',
		'quote_of_the_day_duration_section',
		array('unit' => 'day')
	);

	add_settings_field(
		'quote_duration_hours',
		__('Quote Display Duration (Hours)', 'quote_of_the_day_plugin_domain'),
		'quote_of_the_day_plugin_duration_field_callback',
		'quote-of-the-day-duration-settings',
		'quote_of_the_day_duration_section',
		array('unit' => 'hour')
	);

	add_settings_field(
		'quote_duration_minutes',
		__('Quote Display Duration (Minutes)', 'quote_of_the_day_plugin_domain'),
		'quote_of_the_day_plugin_duration_field_callback',
		'quote-of-the-day-duration-settings',
		'quote_of_the_day_duration_section',
		array('unit' => 'minute')
	);

	register_setting('quote_of_the_day_duration_group', 'quote_duration', 'quote_of_the_day_validate_duration');
}
add_action('admin_init', 'quote_of_the_day_plugin_register_duration_settings');

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

/////////////////////////////////////////////////////////////////
// Subpage 2: Short Code Settings Callback
function quote_of_the_day_plugin_shortcode_settings_page()
{
?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<p><?php esc_html_e('Copy the following shortcode to display the quote on any page or post:', 'quote_of_the_day_plugin_domain'); ?></p>
		<code>[quote_of_the_day]</code>
	</div>
	<?php
}
/////////////////////////////////////////////////////////////////////////

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
		$quote_widget_enabled = get_option('quote_widget_enabled', true);

		if ($quote_widget_enabled) {
			$quote = get_transient('quote_of_the_day_transient');

			if (false === $quote) {
				$quote = quote_of_the_day_plugin_get_random_quote();
				$duration = get_option('quote_duration', array('day' => 0, 'hour' => 0, 'minute' => 0));

				$expiration = $duration['day'] * 86400 + $duration['hour'] * 3600 + $duration['minute'] * 60;
				set_transient('quote_of_the_day_transient', $quote, $expiration);
			}

			echo $args['before_widget'];
			echo $args['before_title'] . esc_html__('Widget: Quote of the Day', 'quote_of_the_day_plugin_domain') . $args['after_title'];
			echo '<div class="quote">' . wp_kses_post($quote) . '</div>';
			echo $args['after_widget'];
		}
	}

	public function form($instance)
	{
		// Retrieve the currently saved widget settings
		$current_quote = isset($instance['quote']) ? $instance['quote'] : '';
		$widget_option = get_option('quote_widget_enabled', true);

		// Display the widget settings form
	?>
		<p>
			<label for="<?php echo $this->get_field_id('quote'); ?>"><?php esc_html_e('Quote:', 'quote_of_the_day_plugin_domain'); ?></label>
			<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('quote'); ?>" name="<?php echo $this->get_field_name('quote'); ?>"><?php echo esc_textarea($current_quote); ?></textarea>
		</p>
		<p>
			<label class="bootstrap-switch-label">
				<input type="checkbox" id="<?php echo $this->get_field_id('widget_enabled'); ?>" name="<?php echo $this->get_field_name('widget_enabled'); ?>" value="1" <?php checked($widget_option, true); ?>>
				<?php esc_html_e('Quotes Widget', 'quote_of_the_day_plugin_domain'); ?>
			</label>
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

////////////////////////////////////////////////////////////////
// Register the 'quotes' custom post type
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
add_action('init', 'quote_of_the_day_plugin_register_post_type');

// Create the admin panel page for managing quotes
function quote_of_the_day_plugin_quotes_page()
{
	// Check if the user has the required capability
	if (!current_user_can('manage_options')) {
		return;
	}

	// Handle actions like adding, editing, or deleting quotes
	// You'll need to implement the logic for these actions here

	?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<!-- Add your HTML and logic here to display and manage quotes -->
	</div>
	<?php
}

// Create the admin panel page for managing quotes
function quote_of_the_day_manage_quotes_menu()
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
add_action('admin_menu', 'quote_of_the_day_manage_quotes_menu');


// Callback function to display the quotes admin page
function quote_of_the_day_manage_quotes_page()
{
	$quote_menu_enabled = get_option('quote_menu_enabled', true);

	if ($quote_menu_enabled) {
	?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php echo esc_html__('Quotes', 'quote_of_the_day_plugin_domain'); ?></h1>
			<a href="<?php echo admin_url('post-new.php?post_type=quote'); ?>" class="page-title-action">
				<?php echo esc_html__('Add New', 'quote_of_the_day_plugin_domain'); ?>
			</a>
			<?php
			$args = array(
				'post_type'      => 'quote',
				'posts_per_page' => -1,
			);

			$quotes = get_posts($args);

			if ($quotes) {
				echo '<table class="wp-list-table widefat fixed striped">';
				echo '<thead><tr>';
				echo '<th>' . esc_html__('Title', 'quote_of_the_day_plugin_domain') . '</th>';
				echo '<th>' . esc_html__('Author', 'quote_of_the_day_plugin_domain') . '</th>';
				echo '<th>' . esc_html__('Date', 'quote_of_the_day_plugin_domain') . '</th>';
				echo '<th></th>';
				echo '</tr></thead>';
				echo '<tbody>';

				foreach ($quotes as $quote) {
					echo '<tr>';
					echo '<td>' . esc_html(get_the_title($quote)) . '</td>';
					echo '<td>' . esc_html(get_the_author_meta('display_name', $quote->post_author)) . '</td>';
					echo '<td>' . esc_html(get_the_date('', $quote)) . '</td>';
					echo '<td>';
					echo '<a href="' . get_edit_post_link($quote->ID) . '">' . esc_html__('Edit', 'quote_of_the_day_plugin_domain') . '</a> | ';
					echo '<a href="' . get_delete_post_link($quote->ID) . '" class="submitdelete">' . esc_html__('Delete', 'quote_of_the_day_plugin_domain') . '</a>';
					echo '</td>';
					echo '</tr>';
				}

				echo '</tbody>';
				echo '</table>';
			} else {
				echo esc_html__('No quotes found.', 'quote_of_the_day_plugin_domain');
			}
			?>
		</div>
<?php
	}
}
