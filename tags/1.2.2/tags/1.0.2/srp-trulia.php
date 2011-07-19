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
	global $graph_types;
	$args = shortcode_atts(array(
		"width"		=> 500,
		"height"	=> 300,
		"type"		=> 'qma_median_sales_price',
		"city"		=> null,
		"state"		=> null,
		"zipcode"	=> null,
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
?>