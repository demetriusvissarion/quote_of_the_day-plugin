<?php

/**
 * Quote of the Day Plugin - Quotes Management
 */

// If this file is called directly, abort
defined('ABSPATH') or die("Hello there");

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
