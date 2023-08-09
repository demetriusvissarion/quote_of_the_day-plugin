<?php

/**
 * Quote of the Day Plugin - Widget Class
 */

// ob_start();

// If this file is called directly, abort
defined('ABSPATH') or die("Hello there");

// Include the necessary function from quote-functions.php
require_once plugin_dir_path(__FILE__) . 'quote-functions.php';


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

// ob_end_flush();

?>