<?php
function pa($x){
	print '<pre>';
	print_r($x);
	print '</pre>';
}

session_start();
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
function srp_map($lat, $lng, $html, $width = NULL, $height = NULL) {
	if (!get_option('greatrealestate_googleAPIkey') && !get_option('srp_gmap_api_key')) return;

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
		var marker = srp_default_createMarker(point, \'' . $html . '\');
		srp_map.addOverlay(marker);';
		if (get_option('srp_gmap_search')) $output .= 'srp_map.enableGoogleBar();';
		$output .= '
	}
	google.setOnLoadCallback(srp_initialize);
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

}

function srp_head_scripts(){	
	$myStyleUrl		= SRP_URL . '/css/srp.css';
    $myStyleFile	= SRP_DIR . '/css/srp.css';	
    if ( file_exists($myStyleFile) ) {
        wp_register_style('srp_MortgageCalc', $myStyleUrl);
        wp_print_styles( 'srp_MortgageCalc');		
    }
	$uitabsStyle	= SRP_URL . '/css/ui.tabs.css';
	$uitabsFile		= SRP_DIR . '/css/ui.tabs.css';
	$srp_ext_gre_options = get_option('srp_ext_gre_options');
	if($srp_ext_gre_options['content']['srp_gre_css'] && file_exists($uitabsFile)){
		wp_register_style('srp_uitabs', $uitabsStyle);
        wp_print_styles( 'srp_uitabs');
	}
		echo "\n" . '<script type="text/javascript">
//<![CDATA[
			tb_pathToImage = "' . get_option('siteurl') . '/wp-includes/js/thickbox/loadingAnimation.gif";tb_closeImage = "' . get_option('siteurl') . '/wp-includes/js/thickbox/tb-close.png";</script>'. "\n";		
		echo "\n" . '<script type="text/javascript">
	var srp_url = "'. SRP_URL .'"; 
	var srp_wp_admin = "' . ADMIN_URL . '";
//]]>
' . "\n" . '</script>' . "\n";
	if(!function_exists('greatrealestate_init') || !get_option('greatrealestate_googleAPIkey')){
		if($srp_gmap_key = get_option('srp_gmap_api_key'))
		echo '<script type="text/javascript" src="http://www.google.com/jsapi?key=' . $srp_gmap_key . '"></script>';
	}
	echo '<script type="text/javascript" src="' . SRP_URL . '/js/srp-gmap.js"></script>'."\n";/**/
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

?>