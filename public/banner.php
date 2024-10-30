<?php
/*
Mobile Banner is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Mobile Banner is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Mobile Banner. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

function mobilebanner_init ($mobilebanner_version) {
	// Check if the banner is to be displayed on this page
	$hiddenpages_list = get_option ('mobilebanner_hiddenpages');
	$hiddenpages_array = explode (',', $hiddenpages_list);
	if (in_array (get_the_ID(), $hiddenpages_array)) {
		// The current page/post ID is in the list of hidden pages. Do nothing more.
		return;
	}

	// Read in existing option values from database
	$mobilebanner_bgcolour = get_option('mobilebanner_bgcolour');
	$mobilebanner_textcolour = get_option('mobilebanner_textcolour');
	$mobilebanner_text = get_option('mobilebanner_text');
	$mobilebanner_url = get_option('mobilebanner_url');
	$mobilebanner_width = get_option('mobilebanner_width');
	$mobilebanner_height = get_option('mobilebanner_height');
	if (strtolower(get_option ('mobilebanner_chkclose')) == 'checked')
		$mobilebanner_close = 1;
	else
		$mobilebanner_close = 0;
	$mobilebanner_cookiedays = get_option('mobilebanner_cookiedays');
	if (strtolower(get_option ('mobilebanner_chknewtab')) == 'checked')
		$mobilebanner_chknewtab = 1;
	else
		$mobilebanner_chknewtab = 0;

	// Make sure values are valid
	$bgcolour = preg_replace ('/[^0-9a-zA-Z#]/', '', $mobilebanner_bgcolour);
	$textcolour = preg_replace ('/[^0-9a-zA-Z#]/', '', $mobilebanner_textcolour);
	$mobilebanner_text = htmlentities($mobilebanner_text, ENT_QUOTES);
	if (filter_var (trim($mobilebanner_url), FILTER_VALIDATE_URL) === False)
		$mobilebanner_url = '';
	$mobilebanner_width = intval ($mobilebanner_width);
	$mobilebanner_height = intval ($mobilebanner_height);
	// Set up array to pass to JavaScript
	$mobilebanner_jsarray = array (
		'bgcolour' => $mobilebanner_bgcolour,
		'textcolour' => $mobilebanner_textcolour,
		'text' => $mobilebanner_text,
		'url' => $mobilebanner_url,
		'width' => $mobilebanner_width,
		'height' => $mobilebanner_height,
		'directory' => plugin_dir_url(__FILE__),
		'closebutton' => $mobilebanner_close,
		'cookiedays' => $mobilebanner_cookiedays,
		'newtab' => $mobilebanner_chknewtab,
	);

	// Register the CSS
	wp_register_style('mobilebanner_css', plugin_dir_url(__FILE__) . 'banner.css', array (), $mobilebanner_version);
	// Register the JavaScript
	wp_register_script('mobilebanner_js', plugin_dir_url(__FILE__) . 'banner.min.js', array('jquery'), $mobilebanner_version, array ('in_footer' => False));

	// Make the $mobilebanner_jsarray array available to the JavaScript
	wp_localize_script('mobilebanner_js', 'mobilebanner_opts', $mobilebanner_jsarray, $mobilebanner_version, array ('in_footer' => False));

	// Enqueue the CSS
	wp_enqueue_style('mobilebanner_css', plugin_dir_url(__FILE__) . 'banner.css', array (), $mobilebanner_version);
	// Enqueue the JavaScript
	wp_enqueue_script('mobilebanner_js');
}

// Enqueue the JavaScript - only in main area, not admin area
if (!is_admin())
	add_action('wp_enqueue_scripts', 'mobilebanner_init');
