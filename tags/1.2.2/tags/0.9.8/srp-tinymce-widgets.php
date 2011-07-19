<?php

define('TRULIA_VER', 163);

$graph_types = array(
	'qma_median_sales_price'	=> 'Median Sales Price',
	'qma_sales_volume'			=> 'Sales Volume',
	'average_listing_price'		=> 'Average Listing Price',
	'listing_volume'			=> 'Number of Properties',
	'qma_price_per_sqft'		=> 'Average Price Per Square Foot',
);

function srp_get_trulia_stats($atts=array()){
	global $graph_types, $srp_property_values;
	$args = shortcode_atts(array(
		"width"		=> 500,
		"height"	=> 300,
		"type"		=> 'qma_median_sales_price',
		"city"		=> $srp_property_values['city'],
		"state"		=> $srp_property_values['state'],
		"zipcode"	=> $srp_property_values['zip_code'],
		"period"	=> 'all',
		"exclude"	=> false,
	), $atts);
	$args = array_filter($args);
	$city_state = $args['city'] . '-' . srp_get_states($args['state']);
	$url = 'http://graphs.trulia.com/real_estate/' . $city_state . '/graph.png?version=' . TRULIA_VER;
	
	foreach($args as $k => $v){
		$query .= '&' . $k . '=' . $v;
	}
	
	$img = '<img src="'.$url . $query.'" alt="'.$graph_types[$args['type']].'" width="'.$args['width'].'" height="'.$args['height'].'"/>';		
	
	return $img;
}


/*
* Altos Charts
*/
$metrics = array(
	'median'				=> 'Price',
	'mean_dom'				=> 'Avg Days on Market',
	'median_market_heat'	=> 'Market Action Index',
	'inventory'				=> 'Inventory',
);

$pricerangequartile = array(
	't'	=> 'Top',
	'u'	=> 'Upper-Middle',
	'l'	=> 'Lower-Middle',
	'b'	=> 'Bottom',
	'a'	=> 'All quartiles combined',
);

$rollingaverage = array(
	'a'	=> '7-Day',
	'c'	=> '90-Day',
);

function srp_get_altos_stats($atts=array()){
	global $metrics;
	$args = shortcode_atts(array(
		"width"		=> null,
		"type"		=> 'median',
		"city"		=> null,
		"state"		=> null,
		"zipcode"	=> null,
	), $atts);
	$args = array_filter($args);
	$url = 'http://charts.altosresearch.com/altos/app?s='.$args['type'].':l,&ra=c&st='.$args['state'].'&c='.$args['city'].'&z='.$args['zipcode'].'&sz=l&service=chart';
	
	$img = '<img src="'.$url . $query.'" alt="'.$graph_types[$args['type']].'" alt="'.$metrics[$args['type']].' in '.$args['city'].', '. $args['state'] . ' ' . $args['zipcode'].'" width="'.$args['width'].'"/>';		
	
	return $img;
}

/*
* GMap
*/

if(get_option('greatrealestate_googleAPIkey')){
	define("GMAP_API", get_option('greatrealestate_googleAPIkey'));
}elseif(get_option('srp_gmap_api_key')){
	define("GMAP_API", get_option('srp_gmap_api_key'));
}
?>