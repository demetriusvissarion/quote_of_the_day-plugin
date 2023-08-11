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