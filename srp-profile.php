<?php
/*---------------------------------------------*
** Simple Realty Pack support functions
** to work with Great Real Estate Plugin
** Author: Max Chirkov
** WebSite: www.PhoenixHomes.com
**---------------------------------------------*/

/*
 * ToDo Max: Organize the bootstrap.
 * There should be a naming convention where object can automatically call .._content() and .._tab() functions depending on the situation.
 * For example: $callback = 'mortgage' therefore we can call mortgage_content() and mortgage_tab().
 * By knowing the callback functions, we can call them via AJAX.
 *
 * Listing Extension should work as a geo/place profile - doesn't matter if it's a GRE or something else or by itself.
 * So, any SRP extensions/widgets or any third party extensions to SRP should be added via the Class.
 * Required options should appear in the Extensions Settings page:
 *  - Extension activation
 *  - Extension Tab Name and Sub-title
 *  - Extension callback function name
 * the class will generate the rest of the tabs etc from the settings above.
 */
$srp_widgets = new srpWidgets();

function srp_prepare_widgets_object(){
    global $srp_widgets;

    $init = array();
    $init = apply_filters('srp_prepare_widgets_object', $init);
    /*print '<pre>';
    print_r($init);
    print '</pre>';
     /**
     */
    if(!is_array($init))
        return;
    foreach($init as $array){
        foreach($array as $k => $v){
            $$k = $v;            
        }
        $srp_widgets->add($name, $title, $tab_name, $content, $callback_function, $init_function, $ajax, $save_to_buffer);
        foreach($array as $k => $v){            
            unset($$k);
        }
    }    
}


if($_POST['srp_listing_values']){
    $srp_property_values = $_POST['srp_listing_values'];
}

function srp_listing_content(){
    do_action('srp_listing_content');
}

function _check_required_values(){
    global $srp_property_values;
    
    if(!is_array($srp_property_values))
        return;
    
    $requierd = array ('lat', 'lng', 'address', 'city', 'state', 'zip_code');
    $keys = array_keys($srp_property_values);
    foreach($requierd as $k){
        if(!in_array($k, $keys))
                return false;
    }
    return true;
}


if(!$srp_ext_gre_options = get_option('srp_ext_gre_options')) return;
$srp_ext_gre_content = array_keys($srp_ext_gre_options['content']);
$srp_ext_gre_tabs = $srp_ext_gre_options['tabs'];
$srp_general_options = get_option('srp_general_options');

function srp_gre_admin_scripts(){
	if($g_api = get_option('greatrealestate_googleAPIkey')){		
		echo "\n" . '<script type="text/javascript">
//<![CDATA[
		var srp_geo = "'. $g_api . '/";
//]]>
		</script>'. "\n";
		echo '<script type="text/javascript" src="'.SRP_URL.'/js/srp-gre-admin.js"></script>';
	}
}
add_action('admin_footer', 'srp_gre_admin_scripts');

function srp_extension_prepare_content() {
    global $srp_ext_gre_content;

    if(in_array('mortgage_calc', $srp_ext_gre_content))
        srp_gre_the_mortgage_content();

    if(in_array('trulia_stats', $srp_ext_gre_content))
        srp_gre_the_trulia_stats_content();

    if(in_array('altos_stats', $srp_ext_gre_content))
        srp_gre_the_altos_stats_content();

    if(in_array('schools', $srp_ext_gre_content))
        srp_gre_the_listing_schools_content();

    if(in_array('yelp', $srp_ext_gre_content))
        srp_gre_the_yelp_content();

    if(in_array('walkscore', $srp_ext_gre_content))
        srp_gre_the_walkscore_content();        
}

function srp_gre_content_init($init){
    global $srp_widgets;

    $gre_functions = array(
        //'Description' => 'the_listing_description_content',
        'Photos' => 'the_listing_gallery_content',
        'Video' => 'the_listing_video_content',
        'Panorama' => 'the_listing_panorama_content',
        'Downloads' => 'the_listing_downloads_content',
        'Community' => 'the_listing_community_content',
    );    
    foreach($gre_functions as $tab_name => $callback){
        //if(srp_buffer($callback)){
        if(function_exists($callback) && srp_buffer($callback)){
            $init[] = array(
                'name' => strtolower($tab_name),
                'title'  => NULL, //GRE already provides H2 headings
                'tab_name' => $tab_name,
                'callback_function' => $callback,
                'ajax' => false,
                'save_to_buffer' => true,
                );
        }
    }
    return $init;
}
if(function_exists('greatrealestate_init')){
    add_filter('srp_prepare_widgets_object', 'srp_gre_content_init', 2);
}

/*
 * @args - ajax, tabs = bool
 */
function srp_profile($args = array()){
    global $srp_general_options, $srp_widgets, $srp_property_values, $srp_ext_gre_content;
    
    if(count($srp_property_values) < 6)
        return;

    if(empty($args)){
        $args['tabs'] = $srp_general_options['content']['srp_profile_tabs'];
        $args['ajax'] = $srp_general_options['content']['srp_profile_ajax'];
    }

    srp_prepare_widgets_object();
    //var_dump($srp_widgets);
    $js_func = 'srp_profile';
    $content = '<div id="srp-tab-wrap">';
    if($args['tabs']){        
        $content .= $srp_widgets->get_tabs();
        $js_func = 'srp_profile_tabs';
    }
    if(!get_option('srp_ext_gre_options')){
            $content .=  '<div style="background:red; color: white; font-weight: bold; padding: 10px;">Please visit the <a href="'.ADMIN_URL.'/admin.php?page=srp_ext_gre">Extension to GRE settings</a> page to complete the installation.</div>';
    }elseif($args['ajax']){
        
        foreach($srp_widgets->widgets as $widget){
            if($widget->ajax == true){
                if(in_array($widget->name, $srp_ext_gre_content)){
                    $callbacks[] = '\'' . $widget->init_function . '\'';
                }
            }
        }

        if(is_array($callbacks)){
            $callbacks_string = implode(',', $callbacks);
            $content .=  '<script type="text/javascript">
                        var srp_listing_values = {' . "\n";
            $i = 0;
            $n = count($srp_property_values);
            foreach($srp_property_values as $k => $v){
                $i++;
                if($i == $n) { $comma = ''; }else{ $comma = ','; }
                $content .=  "\t\t\t" . $k . ': \''.$v.'\''.$comma . "\n";
            }
            
                $content .=  "\t\t" .'};
                    var load_srp_functions = ['. $callbacks_string .'];
                    addLoadEvent('. $js_func .');
                  </script>
                  ';
        }
                    
        $content .=  '<div id="srp_extension">'. $srp_widgets->get_all_ajax(false)  . '</div>';
    }else{        
        $content .= '<div id="srp_extension">';
        $content .= $srp_widgets->get_all();
        $content .= '</div>';
    }
    $content .= '</div>';

    echo $content;
}

/*---------------------------------------------*
** Substitute function the_listing_map_content()
** to be placed in listingpage.php template
** for Great Real Estate Plugin
**---------------------------------------------*/
/*
 * ToDo Max: Tabs & content - output check against SRP settins.
 * 
 */

function srp_gre_the_listing_map_content() {
    global $srp_widgets, $srp_ext_gre_content, $srp_ext_gre_tabs, $srp_property_values;
	if (!_check_required_values()) return;

        $title = 'Location Map';
        $content = '<div class=\"srp-tabs\">';
        $content .= srp_map($srp_property_values['lat'], $srp_property_values['lng'], $srp_property_values['html']);
        $content .= '</div>';
        return $content;
        //$srp_widgets->add('map', $title, $content);
}
function srp_map_content_init($init){
    $array = array(
            'name' => 'map',
            'title'  => 'Location Map',
            'tab_name' => 'Map',
            'callback_function' => 'srp_gre_the_listing_map_content',
            'init_function' => __FUNCTION__,
            'ajax' => false,
            //'save_to_buffer' => false,
            );
    $init[] = $array;
    return $init;
}
add_filter('srp_prepare_widgets_object', 'srp_map_content_init', 1);

function srp_gre_the_listing_schools_content(){
	global $srp_widgets, $srp_ext_gre_content, $srp_ext_gre_tabs, $srp_property_values;

	if(!in_array('schools', $srp_ext_gre_content)) return;
	
	if (!EDU_API_KEY) return;
	if (!$srp_property_values['lat']) return;
	if (!$srp_property_values['lng']) return;
	$address = $srp_property_values['address'] . ' ' . $srp_property_values['city'] . ', ' . $srp_property_values['state'] . ' ' . $srp_property_values['zip_code'];
	if (!($content = srp_schoolSearch_shortcode(array("lat"=>$srp_property_values['lat'],  "lng"=>$srp_property_values['lng'], "distance"=>3, "groupby"=>"gradelevel", "output"=>"table", "location_title"=>$address)))) return;

        return $content;
}
function srp_schools_content_init($init){
    global $srp_ext_gre_tabs;
    if (!EDU_API_KEY) return $init;
    $array = array(
            'name' => 'schools',
            'title'  => 'Local Schools',
            'tab_name' => 'Schools',
            'callback_function' => 'srp_gre_the_listing_schools_content',
            'init_function' => __FUNCTION__,
            'ajax' => true,
            );
    $init[] = $array;
    return $init;
}
add_filter('srp_prepare_widgets_object', 'srp_schools_content_init');
/*-------------------------------------------------------------------------*
** Market Trends Tab (by Trulia) to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_trulia_stats_content(){
	global $srp_widgets, $srp_ext_gre_content, $srp_ext_gre_tabs, $srp_property_values;
	if(!in_array('trulia_stats', $srp_ext_gre_content)) return;
	
	if(!function_exists('srp_get_trulia_stats')) return;
	global $graph_types;
	foreach($graph_types as $k => $v){
		$id = str_replace(' ', '_', strtolower($k));
		$output .='<div id="'.$id.'">' . srp_get_trulia_stats(array('type' => $k, 'city' => $srp_property_values['city'], 'state' => $srp_property_values['state'], 'zipcode' => $srp_property_values['zip_code'])) . '</div>';
		$li .= '<li><a href="#'.$id.'">'.$v.'</a></li>'."\n";
	}
	$content = "<div class=\"srp-tabs\"><ul class=\"clearfix\">\n $li \n </ul>\n";
	$content .= $output . '</div>';

        return $content;
}
function srp_trulia_stats_content_init($init){
    global $srp_ext_gre_tabs, $srp_ext_gre_content;    

    $array = array(
            'name' => 'trulia_stats',
            'title'  => 'Market Statistics',
            'tab_name' => 'Market Stats',
            'callback_function' => 'srp_gre_the_trulia_stats_content',
            'init_function' => __FUNCTION__,
            'ajax' => true,
            );
    $init[] = $array;
    return $init;
}
add_filter('srp_prepare_widgets_object', 'srp_trulia_stats_content_init');
/*-------------------------------------------------------------------------*
** Market Trends Tab (by ALTOS Research) to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_altos_stats_content($width = 600, $height = 400){
	global $srp_widgets, $srp_ext_gre_content, $srp_ext_gre_tabs, $srp_property_values;

	if(!function_exists('srp_get_altos_stats')) return;
	global $metrics, $pricerangequartile, $rollingaverage;
	foreach($metrics as $k => $v){
		$id = str_replace(' ', '_', strtolower($k));
		$output .='<div id="'.$id.'">' . srp_get_altos_stats(array('type' => $k, 'width' => $width, 'height' => $height, 'city' => $srp_property_values['city'], 'state' => $srp_property_values['state'], 'zipcode' => $srp_property_values['zip_code'])) . '</div>';
		$li .= '<li><a href="#'.$id.'">'.$v.'</a></li>'."\n";
	}
	$content = "<div class=\"srp-tabs\"><ul class=\"clearfix\">\n $li \n </ul>\n";
	$content .= $output . '</div>';

        return $content;
}
function srp_altos_stats_content_init($init){
    global $srp_ext_gre_tabs, $srp_ext_gre_content;

    $array = array(
            'name' => 'altos_stats',
            'title'  => 'Market Statistics',
            'tab_name' => 'Market Stats',
            'callback_function' => 'srp_gre_the_altos_stats_content',
            'init_function' => __FUNCTION__,
            'ajax' => true,
            );
    $init[] = $array;
    return $init;
}
add_filter('srp_prepare_widgets_object', 'srp_altos_stats_content_init');
/*-------------------------------------------------------------------------*
** Mortgage/Financing Tab to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_mortgage_content(){
	global $srp_widgets, $srp_ext_gre_content, $srp_ext_gre_tabs, $srp_property_values;
	if(!in_array('mortgage_calc', $srp_ext_gre_content) && !in_array('closing_estimator', $srp_ext_gre_content) && !in_array('affordability_calc', $srp_ext_gre_content) && !in_array('rental_meter', $srp_ext_gre_content)) return;
	
        if(in_array('mortgage_calc', $srp_ext_gre_content)){
            $content = '
		<div style="float: left; width: 50%;">'
			. srp_MortgageCalc_shortcode(array('price_of_home'=> $srp_property_values['listing_price']))
		. '</div>';
        }
	
        $content .= '<div style="float:left; width: 50%;">';
			
	if(in_array('closing_estimator', $srp_ext_gre_content)){
            $content .= srp_ClosingCosts_shortcode(array('loan_amount'=>$srp_property_values['listing_price']));
        }

        $content .= '</div>
		<div style="float:left; width: 50%;">';

        if(in_array('affordability_calc', $srp_ext_gre_content)){
            $content .= srp_AffordabilityCalc_shortcode();
        }

        $content .= '
		</div>
		<div style="float:left; width: 50%;">';

        if(in_array('rental_meter', $srp_ext_gre_content)){
            $content .= srp_RentMeter_shortcode(array('citystatezip'=>$srp_property_values['zip_code'], 'beds'=>$srp_property_values['bedrooms']));
	}			
	
        $content .= '</div>';

        return $content;
}

function srp_mortgage_content_init($init){
    global $srp_ext_gre_tabs, $srp_ext_gre_content;    

    $array = array(
            'name' => 'financial',
            'title'  => 'Financial Tools',
            'tab_name' => 'Financing',
            'callback_function' => 'srp_gre_the_mortgage_content',
            'init_function' => __FUNCTION__,
            'ajax' => true,
            );
    $init[] = $array;
    return $init;
}
add_filter('srp_prepare_widgets_object', 'srp_mortgage_content_init');

/*-------------------------------------------------------------------------*
** Yelp Tab to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_yelp_content(){
	global $srp_widgets, $srp_ext_gre_content, $srp_ext_gre_tabs, $srp_property_values;
	if(!in_array('yelp', $srp_ext_gre_content)) return;
	
	if (!get_option('srp_yelp_api_key')) return;
	if(!function_exists('srp_Yelp_shortcode')) return;
	if (!($content = srp_Yelp_shortcode($atts=array("lat"=>$srp_property_values['lat'], "lng"=>$srp_property_values['lng'], 'radius' => 3, 'output' => 'table', 'sortby' => 'distance', 'term' => null, 'num_biz_requested' => null, 'ajax' => null)))) return;

        return $content;
}
function srp_yelp_content_init($init){
    global $srp_ext_gre_tabs, $srp_ext_gre_content;    

    if (!get_option('srp_yelp_api_key')) return $init;
    
    $array = array(
            'name' => 'yelp',
            'title'  => 'Businesses in the Neighborhood',
            'tab_name' => 'Nearby Businesses',
            'callback_function' => 'srp_gre_the_yelp_content',
            'init_function' => __FUNCTION__,
            'ajax' => true,
            );
    $init[] = $array;
    return $init;
}
add_filter('srp_prepare_widgets_object', 'srp_yelp_content_init');
/*-------------------------------------------------------------------------*
** Walkscore Tab to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_walkscore_content(){
	global $srp_widgets, $srp_ext_gre_content, $srp_ext_gre_tabs, $srp_property_values;
	if(!in_array('walkscore', $srp_ext_gre_content)) return;
	if (!$ws_wsid = get_option('srp_walkscore_api_key')) return;
	$ws_address = $srp_property_values['address'] . ' ' . $srp_property_values['city'] . ', ' . $srp_property_values['state'] . ' ' . $srp_property_values['zip_code'];
	
	$content = srp_walkscore($ws_wsid, $ws_address, $ws_width=500, $ws_height=286, $ws_layout = 'horizontal');

        return $content; //$ws_wsid . ' ' . $ws_address . ' ' . $ws_width=500 . ' ' . $ws_height=286 . ' ' . $ws_layout = 'horizontal';
}
function srp_walkscore_content_init($init){
    global $srp_ext_gre_tabs, $srp_ext_gre_content;    

    if (!$ws_wsid = get_option('srp_walkscore_api_key')) return $init;

    $array = array(
            'name' => 'walkscore',
            'title'  => 'Walkability of the Neighborhood',
            'tab_name' => 'Walkability',
            'callback_function' => 'srp_gre_the_walkscore_content',
            'init_function' => __FUNCTION__,
            'ajax' => true,
            );
    $init[] = $array;
    return $init;
}
add_filter('srp_prepare_widgets_object', 'srp_walkscore_content_init');
?>