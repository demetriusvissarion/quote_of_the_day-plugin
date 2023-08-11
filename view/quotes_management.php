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