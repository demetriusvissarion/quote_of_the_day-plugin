<?php
if (isset($_POST['action']) && $_POST['action'] === 'update_widget_option') {
	update_option('quote_widget_enabled', $_POST['widget_option']);
}
