<?php

/**
 * Quote of the Day Plugin - Admin Menu
 */

// If this file is called directly, abort
defined('ABSPATH') or die("Hello there");


// Include other plugin files
require_once plugin_dir_path(__FILE__) . 'enqueue-scripts.php';

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

	// Subpage 3: Localisation Support Settings
	add_submenu_page(
		'quote-of-the-day-settings',
		'Localisation Support',
		'Localisation Support',
		'manage_options',
		'quote-of-the-day-localisation-settings',
		'quote_of_the_day_plugin_localisation_settings_page'
	);

	// Enqueue JavaScript for the toggle button
	add_action('admin_enqueue_scripts', 'quote_of_the_day_toggle_menu_js');
}
add_action('admin_menu', 'quote_of_the_day_plugin_admin_menu');

///////////////////////////////////////////////////
// Main Settings Page Callback
function quote_of_the_day_plugin_settings_page()
{
	if (isset($_POST['quote_menu_enabled']) || isset($_POST['quote_widget_enabled'])) {
		update_option('quote_menu_enabled', isset($_POST['quote_menu_enabled']) ? true : false);
		update_option('quote_widget_enabled', isset($_POST['quote_widget_enabled']) ? true : false);
		add_settings_error('quote_settings', 'settings_updated', __('Changes saved.', 'quote_of_the_day_plugin_domain'), 'updated');
	}

	$quote_menu_enabled = get_option('quote_menu_enabled', true);
	$quote_widget_enabled = get_option('quote_widget_enabled', true);
?>

	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<p><?php esc_html_e('Welcome to the Quote Settings! Here you can manage various options for the Quote of the Day plugin.', 'quote_of_the_day_plugin_domain'); ?></p>

		<form method="post" action="">
			<!-- ON/OFF switch button for Quotes Management Menu -->
			<label class="bootstrap-switch-label">
				<input type="hidden" name="quote_menu_enabled" value="0">
				<input type="checkbox" id="quote_menu_enabled" name="quote_menu_enabled" value="1" <?php checked($quote_menu_enabled, '1'); ?>>
				<?php esc_html_e('Quotes Management Menu', 'quote_of_the_day_plugin_domain'); ?>
			</label>

			<!-- ON/OFF switch button for Quotes Widget -->
			<p>
				<label class="bootstrap-switch-label">
					<!-- Hidden input to handle unchecked state -->
					<input type="hidden" name="<?php echo esc_attr('quote_widget_enabled'); ?>" value="0">

					<!-- Actual switch input -->
					<input type="checkbox" id="<?php echo esc_attr('quote_widget_enabled'); ?>" name="<?php echo esc_attr('quote_widget_enabled'); ?>" value="1" <?php checked(get_option('quote_widget_enabled', true), true); ?>>

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

/////////////////   09/08/2023 - 10:25   ///////////////////

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

/////////////////////////////////////////////////////////////////
// Subpage 3: Localisation Support Settings Callback
function quote_of_the_day_plugin_localisation_settings_page()
{
?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<p><?php esc_html_e('The Quote of the Day plugin provides localisation support. You can translate the plugin into different languages by providing translation files (.mo and .po).', 'quote_of_the_day_plugin_domain'); ?></p>
		<!-- Add your localisation settings page HTML here -->
	</div>
<?php
}
/////////////////////////////////////////////////////////////////////////
?>