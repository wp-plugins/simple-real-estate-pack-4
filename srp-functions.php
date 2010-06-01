<?php

function pa($x){
	print '<pre>';
	print_r($x);
	print '</pre>';
}

function srp_get_states($key = NULL){
	$states = array (
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'District of Columbia',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
	);
	if($key){
		return $states[$key];
	}else{
		return $states;
	}
}



function format_phone($phone){
	$phone = preg_replace("/[^0-9]/", "", $phone);
	if(strlen($phone) == 7)
		return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
	elseif(strlen($phone) == 10)
		return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
	elseif(strlen($phone) == 11)
		return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1 ($2) $3-$4", $phone);
	else
		return $phone;
}

/**
* @param
*
*/
function srp_map($lat, $lng, $html=null, $width = NULL, $height = NULL) {
	   if($width){ $width = "width:{$width}px;"; }
	   if($height){ $height = "height:{$height}px;"; }

	$output .= '<div id="map">
	  <div id="map_area" style="' . $width . $height . '">
   		<div id="gre_map_canvas" style="' . $width . $height . '"></div>';

		if (get_option('srp_yelp_api_key') && get_option('srp_gmap_yelp')){
			$output .= srp_yelp_select();
		}

	$output .= '<input id="srp_gre_prop_coord" type="hidden" value="' . $lat .',' . $lng . '" />
	   </div>
	   <div class="srp_gre_legend"><span><img src="http://www.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png" /> - Main Marker</span></div>
	</div>
<script type="text/javascript">
/* <![CDATA[ */
	function srp_setupmap() {
		var point = new google.maps.LatLng(' . $lat . ',' . $lng . ');
		srp_map.setCenter(point, 13);
		srp_setDefaultMarker(point, \'' . $html . '\');
		';
		//GoogleBar is not working with GMaps API3 - might be ported later
		//if (get_option('srp_gmap_search')) $output .= 'srp_map.enableGoogleBar();';
		$output .= '
	}
	addLoadEvent(srp_initialize);
/* ]]> */
</script>';
	return $output;
}

/*
** CSS and JS initialization
*/
function srp_admin_scripts(){
	echo "\n" . '<script type="text/javascript">
//<![CDATA[
	var srp_url = "'. SRP_URL .'";
	var srp_wp_admin = "' . ADMIN_URL . '";
//]]>
' . "\n" . '</script>' . "\n";
}

function srp_default_headScripts(){
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
	if (isset($_GET['page']) && strstr($_GET['page'], 'simple-real-estate-pack') || strstr($_GET['page'], 'srp_')){
		wp_enqueue_script('postbox');
		wp_enqueue_script('dashboard');
		wp_enqueue_style('dashboard');
		wp_enqueue_style('global');
		wp_enqueue_style('wp-admin');
		wp_enqueue_style('blogicons-admin-css', SRP_URL . '/settings/settings.css');
	}
        $googlepath = "http://maps.google.com/maps/api/js?sensor=true";
	wp_enqueue_script( 'google', $googlepath, FALSE, false, false );
        if(function_exists('greatrealestate_init')){
            remove_action( 'wp_enqueue_scripts', 'greatrealestate_add_javascript' );
        }

}

function srp_head_scripts(){
    $myStyleUrl		= SRP_URL . '/css/srp.css';
    $myStyleFile	= SRP_DIR . '/css/srp.css';
    if ( file_exists($myStyleFile) ) {
        wp_register_style('srp', $myStyleUrl);
        wp_print_styles( 'srp');
    }
	$uitabsStyle	= SRP_URL . '/css/ui.tabs.css';
	$uitabsFile		= SRP_DIR . '/css/ui.tabs.css';
	$srp_general_options = get_option('srp_general_options');
	$srp_ext_gre_options = get_option('srp_ext_gre_options');
	if($srp_general_options['content']['srp_gre_css'] || $srp_ext_gre_options['content']['srp_gre_css']  && file_exists($uitabsFile)){
            wp_register_style('srp_uitabs', $uitabsStyle);
            wp_print_styles( 'srp_uitabs');
	}
		echo "\n" . '<script type="text/javascript">
/*<![CDATA[ */' ."\n"
. "\t" . 'tb_pathToImage = "' . get_option('siteurl') . '/wp-includes/js/thickbox/loadingAnimation.gif";'."\n"
. "\t" . 'tb_closeImage = "' . get_option('siteurl') . '/wp-includes/js/thickbox/tb-close.png";'. "\n"
. "\t" . 'var srp_url = "'. SRP_URL .'";' . "\n"
. "\t" . 'var srp_wp_admin = "' . ADMIN_URL . '";
/* ]]> */
' . "\n" . '</script>' . "\n";
        //echo '<!--[if lt IE 8]>';
        echo '<script type="text/javascript" src="' . SRP_URL . '/js/jsmin.js"></script>'."\n";
        //echo '<![endif]-->';
	echo '<script type="text/javascript" src="' . SRP_URL . '/js/srp.js"></script>'."\n";
}

function srp_footer_scripts(){
	echo '<script type="text/javascript" src="' . SRP_URL . '/js/srp-MortgageCalc.js"></script>'."\n";
	echo '<script type="text/javascript" src="' . SRP_URL . '/lib/jquery.formatCurrency-1.0.0.js"></script>'."\n";
}

add_action('admin_print_scripts', 'srp_admin_scripts');
add_action('init', 'srp_default_headScripts');
add_action('wp_head', 'srp_head_scripts');
add_action('wp_footer', 'srp_footer_scripts');

/*-------------------------------------------------------------------------*
** Helper function that reports back via AJAX
** if a certain function or API exists, in order to execute specifc JS.
**	@param string $name - function or option name
**  @param string $type - 'function' or 'option'
**-------------------------------------------------------------------------*/
function srp_function_exists(){
	$name = $_POST['name'];
	$type = $_POST['type'];

	switch($type){
		case 'function':
			if(function_exists($name)){ die(true); }
			die('0');

		case 'option':
			if($option = get_option($name)){ die(true); }
			die('0');
	}
}

add_action('wp_ajax_srp_function_exists', 'srp_function_exists');
add_action('wp_ajax_nopriv_srp_function_exists', 'srp_function_exists');

function srp_walkscore($ws_wsid, $ws_address, $ws_width=500, $ws_height=286, $ws_layout = 'horizontal') {
	$output .= "
	<script type='text/javascript'>
	var ws_wsid = '{$ws_wsid}';
	var ws_address = '{$ws_address}';var ws_width = '{$ws_width}';var ws_height = '{$ws_height}';var ws_layout = '{$ws_layout}';</script><style type='text/css'>#ws-walkscore-tile{position:relative;text-align:left}#ws-walkscore-tile *{float:none;}#ws-footer a,#ws-footer a:link{font:11px Verdana,Arial,Helvetica,sans-serif;margin-right:6px;white-space:nowrap;padding:0;color:#000;font-weight:bold;text-decoration:none}#ws-footer a:hover{color:#777;text-decoration:none}#ws-footer a:active{color:#b14900}</style><div id='ws-walkscore-tile'><div id='ws-footer' style='position:absolute;top:268px;left:8px;width:488px'><form id='ws-form'><a id='ws-a' href='http://www.walkscore.com/' target='_blank'>Find out your home's Walk Score:</a><input type='text' id='ws-street' style='position:absolute;top:0px;left:225px;width:231px' /><input type='image' id='ws-go' src='http://www2.walkscore.com/images/tile/go-button.gif' height='15' width='22' border='0' alt='get my Walk Score' style='position:absolute;top:0px;right:0px' /></form></div></div><script type='text/javascript' src='http://www.walkscore.com/tile/show-walkscore-tile.php'></script>";
	return $output;
}

function _add_to_yelpselect() {
    do_action('_add_to_yelpselect');
}

//Geocoding
function srp_geocode_request($address){
	if($srp_gmap_key = get_option('srp_gmap_api_key')){
		$gapi = $srp_gmap_key;
	}elseif($srp_gmap_key = get_option('greatrealestate_googleAPIkey')){
		$gapi = $srp_gmap_key;
	}
		$request = 'http://maps.google.com/maps/geo?output=xml&oe=utf8&sensor=false&key=' .$gapi. '&q=' . $address;
		$xml = simplexml_load_file($request, 'SimpleXMLElement');
		$data = $xml->Response->Placemark->Point->coordinates;
		$coord = explode(',', $data);
		unset($coord[2]);
		return $coord;
}

function srp_geocode_ajax(){
	$address = $_POST['address'];
	if($result = srp_geocode_request($address)){
		$result = json_encode($result);
		die($result);
	}
}
add_action('wp_ajax_srp_geocode_ajax', 'srp_geocode_ajax');
add_action('wp_ajax_nopriv_srp_geocode_ajax', 'srp_geocode_ajax');

function srp_extend_gre_ajax(){
    global $srp_property_values, $srp_widgets;

    $srp_property_values = json_decode(stripslashes($_POST['srp_listing_values']), true);
	_gre_extension_content();
	die($srp_widgets->print_all());
}
add_action('wp_ajax_srp_extend_gre_ajax', 'srp_extend_gre_ajax');
add_action('wp_ajax_nopriv_srp_extend_gre_ajax', 'srp_extend_gre_ajax');

function srp_ajax_call(){
    global $srp_property_values, $srp_widgets;

    $_tmp = smartstripslashes($_REQUEST['srp_listing_values']);
    $srp_property_values = json_decode($_tmp, true);

    if(is_object($srp_property_values)){ //For PHP below 5.2
        foreach($srp_property_values as $k=>$v){
            $tmp[$k] = $v;
        }
        $srp_property_values = $tmp;
    }

    $init = call_user_func($_REQUEST['callback'], array());

        if(!is_array($init))
            return;

        foreach($init as $array){
            foreach($array as $k => $v){
                $$k = $v;
            }
            $srp_widgets->add($name, $title, $tab_name, $content, $callback_function, $init_function, $ajax, $save_to_buffer);
            break;
        }
	die($srp_widgets->print_widget($name));
}
add_action('wp_ajax_srp_ajax_call', 'srp_ajax_call');
add_action('wp_ajax_nopriv_srp_ajax_call', 'srp_ajax_call');

function srp_buffer($callback_function){
    ob_start();
    if($result = call_user_func($callback_function)){
        return $result;
    }
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}

function smartstripslashes($str) {
  $cd1 = substr_count($str, "\"");
  $cd2 = substr_count($str, "\\\"");
  $cs1 = substr_count($str, "'");
  $cs2 = substr_count($str, "\\'");
  $tmp = strtr($str, array("\\\"" => "", "\\'" => ""));
  $cb1 = substr_count($tmp, "\\");
  $cb2 = substr_count($tmp, "\\\\");
  if ($cd1 == $cd2 && $cs1 == $cs2 && $cb1 == 2 * $cb2) {
    return strtr($str, array("\\\"" => "\"", "\\'" => "'", "\\\\" => "\\"));
  }
  return $str;
}

?>