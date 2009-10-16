<?php
function srp_yelp_get_api_key(){
	if(function_exists('get_option')){
		$api_key = get_option('srp_yelp_api_key');
		if($api_key != NULL){
			return $api_key;
		}
	}
}


define('YELP_API_URL', 'http://api.yelp.com/business_review_search');
define('YELP_API_KEY', srp_yelp_get_api_key());
define('YELP_OUTPUT', 'json');

$yelp_categories = array(	
	'grocery'		=> array(
							'name'		=> 'Grocery Stores',
							'category'	=> 'grocery',
							'term'		=> 'grocery',
						),
	'restaurants'	=> array(
							'name'		=> 'Restaurants',
							'category'	=> 'restaurants',
							'term'		=> 'restaurants',
						),
	'banks'			=> array(
							'name'		=> 'Banks',
							'category'	=> 'banks',
							'term'		=> 'banks',
						),
	'gas_stations'	=> array(
							'name'		=> 'Gas Stations',
							'category'	=> 'servicestations',
							'term'		=> 'gas_stations',
						),
	'golf'			=> array(
							'name'		=> 'Golf Courses',
							'category'	=> 'golf',
							'term'		=> 'golf',
						),
	'hospitals'		=> array(
							'name'		=> 'Hospitals',
							'category'	=> 'hospitals',
							'term'		=> 'hospitals',
						)
);

function srp_getYelp($lat, $lng, $radius, $output = 'table', $sortby = 'distance', $term = null, $num_biz_requested = null, $ajax = null){
	global $yelp_categories;
	if($term && $yelp_categories[$term]) {
		$_categories = array($term => $yelp_categories[$term]);
	}elseif($term && $terms = explode(',', $term)){
		foreach($terms as $t){
			$_categories[$t] = $yelp_categories[$t];
		}
	}else{
		$_categories = $yelp_categories;
	}
	//print_r($_categories);
	foreach($_categories as $cat){
		$args = array(
			'term'				=> $cat['term'],
			'num_biz_requested'	=> $num_biz_requested,
			'lat'				=> $lat,
			'long'				=> $lng,
			'radius'			=> $radius,
			'ywsid'				=> YELP_API_KEY,
			'output'			=> YELP_OUTPUT,
			'category'			=> $cat['category'],
		);
		
		if(count($_categories) > 1){		
			$wrap_open = '<div id="tabs-'.$cat['term'].'">'
				. '<h3>' . __($cat['name']). '</h3>';
			$wrap_close = '</div>';
		}
		
		$args = array_filter($args);
		$query_arr = array();
		foreach($args as $k => $v){
			$query_arr[] = $k . '=' . $v;
		}
		$query = implode('&', $query_arr);
		$request = YELP_API_URL . '?' . $query;
		//print $request;
		$result = json_decode(file_get_contents($request), true);
		$phparray = $result;

		if(count($phparray['businesses']) > 0){
			if(count($_categories) > 1){
				$tabs .= '<li><a href="#tabs-'.$cat['term'].'"  title="'.__($cat['name'],"simplerealestatepack") . '" ><span>'
				. __($cat['name'],"simplerealestatepack")
				. '</span></a></li>' . "\n";
			}
			$x = 0;
			//pre-sorting
			$businesses = array();
			$coordinates = array();	
			$table = null;
			foreach($phparray['businesses'] as $item){
				$businesses[] = array($item[$sortby], 'biz' => $item);
			}
			switch($sortby){					
					case 'avg_rating':
						rsort($businesses);
						break;
					case 'distance':
					case 'name':
						sort($businesses);
						break;
				}
				//print_r($businesses);
			foreach($businesses as $item){
				$biz = $item['biz'];
				$x++;
				if($x%2){ $even_odd = "even"; } else { $even_odd = "odd"; }
				$coordinates[$cat['term']][$biz['id']]['lat'] = $biz['latitude'];
				$coordinates[$cat['term']][$biz['id']]['lng'] = $biz['longitude'];
				//$coordinates[$cat['term']][$biz['id']]['html'] = '<div class="srp_infoWindow"><div class="yelp_rating"><img src="'.$biz['rating_img_url'].'" width="84" height="17" align="left"/></div><div class="yelp_photo"><img src="' . $biz['photo_url'].'" width="100" height="100"/></div><div class="yelp_text"><span class="school_name"><a href="'.$biz['url'].'" target="_blank">'.$biz['name'].'</a></span><br />Phone: '. format_phone($biz['phone']) .'<br />' . $biz['address1'].', '. $biz['city'].', '.$biz['state_code'].' '. $biz['zip'] .'<div id="yelp_attribution"><a href="http://www.yelp.com"><img src="'. SRP_URL .'/branding/reviewsFromYelpWHT.gif" width="115" height="25" alt="Reviews from Yelp.com" /></a></div></div></div>';
				$coordinates[$cat['term']][$biz['id']]['html'] = '<div class="srp_infoWindow">
					<table border="0" cellpadding="0" cellspacing="0" style="width: 315px;" class="srp_infoWindow">
					  <tr>
						<td style="vertical-align: top; width: 200px; margin: 0;"><img src="'.$biz['rating_img_url'].'" width="84" height="17" align="left"/><div class="yelp_text"><span class="school_name"><a href="'.$biz['url'].'" target="_blank">'.$biz['name'].'</a></span><br />Phone: '. format_phone($biz['phone']) .'<br />' . $biz['address1'].', '. $biz['city'].', '.$biz['state_code'].' '. $biz['zip'] .'</div></td>
						<td style="vertical-align: top;"><img src="' . $biz['photo_url'].'" width="100" height="100" class="yelp_photo"/>
							<div id="yelp_attribution"><a href="http://www.yelp.com"><img src="'. SRP_URL .'/branding/reviewsFromYelpWHT.gif" width="115" height="25" alt="Reviews from Yelp.com" align="right"/></a></div>
						</td>
					  </tr>
					</table>
				</div>';
				
				$table .= '<tr class="' . $even_odd . '">
						<td style="vertical-align: middle;"><div class="yelp_text"><span class="school_name"><a href="'.$biz['url'].'" target="_blank">'.$biz['name'].'</a></span><br />Phone: '. format_phone($biz['phone']) .'<br />' . $biz['address1'].', '. $biz['city'].', '.$biz['state_code'].' '. $biz['zip'] .'</div></td>
						<td style="vertical-align: middle;">
							<div class="yelp_distance">' . round($biz['distance'], 2) . ' miles</div>
						</td>
						<td style="vertical-align: top;"><img src="' . $biz['photo_url'].'" width="100" height="100" class="yelp_photo" align="right"/><br /><img src="'.$biz['rating_img_url'].'" width="84" height="17" align="right" class="yelp_rating"/>							
						</td>
					  </tr>';
			}
			
			//$_SESSION['srp_coordinates'] = $coordinates;
			if($ajax)
			{
				$ajax_output .= json_encode($coordinates);
			}elseif($table)
			{
				$content_output .= $wrap_open;
				$content_output .= '<table class="srp_table tableStyle">' . $table . '</table><div id="yelp_attribution"><a href="http://www.yelp.com"><img src="'. SRP_URL .'/branding/reviewsFromYelpWHT.gif" width="115" height="25" alt="Reviews from Yelp.com" align="right"/></a></div>';
				$content_output .= $wrap_close;
			}
		}
	}
	if($ajax_output){
		return $ajax_output;
	}else{
		return '<div class="srp-tabs"><ul>' . $tabs . '</ul><div style="clear:both;"></div>' . $content_output . '</div>';
	}
}

function srp_yelp_select(){
	global $yelp_categories;
	$output = '<div id="yelp_select">';
	$output .= apply_filters('_add_to_yelpselect', $value);
	foreach($yelp_categories as $cat){
		$output .= '<input id="yelp_cat_'.$cat['term'].'" name="'.$cat['term'].'" type="checkbox"><label for="'.$cat['term'].'">'.$cat['name'].'</label><br />' . "\n";
	}
	//$output .= '<a class="poweredbysrp" href="http://wordpress.org/extend/plugins/simple-real-estate-pack/">Powered by <span>SRP</span></a>';
	$output .= '</div>';
	
	return $output;
}

function srp_getYelp_ajax(){
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$radius = $_POST['radius'];
	$term = $_POST['term'];
	if($result = srp_getYelp($lat, $lng, $radius=3, $output = 'table', $sortby = 'distance', $term , $num_biz_requested = null, $ajax = true)){
		die($result);
	}
}

add_action('wp_ajax_srp_getYelp_ajax', 'srp_getYelp_ajax');
add_action('wp_ajax_nopriv_srp_getYelp_ajax', 'srp_getYelp_ajax');
?>