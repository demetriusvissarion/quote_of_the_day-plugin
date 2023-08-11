<?php

/**
 * @package QuoteOfTheDayPlugin
 */


/////////////////////////////////////////////////////// => QuoteSettingsModel.php
// Create the admin menu Quote Settings page and subpages
function quote_of_the_day_plugin_quote_settings()
{
	// Main Settings Page
	add_menu_page(
		'Quote Settings',
		'Quote Settings',
		'manage_options',
		'quote-of-the-day-settings',
		'quote_of_the_day_plugin_quote_settings_page',
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
add_action('admin_menu', 'quote_of_the_day_plugin_quote_settings');
///////////////////////////////////////////////////////

/////////////////////////////////////////////////// => QuoteSettingsModel.php
// Quote Settings Page Callback
function quote_of_the_day_plugin_quote_settings_page()
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
	require_once plugin_dir_path(__FILE__) . '../view/quote_settings.php';
}

///////////////////////////////////////////////////////// => QuoteSettingsModel.php
// Enqueue JavaScript for the toggle buttons in Quote Settings
function quote_of_the_day_toggle_menu_js($hook)
{
	if ($hook === 'toplevel_page_quote-of-the-day-settings') {
		// Enqueue Bootstrap Switch CSS and JS
		wp_enqueue_style('bootstrap-switch', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css');
		wp_enqueue_script('bootstrap-switch', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js', array('jquery'), '3.3.4', true);

		// Enqueue your custom JavaScript
		wp_enqueue_script('quote-of-the-day-toggle-menu', plugin_dir_url(__FILE__) . '../view/toggle-menu.js', array('jquery'), '1.0', true);
	}
}

// Enqueue the toggle menu JavaScript in the existing function
add_action('admin_enqueue_scripts', 'quote_of_the_day_toggle_menu_js');


////////////////////////////////////////////////////// => QuoteSettingsModel.php
// Subpage 1: Duration Settings Callback
function quote_of_the_day_plugin_duration_settings_page()
{
	// Check if settings were saved and display a success message
	if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
		add_settings_error('quote_duration_settings', 'settings_updated', __('Changes saved.', 'quote_of_the_day_plugin_domain'), 'updated');
	}

	require_once plugin_dir_path(__FILE__) . '../view/duration.php';
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

///////////////////////////////////////////////////////////////// => QuoteSettingsModel.php
// Subpage 2: Short Code Settings Callback
function quote_of_the_day_plugin_shortcode_settings_page()
{
	require_once plugin_dir_path(__FILE__) . '../view/short-code.php';
}

///////////////////////////////////////////////////////////////// => QuoteSettingsModel.php
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

///////////////////////////////////////////////////////////////// => WidgetModel.php
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

		// Display the widget form
		require_once plugin_dir_path(__FILE__) . '../view/widget.php';
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


//////////////////////////////////////////////////// => QuotesManagementModel.php
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
add_action('init', 'quote_of_the_day_plugin_register_post_type');

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
add_action('admin_menu', 'quote_of_the_day_plugin_quotes_management');


// Callback function to display the "Quotes Management" page
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
			?>
				<!-- <p class="search-box">
					<label class="screen-reader-text" for="post-search-input">Search Quotes:</label>
					<input type="search" id="post-search-input" name="s" value="">
					<input type="submit" id="search-submit" class="button" value="Search Quotes">
				</p> -->

				<div class="tablenav top">

					<div class="alignleft actions bulkactions">
						<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label><select name="action" id="bulk-action-selector-top">
							<option value="-1">Bulk actions</option>
							<option value="trash">Move to Trash</option>
						</select>
						<input type="submit" id="doaction" class="button action" value="Apply">
					</div>

					<div class="alignleft actions">
						<label for="filter-by-date" class="screen-reader-text">Filter by date</label>
						<select name="m" id="filter-by-date">
							<option selected="selected" value="0">All dates</option>
							<option value="202307">July 2023</option>
						</select>
						</select>
						<input type="submit" name="filter_action" id="post-query-submit" class="button" value="Filter">
					</div>

					<br class="clear">
				</div>

				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<td id="cb" class="manage-column column-cb check-column"><label class="label-covers-full-cell" for="cb-select-all-1"><span class="screen-reader-text">Select All</span></label><input id="cb-select-all-1" type="checkbox"></td>
							<th scope="col" id="title" class="manage-column column-title column-primary sortable desc" abbr="Title">Title</th>
							<th scope="col" id="author" class="manage-column column-author">Author</th>
							<th scope="col" id="date" class="manage-column column-date sorted desc" aria-sort="descending" abbr="Date"><a href="http://one.wordpress.test/wp-admin/edit.php?orderby=date&amp;order=asc"><span>Date</span></a></th>
							<th scope="col" id="action" class="manage-column column-action">Action</th>

						</tr>
					</thead>

					<?php

					echo '<tbody>';

					foreach ($quotes as $quote) {
						echo '<tr>';
					?>
						<th scope="row" class="check-column"> <label class="label-covers-full-cell" for="cb-select-17">
								<span class="screen-reader-text">
									Select Sample Title 1 </span>
							</label>
							<input id="cb-select-17" type="checkbox" name="post[]" value="17">
							<div class="locked-indicator">
								<span class="locked-indicator-icon" aria-hidden="true"></span>
								<span class="screen-reader-text">
									“Sample Title 1” is locked </span>
							</div>
						</th>
				<?php
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
