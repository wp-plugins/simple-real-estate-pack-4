<?php
/*
Plugin Name: Simple Real Estate Pack
Plugin URI: http://www.phoenixhomes.com/tech/simple-real-estate-pack
Description: Package of real estate tools and widgets designed specifically for real estate industry blogs and sites. Includes mortgage and home affordability calculators, closing cost estimator, lilve mortgage rates, Trulia statistical graphs, local schools and other features.
Version: 0.9.4
Author: Max Chirkov
Author URI: http://www.PhoenixHomes.com
*/

/*  Copyright 2009  Max Chirkov

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define("PLUGIN_BASENAME", plugin_basename(dirname(__FILE__)));
define("SRP_DIR", WP_PLUGIN_DIR . '/' . PLUGIN_BASENAME);
define("SRP_URL", WP_PLUGIN_URL . '/' . PLUGIN_BASENAME);
define("ADMIN_URL", get_bloginfo('url') . '/wp-admin');

include 'settings/settings.php';
include_once ("tinymce/tinymce.php");
include 'srp-functions.php';
include 'srp-widgets.php';
include 'srp-education.php';
include 'srp-yelp.php';
include 'srp-tinymce-widgets.php';
include 'srp-gre.php';
include 'srp-shortcodes.php';
?>