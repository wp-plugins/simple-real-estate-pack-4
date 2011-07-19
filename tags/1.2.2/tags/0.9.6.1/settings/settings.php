<?php

add_action('admin_menu', 'simpleRealEstatePack_menu');

function simpleRealEstatePack_menu(){
	//add_options_page('simpleRealEstatePack Options', 'simpleRealEstatePack', 8, __FILE__, 'simpleRealEstatePack_options');
	//add_options_page(page_title, menu_title, access_level/capability, file, [function]);
	add_menu_page('Simple Real Estate Pack', 'Real Estate Pack', 8, __FILE__, 'srp_show_menu', WP_CONTENT_URL . '/plugins/' . plugin_basename(dirname(__FILE__)). '/../images/logo_22.png');
	add_submenu_page(__FILE__, 'Mortgage Calcs Options', 'Mortgage Calcs', 8, 'srp_mortgage_calc', 'srp_show_menu');
	add_submenu_page(__FILE__, 'Mortgage Rates Options', 'Mortgage Rates', 8, 'srp_mortgage_rates', 'srp_show_menu');
	add_submenu_page(__FILE__, 'Rental Rates Meter API Key Setup', 'Rental Rates Meter', 8, 'srp_rentmeter', 'srp_show_menu');
	//add_submenu_page(__FILE__, 'Education API Key Setup', 'Education', 8, 'srp_education', 'srp_show_menu');
	add_submenu_page(__FILE__, 'Yelp API Settings', 'Yelp API Settings', 8, 'srp_yelp', 'srp_show_menu');
	add_submenu_page(__FILE__, 'Walkscore API Settings', 'Walkscore API Settings', 8, 'srp_walkscore', 'srp_show_menu');	
	add_submenu_page(__FILE__, 'Google Maps API', 'Google Maps', 8, 'srp_gmap', 'srp_show_menu');
	if(function_exists('greatrealestate_init')){
		add_submenu_page(__FILE__, 'Extension to GRE', 'Extension to GRE', 8, 'srp_ext_gre', 'srp_show_menu');
	}
}

function _default_settings_SRP(){
	//All rates are in %
	$options = new stdClass();
	$options->srp_annual_interest_rate 	= 6;
	$options->srp_property_tax_rate 	= 1;
	$options->srp_home_insurance_rate 	= 0.5;
	$options->srp_pmi					= 0.5;	
	$options->srp_origination_fee		= 1; //%
	$options->srp_mortgage_term			= 30;	
	$options->srp_lender_fees			= 600;
	$options->srp_credit_report_fee		= 50;
	$options->srp_appraisal				= 300;
	$options->srp_title_insurance		= 800;
	$options->srp_reconveyance_fee		= 75;
	$options->srp_recording_fee			= 45;
	$options->srp_wire_courier_fee		= 55;
	$options->srp_endorsement_fee		= 75;
	$options->srp_title_closing_fee		= 125;
	$options->srp_title_doc_prep_fee	= 30;
	
	return $options;
}


function srp_show_menu() {
	global $wp_version;
	switch ($_GET["page"]) {
	case "srp_mortgage_rates" :
		include_once (dirname (__FILE__) . '/mortgage_rates.php');
		srp_MortgageRates_options();
		break;
	
	case "srp_mortgage_calc" :		
	default :
		include_once (dirname (__FILE__) . '/settings.php');
		simpleRealEstatePack_options();
		break;
		
	case "srp_rentmeter" :
		include_once (dirname (__FILE__) . '/rentmeter.php');		
		srp_RentMeter_options();
		break;
		
	case "srp_education" :
		include_once (dirname (__FILE__) . '/education.php');		
		srp_Education_options();
		break;
		
	case "srp_yelp" :
		include_once (dirname (__FILE__) . '/yelp.php');		
		srp_Yelp_options();
		break;
	
	case "srp_walkscore" :
		include_once (dirname (__FILE__) . '/walkscore.php');		
		srp_Walkscore_options();
		break;
	
	case "srp_gmap" :
		include_once (dirname (__FILE__) . '/srp_gmap.php');		
		srp_gmap_options();
		break;
		
	case "srp_ext_gre" :
		include_once (dirname (__FILE__) . '/srp_ext_gre.php');		
		srp_ext_gre_options();
		break;
		
	default :
			include_once (dirname (__FILE__) . '/main.php');
			//srp_MainAdmin_page();
			break;
		
	}
}


function simpleRealEstatePack_options() {
	$default_options = _default_settings_SRP();
	foreach($default_options as $name=>$value){
		if(!get_option($name)){
			add_option($name, $value);
		}
	}
	      	 
 echo '<div class="wrap srp">';
  echo '<h2>Mortgage Calculators</h2>';
  srp_updated_message();
  ?>
<div class="postbox-container" style="width:70%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">  
  <form method="post" action="options.php">
  <?php wp_nonce_field('update-options'); ?>
  <div class="postbox">
	<div class="handlediv" title="Click to toggle"><br /></div>
		<h3 class="hndle"><span>Mortgage Calc Estimate Default Values</span></h3>
			<div class="inside">
  <table class="form-table">
	<tr valign="bottom">
	  <th scope="row"><div align="right">Annual Interest Rate: </div></th>
	  <td><input name="srp_annual_interest_rate" type="text" value="<?php echo get_option('srp_annual_interest_rate');?>" size="10" />
	    %</td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Mortgage Term: </div></th>
	  <td><input name="srp_mortgage_term" type="text" value="<?php echo get_option('srp_mortgage_term');?>" size="10" />
	    years</td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Property Tax Rate: </div></th>
	  <td><input name="srp_property_tax_rate" type="text" value="<?php echo get_option('srp_property_tax_rate');?>" size="10" />
	    %</td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Home Insurance Rate: </div></th>
	  <td><input name="srp_home_insurance_rate" type="text" value="<?php echo get_option('srp_home_insurance_rate');?>" size="10" />
      %</td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Premium Mortgage Insurance (PMI): </div></th>
	  <td><input name="srp_pmi" type="text" value="<?php echo get_option('srp_pmi');?>" size="10" />
      %</td>
    </tr>
  </table>
  	</div>
  </div>
  <div class="postbox">
	<div class="handlediv" title="Click to toggle"><br /></div>
		<h3 class="hndle"><span>Closing Cost Estimate Default Values</span></h3>
			<div class="inside">
  <table class="form-table">
	<tr valign="bottom">
	  <th scope="row"><div align="right">Origination Fee: </div></th>
	  <td><input name="srp_origination_fee" type="text" value="<?php echo get_option('srp_origination_fee');?>" size="10" />
	    %</td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Lender Fees (processing/underwriting): </div></th>
	  <td><input name="srp_lender_fees" type="text" value="<?php echo get_option('srp_lender_fees');?>" size="10" />
	    </td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Credit Report Fee: </div></th>
	  <td><input name="srp_credit_report_fee" type="text" value="<?php echo get_option('srp_credit_report_fee');?>" size="10" />
      </td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Appraisal: </div></th>
	  <td><input name="srp_appraisal" type="text" value="<?php echo get_option('srp_appraisal');?>" size="10" />
      </td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Title Insurance: </div></th>
	  <td><input name="srp_title_insurance" type="text" value="<?php echo get_option('srp_title_insurance');?>" size="10" />
      </td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Reconveyance Fee: </div></th>
	  <td><input name="srp_reconveyance_fee" type="text" value="<?php echo get_option('srp_reconveyance_fee');?>" size="10" />
      </td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Recording Fee: </div></th>
	  <td><input name="srp_recording_fee" type="text" value="<?php echo get_option('srp_recording_fee');?>" size="10" />
      </td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Wire and Courier Fees: </div></th>
	  <td><input name="srp_wire_courier_fee" type="text" value="<?php echo get_option('srp_wire_courier_fee');?>" size="10" />
      </td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Endorsement Fee: </div></th>
	  <td><input name="srp_endorsement_fee" type="text" value="<?php echo get_option('srp_endorsement_fee');?>" size="10" />
      </td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Title Closing Fee: </div></th>
	  <td><input name="srp_title_closing_fee" type="text" value="<?php echo get_option('srp_title_closing_fee');?>" size="10" />
      </td>
    </tr>
	<tr valign="bottom">
	  <th scope="row"><div align="right">Title Document Prep Fee: </div></th>
	  <td><input name="srp_title_doc_prep_fee" type="text" value="<?php echo get_option('srp_title_doc_prep_fee');?>" size="10" />
      </td>
    </tr>
  </table>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="srp_annual_interest_rate,srp_property_tax_rate,srp_home_insurance_rate,srp_pmi,srp_origination_fee,srp_lender_fees,srp_credit_report_fee,srp_appraisal,srp_title_insurance,srp_reconveyance_fee,srp_recording_fee,srp_wire_courier_fee,srp_endorsement_fee,srp_title_closing_fee,srp_title_doc_prep_fee" />
	<p class="submit">
	<input name="simpleRealEstatePack_submit" type="submit" class="button-primary" value="<?php _e('Save All Changes') ?>" />
	</p>
	</form>
					</div>
				</div>
			</div>
		</div>
	</div>

  <?php  
  echo srp_settings_right_column();
  echo '</div>';  
}

//get_option substitute to use inside WP_Widget class
function srp_get_option($option, $instance = null){
	if($instance){
		return $instance;
	}else{		
		/*--BEGIN return zillow rate--*/
		if($option == 'srp_annual_interest_rate'){
			if(get_option('srp_use_rates_in_calcs') && get_option('srp_getratesummary_api_key')){
				$rate = srp_get_zillow_mortgage_rates($return_rate = true);
				if($rate){
					add_filter('widget', 'srp_mortgage_rates_branding',9);
					return $rate;
				}
			}
		}		
		/*--END return zillow rate--*/
		if($value = get_option($option)){
			return $value;
		}else{
			$default_options = _default_settings_SRP();
			if($default_options->$option){
				return $default_options->$option;
			}else{
				return;
			}
		}
	}
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
		If you would like to make a financial contribution, as a jester of of your appreciation for this free plugin, please consider a donation to the <a href="https://www.cancer.org/aspx/Donation/DON_1_Donate_Online_Now.aspx" title="Donate to American Cancer Society">American Cancer Society</a>		
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