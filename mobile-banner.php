<?php
/*
Plugin Name: Mobile Banner
Plugin URI: https://www.authorhelp.uk/wordpress-plugin-mobile-banner/
Description: Create a banner with a link at the bottom of the screen, when viewed on mobile only.
Version: 1.8
Author: Robin Phillips (Author Help)
Author URI: https://www.authorhelp.uk/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

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

$mobilebanner_version = 1.8;

// Activation function: Initialise options
function mobilebanner_activation () {
	// Create options with default values
	add_option('mobilebanner_bgcolour', '#6B97D0');
	add_option('mobilebanner_textcolour', 'white');
	add_option('mobilebanner_text', '');
	add_option('mobilebanner_url', '');
	add_option('mobilebanner_width', 1000);
	add_option('mobilebanner_height', '');
	add_option('mobilebanner_chkclose', 'checked');
	add_option('mobilebanner_cookiedays', 30);
	add_option('mobilebanner_chknewtab', 'checked');
}

// Uninstall function
function mobilebanner_uninstall () {
	// Remove options
	delete_option('mobilebanner_bgcolour');
	delete_option('mobilebanner_textcolour');
	delete_option('mobilebanner_text');
	delete_option('mobilebanner_url');
	delete_option('mobilebanner_width');
	delete_option('mobilebanner_height');
	delete_option('mobilebanner_chkclose');
	delete_option('mobilebanner_cookiedays');
	delete_option('mobilebanner_chknewtab');
}

// Register hooks
register_activation_hook( __FILE__, 'mobilebanner_activation' );
register_uninstall_hook(__FILE__, 'mobilebanner_uninstall');

// Add Settings link to plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'mobilebanner_pluginpagelinks');

function mobilebanner_pluginpagelinks ($links) {
	// Add a "Settings" link
	$links [] = '<a href="'. get_admin_url(null, 'options-general.php?page=mobilebanner_config') .'">' .
		esc_html__('Settings', 'mobile-banner') . '</a>';
	return $links;
}

// Create settings page
add_action('admin_menu', 'mobilebanner_admin');

// Include the main code: only if the hide cookie is not set, or if its value is not 'hide'
if (!isset($_COOKIE['mobilebanner']) || $_COOKIE['mobilebanner'] != 'hide')
	require ('public/banner.php');

// Include the admin code
require ('admin/settings.php');
