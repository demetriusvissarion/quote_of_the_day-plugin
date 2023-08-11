<?php

/**
 * @package QuoteOfTheDayPlugin
 */
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