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
	if (!get_option('greatrealestate_googleAPIkey')) return;

	   if($width){ $width = "width:{$width}px;"; }
	   if($height){ $height = "height:{$height}px;"; }
	   ?>
	<div id="map">
	  <div id="map_area" style="<?php echo $width . $height; ?>">
   		<div id="gre_map_canvas" style="<?php echo $width . $height; ?>"></div>
		<?php 
		if (get_option('srp_yelp_api_key')){
			echo srp_yelp_select(); 
		}
		?>
		<input id="srp_gre_prop_coord" type="hidden" value="<?php echo $lat; ?>,<?php echo $lng; ?>" />		
	   </div>
	   <div class="srp_gre_legend"><span><img src="http://www.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png" /> - Main Marker</span></div>
	</div>
<script type="text/javascript">
/* <![CDATA[ */

	function srp_setupmap() {
		var point = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);	
		srp_map.setCenter(point, 13);	
		var marker = srp_default_createMarker(point, '<?php echo $html; ?>');
		srp_map.addOverlay(marker);
		
	}
	google.setOnLoadCallback(srp_initialize);
/* ]]> */
</script>
<?php
}

/*
** CSS and JS initialization
*/
function srp_default_headScripts(){
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
	if(is_admin()){
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
	$uitabsStyle	= SRP_URL . '/css/ui.tabs.css';
    if ( file_exists($myStyleFile) ) {
        wp_register_style('srp_MortgageCalc', $myStyleUrl);
        wp_print_styles( 'srp_MortgageCalc');
		wp_register_style('srp_uitabs', $uitabsStyle);
        wp_print_styles( 'srp_uitabs');		
    }		
	if(!is_admin()){		
		echo "\n" . '<script type="text/javascript">
			tb_pathToImage = "' . get_option('siteurl') . '/wp-includes/js/thickbox/loadingAnimation.gif";tb_closeImage = "' . get_option('siteurl') . '/wp-includes/js/thickbox/tb-close.png";</script>'. "\n";		
		echo "\n" . '<script type="text/javascript">
			var srp_url = "'. SRP_URL .'"; 
			var srp_wp_admin = "' . ADMIN_URL . '";' . "\n" . '</script>' . "\n";
	}
	if(!function_exists('greatrealestate_init') || !get_option('greatrealestate_googleAPIkey')){
		if($srp_gmap_key = get_option('srp_gmap_api_key'))
		echo '<script type="text/javascript" src="http://www.google.com/jsapi?key=' . $srp_gmap_key . '"></script>';
	}
	echo '<script type="text/javascript" src="' . SRP_URL . '/js/srp-gmap.js"></script>';/**/
}
function srp_footer_scripts(){
	echo '<script type="text/javascript" src="' . SRP_URL . '/js/srp-MortgageCalc.js"></script>';	
	echo '<script type="text/javascript" src="' . SRP_URL . '/lib/jquery.formatCurrency-1.0.0.js"></script>';
}

add_action('plugins_loaded', 'srp_default_headScripts');
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
?>