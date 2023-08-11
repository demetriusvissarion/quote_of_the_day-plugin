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