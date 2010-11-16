==== Simple Real Estate Pack ====
Contributors: Max Chirkov
Donate link: https://www.cancer.org/aspx/Donation/DON_1_Donate_Online_Now.aspx
Tags: mortgage, mortgage calculator, real estate, realty, widget, plugin, listing, AJAX, homes, neighborhood, schools, yelp, zillow, map, trulia, altos, charts, statistics, real estate market
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: 1.1.3

Package of real estate tools and widgets designed specifically for real estate industry blogs and sites.

== Description ==
Simple Real Estate Pack is a package of real estate tools and widgets designed specifically for real estate industry blogs and web sites. The plugin includes mortgage and home affordability calculators, closing cost estimator, live mortgage rates, Trulia and ALTOS statistical charts, local schools, local rental rates meter, business listings from Yelp and Google Maps. Optionally, Simple Real Estate Pack can function as an extension for Great Real Estate (GRE) plugin, and will add new features to the GRE if it's installed. Take a look at live example of this functionality at [ScottsdaleHomes.com](http://www.scottsdalehomes.com/properties/kierland-greens-condo/).

**Requires PHP 5.2 or higher**

**Features Include:**

1.  Calculators
       * Mortgage Calculator (widget and shortcode)
       * Affordability Calculator (widget and shortcode)
       * Closing Costs Estimator (widget and shortcode)

2. Schools - shortcode widget provides a list of schools within selected location (via Education.com API). Can group schools by type, grade level, school district or zip code.

3. Live Mortgage Rates via Zillow API (widget and shortcode).

4. Rent Meter (widget and shortcode) - provides median rental rates for selected zip code via Rentometer API.

5. Market trends and statistical graphs/charts via Trulia.com and ALTOS Research.

6. Embed Google Maps with a click of a mouse with grocery stores, restaurants, gas stations, banks, golf courses and hospitals (optional) within 3 mile radius of the main marker (via Yelp API).

7. Publish Yelp listings (shortcode) within 3 mile radius from a specified point into you content. Grouped with tabs by business type (i.e. grocery stores, restaurants etc.).

8. Walkscore via Walkscore.com

9. Extension of GRE plugin (optional) via Neighborhood Profile options - mapping local grocery stores, restaurants, gas stations, banks, golf courses and hospitals within 3 mile radius of the property. Includes property location, contact information, ratings via Yelp API., as well as financial tools and statistical charts.

10. Easy to use API for third party widget integration into the Neighborhood Profiles.

For detailed usage instructions visit the [official site](http://www.phoenixhomes.com/tech/simple-real-estate-pack "Simple Real Estate Pack by PhoenixHomes.com").


* Author: Max Chirkov
* Author URI: [http://www.PhoenixHomes.com](http://www.PhoenixHomes.com "Phoenix Real Estate")
* Copyright: Released under GNU GENERAL PUBLIC LICENSE


== Installation ==

**Install like any other basic plugin:**

1.	Unzip and copy the simple-real-estate-pack folder to your /wp-content/plugins/ directory

2.	Activate the Simple Real Estate Pack on your plugins page.

3.	Go to the Real Estate Pack Settings Page and adjust options to fit your needs.

4.  A lot of the functionality of the plugin depends on third party APIs. To take advantage of all the features it's highly recommended that you obtain (free) API keys for each service.


**Using Neighborhood Profiles in Templates**

*Note: This requires good understanding of HTML markup and experience in editing WordPress templates.*

= Extending GRE listings with SRP Neighborhood Profiles =

If you're using the Great Real Estate Plugin, you can simply include the existing template that comes with the Simple Real Estate Plugin. To do so, follow the following steps:

**1.** Create a copy of your page.php template and name it listingpage.php. It has to be in your theme's folder where other templates are (page.php, post.php etc.).

**2.** Open the file to edit, and place the following code at the top of the file:

`
<?php
/*
Template Name: SRP Listing Page
*/
?>
`

**3.** Find the line that begins with `<div class="entry">` and its corresponding closing `</div>`. Delete everything in between and insert this code:

`<?php
if(SRP_DIR)
    include (SRP_DIR . '/templates/listing_page.php');
?>`

**4.** Now, you should be able to use SRP Listing Page template from the drop-down selection list when editing your listings.

= Using SRP Neighborhood Profiles in Custom Templates =

Assuming that a number of values that refer to property location will be passes to your custom template, you need to define the following global variable as an associative array with preset keys followed by the `srp_profile()` function:

`<?php
$srp_property_values = array(
    'lat' => '',
    'lng' => '',
    'address' => '',
    'city' => '',
    'state' => '',
    'zip_code' => '',
    'listing_price' => '',
    'bedrooms'  => '', //optional
    'bathrooms' => '', //optional
    'html' => '', //optional
);
if(function_exists('srp_profile')){
    srp_profile();
}
?>`
*All parameters are required except the noted ones. The variable's name (`$srp_property_values`) required to be exact.*

**Using SRP API**

Simple Real Estate Pack's API allows developers to add their own widgets to the Neighborhood Profile output. Those widgets will play by the same rules as the built-in ones - they can load statically into the page or via AJAX, can be presented via tabs and their tab names and subtitles can be customized via plugin settings page by the end-user.

This implementation consists of 3 simple steps:

**1.** The actual function that returns your widget's content. You can use global `$srp_property_values` variable to get access to the initial property/location parameters if needed.

`<?php
function my_custom_srp_widget_content() {
    global $srp_property_values;

    ..do something to generate content..

    return $content;
}
?>`

**2.** The function that initializes your widget and adds all the necessary information about it to the $srp_widgets object:

`<?php
function my_custom_srp_widget_content_init($init) {
    $array = array(
            'name' => 'widget_name', //lower case and no special characters
            'title'  => 'My Custom Widget Title', //will used as subtitle within content
            'tab_name' => 'Widget Tab Name', //short 1-2 word tab name
            'callback_function' => 'my_custom_srp_widget_content', //callback widget content function
            'init_function' => __FUNCTION__, //don't change - this is reference to the current function
            'ajax' => false, //bool - false or true grants an option for your widget to be loaded via AJAX.
            'save_to_buffer' => false, //bool - change to true if your widget's content needs to be cached before the output. For example your widget content function doesn't return the data, but instead outputs it directly. If you change to TRUE, it will cache the output and return in correctly.
            );
    $init[] = $array;
    return $init;
}
?>`

*Note: all parameters of the $array are required. Make sure your _init function references the $init argument and always returns $init.*

**3.** The last step is to add a filter that will add your new widget init to the object preparation process:

`<?php
add_filter('srp_prepare_widgets_object', 'my_custom_srp_widget_content_init');
?>`

*Note: if you want to change an order in which widgets load, simply add 3rd numerical parameter to the filter. Keep in mind that Google Map is set to load first (and should stay that way), due to some unsolved issue.*

== Screenshots ==

1. Mapping - outputs schools, grocery stores, restaurants, banks, gas stations, golf courses and hospitals in the neighborhood of property listings (Can also work as extension to [Great Real Estate plugin](http://wordpress.org/extend/plugins/great-real-estate/)).
2. Financial Tools.
3. Trulia Market Trends.
4. Local Schools.
5. Settings page for Mortgage Calculator and Closing Costs Estimator.
6. Altos Market Charts.
7. Local businesses via Yelp.
8. Walk Score.
9. Tabbed presentation of data.
10. Neigborhood profile options.
11. Custom tab names and subtitles of the widgets.
12. TinyMCE widgets in post/page editor.
13. Yelp TinyMCE widget to insert a shortcode into the post/page.
14. Financial Tools TinyMCE widget to insert a shortcode into the post/page.
15. Google Maps TinyMCE widget to insert a shortcode into the post/page.
16. Altos Stats TinyMCE widget to insert a shortcode into the post/page.
17. Trulia Stats TinyMCE widget to insert a shortcode into the post/page.

== Frequently Asked Questions ==

**If something doesn't work, please try to troubleshoot the issue by checking is any JavaScript errors are reported. Also, try disabling other plugins and leave the SRP enabled just to make sure there are no conflicts.**

== Changelog ==
**Version 1.1.3**

- Added messages for schools and businesses if no records returned within specified radius. The same applies to the maps when clicking on checkboxes.
- Added dummy image output with a message if market stats is not available.
- Fixed bug for multi-word cities in school requests.

== Upgrade Notice ==

It is strongly recomended to re-visit the plugin's settings pages and save them.

If you have been using the Great Real Estate Plugin prior to upgrade, you need to update your listingpage.php file by following the Installation instructions in readme.txt.