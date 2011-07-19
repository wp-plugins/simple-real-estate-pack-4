<?php
/*---------------------------------------------*
** Simple Realty Pack support functions
** to work with Great Real Estate Plugin
** Author: Max Chirkov
** WebSite: www.PhoenixHomes.com
**---------------------------------------------*/ 

if(!function_exists('greatrealestate_init')) return;
if(!$srp_ext_gre_options = get_option('srp_ext_gre_options')) return;
$srp_ext_gre_content = array_keys($srp_ext_gre_options['content']);
$srp_ext_gre_tabs = $srp_ext_gre_options['tabs'];

function srp_gre_admin_scripts(){
	if($g_api = get_option('greatrealestate_googleAPIkey')){		
		echo "\n" . '<script type="text/javascript">
		var srp_geo = "'. $g_api . '/";</script>'. "\n";
		echo '<script type="text/javascript" src="'.SRP_URL.'/js/srp-gre-admin.js"></script>';
	}
}
add_action('admin_head', 'srp_gre_admin_scripts');

/*---------------------------------------------*
** Include srp_gre_extention_tabs()
** and srp_gre_extention_content() into the 
** listingpage.php
**---------------------------------------------*/
function srp_gre_extention_tabs() {
	if(!get_option('srp_ext_gre_options')) return;
	srp_gre_the_mortgage_tab();	
	srp_gre_the_trulia_stats_tab();	
	srp_gre_the_altos_stats_tab();
	srp_gre_the_listing_schools_tab();
	srp_gre_the_yelp_tab();
	srp_gre_the_walkscore_tab();
}

function srp_gre_extention_content() {
	if(!get_option('srp_ext_gre_options')){
		echo '<div style="background:red; color: white; font-weight: bold; padding: 10px;">Please visit the <a href="'.ADMIN_URL.'/admin.php?page=srp_ext_gre">Extension to GRE settings</a> page to complete the installation.</div>';
	}else{
		srp_gre_the_mortgage_content();	
		srp_gre_the_trulia_stats_content();
		srp_gre_the_altos_stats_content();
		srp_gre_the_listing_schools_content();
		srp_gre_the_yelp_content();
		srp_gre_the_walkscore_content();
	}
}

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
		if (get_option('srp_yelp_api_key') && get_option('srp_gmap_yelp')){
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
		<?php if (get_option('srp_gmap_search')) echo 'gre_map.enableGoogleBar();';?>
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
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('schools', $srp_ext_gre_content)) return;
	
	if (!EDU_API_KEY) return;
	if (!get_option('greatrealestate_googleAPIkey')) return;
	if (!get_listing_longitude()) return;
	if (!get_listing_latitude()) return;
	echo $before;
	echo '<a href="#srp_education"  title="'.__("Schools Information","simplerealestatepack") . '" ><span>';
	_e($srp_ext_gre_tabs['schools']['tabname'],"simplerealestatepack");
	echo '</span></a>';
	echo $after;
}

function srp_gre_the_listing_schools_content(){
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('schools', $srp_ext_gre_content)) return;
	
	if (!EDU_API_KEY) return;
	if (!get_option('greatrealestate_googleAPIkey')) return;
	if (!get_listing_longitude()) return;
	if (!get_listing_latitude()) return;
	$address = get_listing_address() . ' ' . get_listing_city() . ', ' . get_listing_state() . ' ' . get_listing_postcode();
	if (!($output = srp_schoolSearch_shortcode(array("lat"=>get_listing_latitude(),  "lng"=>get_listing_longitude(), "distance"=>3, "groupby"=>"gradelevel", "output"=>"table", "location_title"=>$address)))) return;
?>
<div id="srp_education">
<h2><?php _e($srp_ext_gre_tabs['schools']['heading']); ?></h2>
   <div><?php echo $output; ?></div>
</div>
<?php
}

/*-------------------------------------------------------------------------*
** Market Trends Tab (by Trulia) to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_trulia_stats_tab($before='<li>',$after='</li>'){
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('trulia_stats', $srp_ext_gre_content)) return;
	
	if(!function_exists('srp_get_trulia_stats')) return;
	echo $before;
	echo '<a href="#srp_market_trends"  title="'.__($srp_ext_gre_tabs['stats']['tabname'],"simplerealestatepack") . '" ><span>';
	_e($srp_ext_gre_tabs['stats']['tabname'],"simplerealestatepack");
	echo '</span></a>';
	echo $after;
}

function srp_gre_the_trulia_stats_content(){
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('trulia_stats', $srp_ext_gre_content)) return;
	
	if(!function_exists('srp_get_trulia_stats')) return;
	global $graph_types;	
	foreach($graph_types as $k => $v){
		$id = str_replace(' ', '_', strtolower($k));
		$output .='<div id="'.$id.'">' . srp_get_trulia_stats(array('type' => $k)) . '</div>';
		$li .= '<li><a href="#'.$id.'">'.$v.'</a></li>'."\n";
	}
	print '<div id="srp_market_trends" class="srp-tabs"><h2>' . __($srp_ext_gre_tabs['stats']['heading']) . '</h2>';;
	print "<ul>\n $li \n </ul>\n";
	print $output . '</div>';
}

/*-------------------------------------------------------------------------*
** Market Trends Tab (by ALTOS Research) to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_altos_stats_tab($before='<li>',$after='</li>'){
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('altos_stats', $srp_ext_gre_content)) return;

	if(!function_exists('srp_get_altos_stats')) return;
	echo $before;
	echo '<a href="#srp_market_trends"  title="'.__($srp_ext_gre_tabs['stats']['tabname'],"simplerealestatepack") . '" ><span>';
	_e($srp_ext_gre_tabs['stats']['tabname'],"simplerealestatepack");
	echo '</span></a>';
	echo $after;
}

function srp_gre_the_altos_stats_content($width = 600, $height = 400){
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('altos_stats', $srp_ext_gre_content)) return;

	if(!function_exists('srp_get_altos_stats')) return;
	global $metrics, $pricerangequartile, $rollingaverage;
	foreach($metrics as $k => $v){
		$id = str_replace(' ', '_', strtolower($k));
		$output .='<div id="'.$id.'">' . srp_get_altos_stats(array('type' => $k, 'width' => $width, 'height' => $height)) . '</div>';
		$li .= '<li><a href="#'.$id.'">'.$v.'</a></li>'."\n";
	}
	print '<div id="srp_market_trends" class="srp-tabs"><h2>' . __($srp_ext_gre_tabs['stats']['heading']) . '</h2>';
	print "<ul>\n {$li} \n </ul>\n";
	print $output . '</div>';
}

/*-------------------------------------------------------------------------*
** Mortgage/Financing Tab to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_mortgage_tab($before='<li>',$after='</li>'){
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('mortgage_calc', $srp_ext_gre_content) && !in_array('closing_estimator', $srp_ext_gre_content) && !in_array('affordability_calc', $srp_ext_gre_content) && !in_array('rental_meter', $srp_ext_gre_content)) return;

	echo $before;
	echo '<a href="#srp_mortgage"  title="'.__($srp_ext_gre_tabs['mortgage']['tabname'],"simplerealestatepack") . '" ><span>';
	_e($srp_ext_gre_tabs['mortgage']['tabname'],"simplerealestatepack");
	echo '</span></a>';
	echo $after;
}

function srp_gre_the_mortgage_content(){
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('mortgage_calc', $srp_ext_gre_content) && !in_array('closing_estimator', $srp_ext_gre_content) && !in_array('affordability_calc', $srp_ext_gre_content) && !in_array('rental_meter', $srp_ext_gre_content)) return;
	?>	
	<div id="srp_mortgage" class="clearfix">
		<?php print '<h2>' . __($srp_ext_gre_tabs['mortgage']['heading']) . '</h2>';?>
		<?php if(in_array('mortgage_calc', $srp_ext_gre_content)){ ?>
		<div style="float: left; width: 50%;">
			<?php echo srp_MortgageCalc_shortcode(array('price_of_home'=>get_listing_listprice()));?>
		</div>
		<?php } ?>
		<div style="float:left; width: 50%;">
			<?php 
			if(in_array('closing_estimator', $srp_ext_gre_content)){
				echo srp_ClosingCosts_shortcode(array('loan_amount'=>get_listing_listprice()));
			}?>
		</div>
		<div style="float:left; width: 50%;">
			<?php
			if(in_array('affordability_calc', $srp_ext_gre_content)){
				echo srp_AffordabilityCalc_shortcode();
			} 
			?>
		</div>
		<div style="float:left; width: 50%;">
			<?php
			if(in_array('rental_meter', $srp_ext_gre_content)){
				echo srp_RentMeter_shortcode(array('citystatezip'=>get_listing_postcode(), 'beds'=>get_listing_bedrooms()));
			}			
			?>
		</div>
	</div>
	<?php
}

/*-------------------------------------------------------------------------*
** Yelp Tab to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_yelp_tab($before='<li>',$after='</li>'){
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('yelp', $srp_ext_gre_content)) return;
	
	if (!get_option('srp_yelp_api_key')) return;
	if(!function_exists('srp_Yelp_shortcode')) return;
	echo $before;
	echo '<a href="#srp_yelp"  title="'.__($srp_ext_gre_tabs['yelp']['tabname'],"simplerealestatepack") . '" ><span>';
	_e($srp_ext_gre_tabs['yelp']['tabname'],"simplerealestatepack");
	echo '</span></a>';
	echo $after;
}

function srp_gre_the_yelp_content(){
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('yelp', $srp_ext_gre_content)) return;
	
	if (!get_option('srp_yelp_api_key')) return;
	if(!function_exists('srp_Yelp_shortcode')) return;
	if (!($output = srp_Yelp_shortcode($atts=array("lat"=>get_listing_latitude(), "lng"=>get_listing_longitude(), 'radius' => 3, 'output' => 'table', 'sortby' => 'distance', 'term' => null, 'num_biz_requested' => null, 'ajax' => null)))) return;
?>
	<div id="srp_yelp">
	<h2><?php _e($srp_ext_gre_tabs['yelp']['heading']); ?></h2>
	   <div><?php echo $output; ?></div>
	</div>
<?php
}

/*-------------------------------------------------------------------------*
** Walkscore Tab to be placed in listingpage.php template
**-------------------------------------------------------------------------*/
function srp_gre_the_walkscore_tab($before='<li>',$after='</li>'){
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('walkscore', $srp_ext_gre_content)) return;
	
	if (!get_option('srp_walkscore_api_key')) return;
	echo $before;
	echo '<a href="#srp_walkscore"  title="'.__($srp_ext_gre_tabs['walkscore']['tabname'],"simplerealestatepack") . '" ><span>';
	_e($srp_ext_gre_tabs['walkscore']['tabname'],"simplerealestatepack");
	echo '</span></a>';
	echo $after;
}

function srp_gre_the_walkscore_content(){
	global $srp_ext_gre_content, $srp_ext_gre_tabs;
	if(!in_array('walkscore', $srp_ext_gre_content)) return;
	if (!$ws_wsid = get_option('srp_walkscore_api_key')) return;
	$ws_address = get_listing_address() . ' ' . get_listing_city() . ', ' . get_listing_state() . ' ' . get_listing_postcode();
	?>
	<div id="srp_walkscore">
	<h2><?php _e($srp_ext_gre_tabs['walkscore']['heading']); ?></h2>
	   <div>	
	<?php
	echo srp_walkscore($ws_wsid, $ws_address, $ws_width=500, $ws_height=286, $ws_layout = 'horizontal');
	?>
		</div>
	</div>
	<?php
}
?>