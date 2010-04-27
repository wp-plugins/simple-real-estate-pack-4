<?php
session_start();
function srp_get_rentometer_rates($return_rate = false){

	$api_key = $_SESSION['srp_rentometer_api_key'];
	
	$url	= "http://out.rentometer.com/ws/query?token=";
	$args = array(
	/*	'citystatezip'	=> "85022",
		'city'	=> "Phoenix",
		'state'	=> "AZ",
		'zip'	=> "85022",
		'rent'	=> 830,
		'beds'	=> 2, */
	);
	
	if(!empty($_GET['citystatezip'])){
		$args['citystatezip'] = $_GET['citystatezip'];
	}
	if(!empty($_GET['rent'])){
		$args['rent'] = $_GET['rent'];
	}
	if(!empty($_GET['beds'])){
		$args['beds'] = $_GET['beds'];
	}

	foreach($args as $k => $v){
		if($v){
			$query .= '&' . $k . '=' . $v;
		}
	}
	
	if($query){
		$request_url = $url . $api_key . $query;
	}else{
		return;
	}
	if($api_key){
		if(!$xml = simplexml_load_file($request_url, 'SimpleXMLElement')) return false;	
	
		print json_encode($xml);
	}
}

srp_get_rentometer_rates($return_rate = false);

?>