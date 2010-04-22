<?php
$srp_widgets = new srpWidgets();

add_action('admin_menu', 'simpleRealEstatePack_menu');

add_action('admin_init', 'srp_options_init');
function srp_options_init(){
    register_setting('srp-general-options', 'srp_general_options');
    register_setting('srp-gre-extension-options', 'srp_ext_gre_options');
    register_setting('srp-mortgage-rates-options', 'srp_mortgage_rates');
    register_setting('srp-rentometer-options', 'srp_rentometer_api_key');
    register_setting('srp-yelp-options', 'srp_yelp_api_key');
    register_setting('srp-walkscore-options', 'srp_walkscore_api_key');
    register_setting('srp-education-options', 'srp_education_api_key');
    register_setting('srp-mortgage-calc-options', 'srp_mortgage_calc_options');
}

function simpleRealEstatePack_menu(){	
	add_menu_page('Simple Real Estate Pack', 'Real Estate Pack', 8, __FILE__, 'srp_show_menu', WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)). '/../images/logo_22.png');
	add_submenu_page(__FILE__, 'Mortgage Calcs Options', 'Mortgage Calcs', 8, 'srp_mortgage_calc', 'srp_show_menu');
	add_submenu_page(__FILE__, 'Mortgage Rates Options', 'Mortgage Rates', 8, 'srp_mortgage_rates', 'srp_show_menu');
	add_submenu_page(__FILE__, 'Rental Rates Meter API Key Setup', 'Rental Rates Meter', 8, 'srp_rentmeter', 'srp_show_menu');
	//add_submenu_page(__FILE__, 'Education API Key Setup', 'Education', 8, 'srp_education', 'srp_show_menu');
	add_submenu_page(__FILE__, 'Yelp API Settings', 'Yelp API Settings', 8, 'srp_yelp', 'srp_show_menu');
	add_submenu_page(__FILE__, 'Walkscore API Settings', 'Walkscore API Settings', 8, 'srp_walkscore', 'srp_show_menu');	
	add_submenu_page(__FILE__, 'Google Maps API', 'Google Maps', 8, 'srp_gmap', 'srp_show_menu');	
        add_submenu_page(__FILE__, 'Neighborhood', 'Neighborhood', 8, 'srp_profile', 'srp_show_menu');
}


function srp_show_menu() {
	global $wp_version;
	switch ($_GET["page"]) {
	case "srp_mortgage_rates" :
		include_once (dirname (__FILE__) . '/mortgage_rates.php');
		srp_MortgageRates_options_page();
		break;
	
	case "srp_mortgage_calc" :		
	default :
		include_once (dirname (__FILE__) . '/mortgage_calc.php');
		simpleMortgageCalc_options_page();
		break;
		
	case "srp_rentmeter" :
		include_once (dirname (__FILE__) . '/rentmeter.php');		
		srp_RentMeter_options_page();
		break;

        //Education API Key is hardcoded - no need for the education.php
	case "srp_education" :
		include_once (dirname (__FILE__) . '/education.php');		
		srp_Education_options_page();
		break;
		
	case "srp_yelp" :
		include_once (dirname (__FILE__) . '/yelp.php');		
		srp_Yelp_options_page();
		break;
	
	case "srp_walkscore" :
		include_once (dirname (__FILE__) . '/walkscore.php');		
		srp_Walkscore_options_page();
		break;
	
	case "srp_gmap" :
		include_once (dirname (__FILE__) . '/srp_gmap.php');		
		srp_gmap_options();
		break;
		
	case "srp_profile" :
		include_once (dirname (__FILE__) . '/srp_profile.php');
		srp_profile_options_page();
		break;
		
	default :
                include_once (dirname (__FILE__) . '/main.php');
                //srp_MainAdmin_page();
                srp_general_options_page();
                break;
		
	}
}

function _default_settings_MortgageCalc(){
	//All rates are in %	
	$options['annual_interest_rate'] 	= 6;
	$options['property_tax_rate'] 	= 1;
	$options['home_insurance_rate'] 	= 0.5;
	$options['pmi']					= 0.5;
	$options['origination_fee']		= 1; //%
	$options['mortgage_term']			= 30;
	$options['lender_fees']			= 600;
	$options['credit_report_fee']		= 50;
	$options['appraisal']				= 300;
	$options['title_insurance']		= 800;
	$options['reconveyance_fee']		= 75;
	$options['recording_fee']			= 45;
	$options['wire_courier_fee']		= 55;
	$options['endorsement_fee']		= 75;
	$options['title_closing_fee']		= 125;
	$options['title_doc_prep_fee']	= 30;

	return $options;
}

//get_option substitute to use inside WP_Widget class
function srp_get_option($option, $instance = null){
    $mortgage_rates_options = get_option('srp_mortgage_rates');
	if($instance != null){
		return $instance;
	}else{		
		/*--BEGIN return zillow rate--*/
		if($option == 'annual_interest_rate'){
			if($mortgage_rates_options['use_rates_in_calcs'] && $mortgage_rates_options['getratesummary_api_key']){
				$rate = srp_get_zillow_mortgage_rates($return_rate = true);
				if($rate){
					add_filter('widget', 'srp_mortgage_rates_branding',9);
					return $rate;
				}
			}
		}		
		/*--END return zillow rate--*/
		
	}
        return;
}

function srp_updated_message($updated = false){
	if($_GET['updated'] == true || $updated == true){
  		echo '<div class="updated"><p>Your settings have been saved.</p></div>';
  	}
}

/**
		 * Create a potbox widget
		 */
		function postbox($id, $title, $content) {
			$content = '<div id="'.$id.'" class="postbox">
				<div class="handlediv" title="Click to toggle"><br /></div>
				<h3 class="hndle"><span>'.$title.'</span></h3>
				<div class="inside">'
					. $content
				.'</div>
			</div>';
			return $content;
		}

function srp_like_plugin(){
	$content = '	
	<p>Help us spread the word :)</p>
	<ul>
		<li>Link to it or blog about the plugin, so other users can find out about it.</li>
		<li>Give it a good rating on <a href="http://wordpress.org/extend/plugins/simple-real-estate-pack-4/">WordPress.org</a></li>
	</ul>';	
	
	return $content;
}

function srp_plugin_support(){
	$content = '<p>If you have any problems with this plugin or good ideas for improvements or new features, please talk about them in the <a href="http://wordpress.org/tags/simple-real-estate-pack-4?forum_id=10">Support forums</a>.</p>';	
	$content = '
	<p>Help us make it better:</p>
	<ul>
		<li><a href="http://wordpress.org/tags/simple-real-estate-pack-4?forum_id=10">Ask for help</a></li>
		<li><a href="http://wordpress.org/tags/simple-real-estate-pack-4?forum_id=10">Report a bug</a></li>
		<li><a href="http://wordpress.org/tags/simple-real-estate-pack-4?forum_id=10">Suggest improvements or new features</a></li>
	</ul>';
	return $content;
}

function srp_plugin_credits(){
	$content = '
	<ul>
		<li><a href="http://www.phoenixhomes.com/tech/simple-real-estate-pack">Official Plugin Page</a></li>
		<li>Designed by <a href="http://wordpress.org/extend/plugins/profile/maxchirkov">Max Chirkov</a></li>
		<li>Sponsored by <a href="http://www.phoenixhomes.com">PhoenixHomes.com</a></li>
	</ul>';
	return $content;
}

function srp_plugin_donate(){
	$content = '
	<p>
		If you would like to make a financial contribution, as a jester of your appreciation for this free plugin, please consider a donation to the <a href="https://www.cancer.org/aspx/Donation/DON_1_Donate_Online_Now.aspx" title="Donate to American Cancer Society">American Cancer Society</a>		
	</p>
	<div style="text-align:center"><a href="https://www.cancer.org/aspx/Donation/DON_1_Donate_Online_Now.aspx" title="Donate to American Cancer Society"><img src="'.SRP_URL.'/images/ACS-logo.jpg" alt="American Cancer Society Logo" title="Donate to American Cancer Society" /></a></div>
	';
	return $content;
}

function srp_settings_right_column(){
	$content = '<div class="postbox-container" style="width:20%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">'				
				. postbox('srp_like_plugin', 'Like this plugin?', srp_like_plugin())
				. postbox('srp_plugin_support', 'Plugin Support', srp_plugin_support())
				. postbox('srp_plugin_credits', 'Credits', srp_plugin_credits())
				. postbox('srp_plugin_donate', 'Donate', srp_plugin_donate())
			. '</div>
			<br/><br/><br/>
		</div>
	</div>';
	return $content;
}
?>