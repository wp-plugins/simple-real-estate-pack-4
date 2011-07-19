<?php
/*---------------------------------------------*
** Simple Realty Pack support functions
** to work with Great Real Estate Plugin
** Author: Max Chirkov
** WebSite: www.PhoenixHomes.com
**---------------------------------------------*/ 

if(!function_exists('greatrealestate_init')) return;

function srp_gre_admin_scripts(){
	if($g_api = get_option('greatrealestate_googleAPIkey')){		
		echo "\n" . '<script type="text/javascript">
		var srp_geo = "'. $g_api . '/";</script>'. "\n";
		echo '<script type="text/javascript" src="'.get_bloginfo('wpurl').'/wp-content/plugins/simple-real-estate-pack/js/srp-gre-admin.js"></script>';
	}
}
add_action('admin_head', 'srp_gre_admin_scripts');

/*---------------------------------------------*
** Substitute function the_listing_map_content()
** to be placed in listingpage.php template
** for Great Real Estate Plugin
**---------------------------------------------*/
function srp_gre_the_listing_map_content() {
	if (!get_option('greatrealestate_googleAPIkey')) return;
	if (!get_listing_longitude()) return;
	if (!get_listing_latitude()) return;
?>
<div id="map">
   <h2>Location Map</h2>
   <div id="map_area">
   		<div id="gre_map_canvas"></div>
		<?php 
		if (get_option('srp_yelp_api_key')){
			echo srp_yelp_select(); 
		}
		?>
		<input id="srp_gre_prop_coord" type="hidden" value="<?php echo get_listing_latitude(); ?>,<?php echo get_listing_longitude(); ?>" />
   </div>
<script type="text/javascript">
/* <![CDATA[ */	

	function gre_setupmap() {
		var prop_point = new google.maps.LatLng(<?php echo get_listing_latitude(); ?>,<?php echo get_listing_longitude(); ?>);
		gre_map.setCenter(prop_point, 13);
		var prop_marker = gre_createMarker(prop_point, '<?php the_listing_js_mapinfo(); ?>');
		gre_map.addOverlay(prop_marker);

	}
	google.load("maps", "2");
	google.setOnLoadCallback(gre_mapinitialize);
	
/* ]]> */
</script>
<div class="srp_gre_legend"><span><img src="http://www.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png" /> - Property</span></div>

</div>
<?php
}
/*------------------------------------------------------*
** School Tab to be placed in listingpage.php template
**------------------------------------------------------*/
function srp_gre_the_listing_schools_tab($before='<li>',$after='</li>'){
	if (!EDU_API_KEY) return;
	if (!get_option('greatrealestate_googleAPIkey')) return;
	if (!get_listing_longitude()) return;
	if (!get_listing_latitude()) return;
	echo $before;
	echo '<a href="#srp_education"  title="'.__("Schools Information","simplerealestatepack") . '" ><span>';
	_e("Schools","simplerealestatepack");
	echo '</span></a>';
	echo $after;
}

function srp_gre_the_listing_schools_content(){
	if (!EDU_API_KEY) return;
	if (!get_option('greatrealestate_googleAPIkey')) return;
	if (!get_listing_longitude()) return;
	if (!get_listing_latitude()) return;
	$address = get_listing_address() . ' ' . get_listing_city() . ', ' . get_listing_state() . ' ' . get_listing_postcode();
	if (!($output = srp_schoolSearch_shortcode(array("lat"=>get_listing_latitude(),  "lng"=>get_listing_longitude(), "distance"=>3, "groupby"=>"gradelevel", "output"=>"table", "location_title"=>$address)))) return;
?>
<div id="srp_education">
<h2><?php _e("Schools"); ?></h2>
   <div><?php echo $output; ?></div>
</div>
<?php
}

/*-------------------------------------------------------------------------*
** Market Trends Tab (by Trulia) to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_trulia_stats_tab($before='<li>',$after='</li>'){
	if(!function_exists('srp_get_trulia_stats')) return;
	echo $before;
	echo '<a href="#srp_market_trends"  title="'.__("Sales Statistics","simplerealestatepack") . '" ><span>';
	_e("Market Trends","simplerealestatepack");
	echo '</span></a>';
	echo $after;
}

function srp_gre_the_trulia_stats_content(){
	if(!function_exists('srp_get_trulia_stats')) return;
	global $graph_types;	
	foreach($graph_types as $k => $v){
		$id = str_replace(' ', '_', strtolower($k));
		$output .='<div id="'.$id.'">' . srp_get_trulia_stats(array('type' => $k)) . '</div>';
		$li .= '<li><a href="#'.$id.'">'.$v.'</a></li>'."\n";
	}
	print '<div id="srp_market_trends" class="srp-tabs"><h2>' . __("Market Trends") . '</h2>';;
	print "<ul>\n $li \n </ul>\n";
	print $output . '</div>';
}

/*-------------------------------------------------------------------------*
** Mortgage/Financing Tab to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_mortgage_tab($before='<li>',$after='</li>'){
	echo $before;
	echo '<a href="#srp_mortgage"  title="'.__("Mortgage/Financing","simplerealestatepack") . '" ><span>';
	_e("Mortgage/Financing","simplerealestatepack");
	echo '</span></a>';
	echo $after;
}

function srp_gre_the_mortgage_content(){
	?>	
	<div id="srp_mortgage" class="clearfix">
		<h2>Financial Tools</h2>
		<div style="float: left; width: 50%;">
			<?php echo srp_MortgageCalc_shortcode(array('price_of_home'=>get_listing_listprice()));?>
		</div>
		<div style="float:left; width: 50%;">
			<?php echo srp_ClosingCosts_shortcode(array('loan_amount'=>get_listing_listprice()));?>

			<?php echo srp_RentMeter_shortcode(array('citystatezip'=>get_listing_postcode(), 'beds'=>get_listing_bedrooms()));?>
		</div>
	</div>
	<?php
}

/*-------------------------------------------------------------------------*
** Yelp Tab to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_yelp_tab($before='<li>',$after='</li>'){
	if (!get_option('srp_yelp_api_key')) return;
	if(!function_exists('srp_Yelp_shortcode')) return;
	echo $before;
	echo '<a href="#srp_yelp"  title="'.__("Nearby Businesses","simplerealestatepack") . '" ><span>';
	_e("Nearby Businesses","simplerealestatepack");
	echo '</span></a>';
	echo $after;
}

function srp_gre_the_yelp_content(){
	if (!get_option('srp_yelp_api_key')) return;
	if(!function_exists('srp_Yelp_shortcode')) return;
	if (!($output = srp_Yelp_shortcode($atts=array("lat"=>get_listing_latitude(), "lng"=>get_listing_longitude(), 'radius' => 3, 'output' => 'table', 'sortby' => 'distance', 'term' => null, 'num_biz_requested' => null, 'ajax' => null)))) return;
?>
	<div id="srp_yelp">
	<h2><?php _e("Nearby Businesses"); ?></h2>
	   <div><?php echo $output; ?></div>
	</div>
<?php
}
?>