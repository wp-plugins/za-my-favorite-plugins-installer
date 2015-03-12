<?php
/**
 * Plugin Name: ZA My Favorite Plugins Installer (ZAMFPI)
 * Plugin URI: http://zeeshanarshad.com
 * Description: One-click installer for automatically download, install and activate your favorite collection of wordpress plugins.
 * Version: 1.0
 * Author: Zeeshan Arshad
 * Author URI: http://www.zeeshanarshad.com
 * License: GPL2
 */

/*  Copyright 2014-2015 ZEESHAN ARSHAD  (email : realisticzee@yahoo.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// create menu to sidebar
add_action('admin_menu', 'mc_menu');

function mc_menu() {
	add_menu_page('My Favorite Plugins Installer', 'Favorite Plugins', 'manage_options', 'favorite_plugin_installer', 'favorite_plugin_installer', plugin_dir_url( __FILE__ ).'images/logo.png', 62);
}
	function favorite_plugin_installer() {
		include('plugin_installer.php');
	}
?>