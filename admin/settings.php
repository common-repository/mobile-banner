<?php
function mobilebanner_admin() {
	add_submenu_page ('options-general.php', 'Mobile Banner', 'Mobile Banner', 'manage_options', 'mobilebanner_config', 'mobilebanner_config_page');
}

function mobilebanner_config_page() {
	// Check that the user has the required capability
	if (!current_user_can('manage_options'))
		wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'mobile-banner'));

	// variables for the field and option names
	$hidden_field_name = 'mobilebanner_submit_hidden';

	$bgcolour_name = 'mobilebanner_bgcolour';
	$textcolour_name = 'mobilebanner_textcolour';
	$text_name = 'mobilebanner_text';
	$url_name = 'mobilebanner_url';
	$width_name = 'mobilebanner_width';
	$height_name = 'mobilebanner_height';
	$chkclose_name = 'mobilebanner_chkclose';
	$cookiedays_name = 'mobilebanner_cookiedays';
	$chknewtab_name = 'mobilebanner_chknewtab';
	$hiddenpages_name = 'mobilebanner_hiddenpages';

	// Read in existing option values from database
	$bgcolour_opt_val = get_option ($bgcolour_name);
	$textcolour_opt_val = get_option ($textcolour_name);
	$text_opt_val = get_option ($text_name);
	$url_opt_val = get_option ($url_name);
	$width_opt_val = get_option ($width_name);
	$height_opt_val = get_option ($height_name);
	$chkclose_checked = get_option ($chkclose_name);
	$cookiedays_opt_val = get_option ($cookiedays_name);
	$chknewtab_checked = get_option ($chknewtab_name);
	$hiddenpages_list = get_option ($hiddenpages_name);

	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		// Check nonce is valid
		// This call checks the nonce and the referrer, and if the check fails it takes the normal action (terminating script execution with a “403 Forbidden” response and an error message). [https://developer.wordpress.org/apis/security/nonces/]
		check_admin_referer('mobilebanner_update_nonce');

		// Read their posted value
		if (isset($_POST[$bgcolour_name]))
			$bgcolour_opt_val = sanitize_text_field (wp_unslash ($_POST[$bgcolour_name]));
		else
			$bgcolour_opt_val = 'white';
		if (isset($_POST[$textcolour_name]))
			$textcolour_opt_val = sanitize_text_field (wp_unslash ($_POST[$textcolour_name]));
		else
			$textcolour_opt_val = 'black';
		if (isset($_POST[$text_name]))
			$text_opt_val = sanitize_text_field (wp_unslash ($_POST[$text_name]));
		else
			$text_opt_val = '';
		if (isset($_POST[$url_name]))
			$url_opt_val = sanitize_text_field (wp_unslash ($_POST[$url_name]));
		else
			$url_opt_val = '';
		if (isset($_POST[$width_name]))
			$width_opt_val = intval ($_POST[$width_name]);
		else
			$width_opt_val = 1000;
		if (isset($_POST[$height_name]))
			$height_opt_val = intval ($_POST[$height_name]);
		else
			$height_opt_val = '';
		if (isset($_POST[$chkclose_name]))
			$chkclose_checked = 'checked';
		else
			$chkclose_checked = '';
		if (isset($_POST[$cookiedays_name]))
			$cookiedays_opt_val = intval ($_POST[$cookiedays_name]);
		else
			$cookiedays_opt_val = '';
		if (isset($_POST[$chknewtab_name]))
			$chknewtab_checked = 'checked';
		else
			$chknewtab_checked = '';

		// Hidden pages - requires more processing
		if (isset ($_POST [$hiddenpages_name])) {
			// Make sure all values are integers, and put them into a comma-separated list
			$hiddenpages_list = '';
			foreach (explode ("\n", sanitize_text_field (wp_unslash ($_POST [$hiddenpages_name]))) as $val) {
				if (intval ($val) > 0) {
					$hiddenpages_list .= ',' . intval ($val);
				}
			}
		}
		else {
			$hiddenpages_list = '';
		}

		// Make sure values are valid
		$bgcolour_opt_val = preg_replace ('/[^0-9a-zA-Z#]/', '', $bgcolour_opt_val);
		$textcolour_opt_val = preg_replace ('/[^0-9a-zA-Z#]/', '', $textcolour_opt_val);
		$text_opt_val = htmlentities (trim($text_opt_val), ENT_QUOTES);
		if (filter_var (trim($url_opt_val), FILTER_VALIDATE_URL) === False)
			$url_opt_val = '';
		$width_opt_val = intval ($width_opt_val);
		$height_opt_val = intval($height_opt_val);
		if ($height_opt_val == 0)
			$height_opt_val = '';
		$cookiedays_opt_val = intval($cookiedays_opt_val);

		// Save the posted values in the database
		update_option($bgcolour_name, $bgcolour_opt_val);
		update_option($textcolour_name, $textcolour_opt_val);
		update_option($text_name, $text_opt_val);
		update_option($url_name, $url_opt_val);
		update_option($width_name, $width_opt_val);
		update_option($height_name, $height_opt_val);
		update_option($chkclose_name, $chkclose_checked);
		update_option($cookiedays_name, $cookiedays_opt_val);
		update_option($chknewtab_name, $chknewtab_checked);
		update_option($hiddenpages_name, $hiddenpages_list);

		// Put a "settings saved" message on the screen
		echo '<div class="updated"><p><strong>' . esc_html__('Settings saved.', 'mobile-banner') . '</strong></p></div>';
	}

	// Now display the settings editing screen
	echo '<div class="wrap"><h2>' . esc_html__('Mobile Banner Settings', 'mobile-banner') . '</h2>';
	echo '<form name="mobilebanner_form" method="post" action="">';
	echo '<input type="hidden" name="' . esc_html($hidden_field_name) . '" value="Y">';
?>
	<p>
<?php
	echo esc_html__('Background colour', 'mobile-banner') . ": <input type='text' name='" . esc_html($bgcolour_name) . "' id='" . esc_html($bgcolour_name) . "' value='" . esc_html($bgcolour_opt_val) . "'> <label for='" . esc_html($bgcolour_name) . "'> " . esc_html('CSS colour name or RGB value (eg #6B97D0)');
?>
	</p>

	<p>
<?php
	echo esc_html__('Text colour', 'mobile-banner') . ": <input type='text' name='" . esc_html($textcolour_name) . "' id='" . esc_html($textcolour_name) . "' value='" . esc_html($textcolour_opt_val) . "'> <label for='" . esc_html($textcolour_name) . "'> " . esc_html__('CSS colour name or RGB value (eg #6B97D0)', 'mobile-banner');
?>
	</p>

	<p>
<?php
	echo esc_html__('Text', 'mobile-banner') . ": <input type='text' name='" . esc_html($text_name) . "' id='" . esc_html($text_name) . "' value='" . esc_html($text_opt_val) . "'> <label for='" . esc_html($text_name) . "'> " . esc_html__('Plain text only. No HTML.', 'mobile-banner');
?>
	</p>

	<p>
<?php
	echo esc_html__('Maximum width (pixels)', 'mobile-banner') . ": <input type='text' name='" . esc_html($width_name) . "' id='" . esc_html($width_name) . "' value='" . esc_html($width_opt_val) . "'> <label for='" . esc_html($width_name) . "'> " . esc_html__('Banner is not shown if the screen is wider than this.', 'mobile-banner');
?>
	</p>

	<p>
<?php
	echo esc_html__('Banner height (pixels)', 'mobile-banner') . ": <input type='text' name='" . esc_html($height_name) . "' id='" . esc_html($height_name) . "' value='" . esc_html($height_opt_val) . "'> <label for='" . esc_html($height_name) . "'> " . esc_html__('The height of the banner. Leave blank for default.', 'mobile-banner');
?>
	</p>

	<h3>Hide banner</h3>
	<p>
<?php
	echo esc_html__('IDs of pages and posts where the banner should not be displayed. Enter one ID per line.', 'mobile-banner');
	echo '<br>';
	echo "<textarea name='" . esc_html($hiddenpages_name) . "' id='" . esc_html($hiddenpages_name) . "' rows='5'>";
	$hiddenpages_array = explode (',', $hiddenpages_list);
	sort ($hiddenpages_array);
	foreach ($hiddenpages_array as $val) {
		echo esc_html($val) . "\n";
	}
	echo '</textarea>';
?>
	</p>

	<h3>Link</h3>
	<p>
<?php
	echo esc_html__('URL to link to', 'mobile-banner') . ": <input type='text' name='" . esc_html($url_name) . "' id='" . esc_html($url_name) . "' value='" . esc_html($url_opt_val) . "'> <label for='" . esc_html($url_name) . "'> " . esc_html__('Including', 'mobile-banner') . " &quot;http://&quot; " . esc_html__('or', 'mobile-banner') . " &quot;https://&quot;"
?>
	</p>

	<p>
<?php
	echo "<input type='checkbox' name='" . esc_html($chknewtab_name) . "' id='" . esc_html($chknewtab_name) . "' value='1' " . esc_html($chknewtab_checked) . "> <label for='" . esc_html($chknewtab_name) . "'> " . esc_html__('Open link in new tab.', 'mobile-banner');
?>
	</p>

	<h3>Close button</h3>
	<p>
<?php
	echo "<input type='checkbox' name='" . esc_html($chkclose_name) . "' id='" . esc_html($chkclose_name) . "' " . esc_html($chkclose_checked) . "> <label for='" . esc_html($chkclose_name) . "'> " . esc_html__('Show a close button (20 pixels tall)', 'mobile-banner');
?>
	</p>

	<p>
<?php
	echo esc_html__('Days to hide', 'mobile-banner') . ": <input type='text' name='" . esc_html($cookiedays_name) . "' id='" . esc_html($cookiedays_name) . "' value='" . esc_html($cookiedays_opt_val) . "'> <label for='" . esc_html($cookiedays_name) . "'> " . esc_html__('When a user closes the banner, it will not be displayed again for this many days.', 'mobile-banner');
?>
	</p>

	<p class='submit'>
<?php
	// Add a nonce to the form [https://developer.wordpress.org/apis/security/nonces/]
	wp_nonce_field('mobilebanner_update_nonce');
	echo '<input type="submit" name="Submit" class="button-primary" value="' . esc_html__('Save Changes', 'mobile-banner') . '" />';
?>
	</p>
	</form>

	</div>
<?php
}
