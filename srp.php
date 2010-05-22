<?php
/*
Plugin Name: Simple Real Estate Pack
Plugin URI: http://www.phoenixhomes.com/tech/simple-real-estate-pack
Description: Package of real estate tools and widgets designed specifically for real estate industry blogs and sites. Includes mortgage and home affordability calculators, closing cost estimator, lilve mortgage rates, Trulia statistical graphs, local schools and other features.
Version: 1.0.2
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

/*
 * ToDo Max: Instructions on API, and how to include GRE template, finish GRE template + CSS for it, videos on implementation of all of the above.
 */

define("PLUGIN_BASENAME", plugin_basename(dirname(__FILE__)));
define("SRP_DIR", WP_PLUGIN_DIR . '/' . PLUGIN_BASENAME);
define("SRP_URL", WP_PLUGIN_URL . '/' . PLUGIN_BASENAME);
define("ADMIN_URL", get_bloginfo('url') . '/wp-admin');

register_activation_hook(__FILE__, 'srp_activation');

require_once 'Class_srpWidgets.php';
include_once ("tinymce/tinymce.php");
include 'srp-functions.php';
include 'srp-widgets.php';
include 'srp-education.php';
include 'srp-yelp.php';
include 'srp-tinymce-widgets.php';
include 'srp-profile.php';
include 'srp-shortcodes.php';
include 'settings/settings.php';

/*
 * Set default settings on plugin activation
 */
function srp_activation(){

    $auto_options = array(
        'srp_general_options' => Array(
            'content' => Array(
                'srp_gre_css' => 'on',
                'srp_profile_tabs' => 'on',
                'srp_profile_ajax' => 'on',
            ),
        ),

        'srp_ext_gre_options' => Array(
            'content' => Array(
                'map' => 'on',
                'schools' => 'on',
                'altos_stats' => 'on',
                'financial' => 'on',
                'yelp' => 'on',
                'walkscore' => 'on',
                'mortgage_calc' => 'on',
                'closing_estimator' => 'on',
                'affordability_calc' => 'on',
                'description' => 'on',
                'photos' => 'on',
                'video' => 'on',
                'panorama' => 'on',
                'downloads' => 'on',
                'community' => 'on',
            ),
            'tabs' => Array(
                'map' => Array(
                        'tabname' => 'Map',
                        'heading' => 'Location Map',
                    ),
                'schools' => Array(
                        'tabname' => 'Schools',
                        'heading' => 'Local Schools',
                    ),
                'trulia_stats' => Array(
                        'tabname' => 'Market Stats',
                        'heading' => 'Market Statistics',
                    ),
                'altos_stats' => Array(
                        'tabname' => 'Market Stats',
                        'heading' => 'Market Statistics',
                    ),
                'financial' => Array
                    (
                        'tabname' => 'Financing',
                        'heading' => 'Financial Tools',
                    ),
                'yelp' => Array(
                        'tabname' => 'Nearby Businesses',
                        'heading' => 'Businesses in the Neighborhood',
                    ),
                'walkscore' => Array(
                        'tabname' => 'Walkability',
                        'heading' => 'Walkability of the Neighborhood',
                    ),
            ),
       ),

       'srp_mortgage_calc_options' => Array(
            'annual_interest_rate' => 6,
            'mortgage_term' => 30,
            'property_tax_rate' => 1,
            'home_insurance_rate' => 0.5,
            'pmi' => 0.5,
            'origination_fee' => 1,
            'lender_fees' => 600,
            'credit_report_fee' => 50,
            'appraisal' => 300,
            'title_insurance' => 800,
            'reconveyance_fee' => 75,
            'recording_fee' => 45,
            'wire_courier_fee' => 55,
            'endorsement_fee' => 75,
            'title_closing_fee' => 125,
            'title_doc_prep_fee' => 30,
       ),

       'srp_walkscore_api_key' => 'YOUR-WSID-HERE',
   );

    foreach($auto_options as $option_name => $value){
        if(!get_option($option_name)){
                add_option($option_name, $value, 'yes');
        }
    }
}
?>