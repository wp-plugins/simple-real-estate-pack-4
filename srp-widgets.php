<?php

/*---------------------------------------------*
** Mortgage Calculator Widget
**---------------------------------------------*/
class srp_MortgageCalc extends WP_Widget {

	function srp_MortgageCalc() {
		$widget_ops = array('classname' => 'srp_MortgageCalc', 'description' => __('Mortgage Calculator Widget'));
		$control_ops = array('width' => 280);
		$this->WP_Widget('srp_MortgageCalc', __('[SRP] Mortgage Calculator'), $widget_ops, $control_ops);		
	}

	function widget( $args, $instance ) {		
		extract($args);
		$title = apply_filters('srp_MortgageCalc', empty($instance['title']) ? '' : $instance['title']);
		if ( !empty( $title ) ) { $title = $before_title . $title . $after_title; } 
		$interest_rate = srp_get_option('srp_annual_interest_rate', $instance['interest_rate']);
		$mortgage_term = srp_get_option('srp_mortgage_term', $instance['mortgage_term']);
		if($instance['width']){ $width = 'style="width:'.$instance['width'].'px"'; }
		
$output = $title . '
<div class="srp_MortgageCalcwidget" ' . $width . '>
				<table class="srp_table">
				  <tr>
					<td><label>Price of Home </label></td>
					<td>$</td>
					<td><input id="' . $this->get_field_id('price_of_home') . '" class="currency" name="' . $this->get_field_name('price_of_home') . '" type="text" size="8" value="' . $instance['price_of_home'] . '"></td>
				  </tr>
				  <tr>
					<td><label>Down Payment </label></td>
					<td>&nbsp;</td>
					<td><input id="' . $this->get_field_id('down_payment') . '" name="' . $this->get_field_name('down_payment') . '" type="text" size="8" value="' . $instance['down_payment'] . '">%</td>
				  </tr>
				  <tr>
					<td><label>Mortgage Term </label></td>
					<td>&nbsp;</td>
					<td><input id="' . $this->get_field_id('mortgage_term') . '" name="' . $this->get_field_name('mortgage_term') . '" type="text" size="8" value="' . $mortgage_term . '">yrs</td>
				  </tr>
				  <tr>
					<td><label>Interest Rate </label></td>
					<td>&nbsp;</td>
					<td><input id="' . $this->get_field_id('interest_rate') . '" name="' . $this->get_field_name('interest_rate') . '" type="text" size="8" value="' . $interest_rate . '">%</td>
				  </tr>
				  <tr class="monthly_payment">
					<td><label>Monthly Payment </label></td>
					<td>$</td>
					<td><input id="' . $this->get_field_id('monthly_payment') . '" class="currency" name="' . $this->get_field_name('monthly_payment') . '" type="text" size="8" value="' . $instance['monthly_payment'] . '"></td>
				  </tr>
				  <tr>
				  	<td colspan="3">
				  		<div id="' . $this->get_field_id('result') . '" class="srp_additional-info" style="display:none">
						</div>
					</td>
				  </tr>
				  </table>
			</div>';

						$property_tax_rate = get_option('srp_property_tax_rate');
						$home_insurance_rate = get_option('srp_home_insurance_rate');
						$pmi = get_option('srp_pmi');
						
						$default_options = _default_settings_SRP();
						if(!$property_tax_rate){ $property_tax_rate = $default_options->srp_property_tax_rate; }
						if(!$home_insurance_rate){ $home_insurance_rate = $default_options->srp_home_insurance_rate; }
						if(!$pmi){ $pmi = $default_options->srp_pmi; }
						
						$output .= '<input id="property_tax_rate" name="property_tax_rate" type="hidden"  value="'.$property_tax_rate.'">';
						$output .= '<input id="home_insurance_rate" name="home_insurance_rate" type="hidden"  value="'.$home_insurance_rate.'">';
						$output .= '<input id="pmi" name="pmi" type="hidden"  value="'.$pmi.'">' . $after_widget;
		
		if($instance['return'] == true){
			return $output;
		}else{
			echo $output;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;		
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['price_of_home'] 		= $new_instance['price_of_home'];
		$instance['down_payment'] 		= $new_instance['down_payment'];
		$instance['mortgage_term'] 		= $new_instance['mortgage_term'];
		$instance['interest_rate'] 		= $new_instance['interest_rate'];
		$instance['monthly_payment'] 	= $new_instance['monthly_payment'];
		$instance['extended']			= $new_instance['extended'];
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Mortgage Calculator') );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

<?php
	}
}


/*---------------------------------------------*
** Affordability Calculator Widget
**---------------------------------------------*/
class srp_AffordabilityCalc extends WP_Widget {

	function srp_AffordabilityCalc() {
		$widget_ops = array('classname' => 'srp_AffordabilityCalc', 'description' => __('Affordability Calculator Widget'));
		$control_ops = array('width' => 280);
		$this->WP_Widget('srp_AffordabilityCalc', __('[SRP] Affordability Calculator'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('srp_AffordabilityCalc', empty($instance['title']) ? '' : $instance['title']);
		if ( !empty( $title ) ) { $title = $before_title . $title . $after_title; } 
		if($instance['width']){ $width = 'style="width:'.$instance['width'].'px"'; }
		$interest_rate = srp_get_option('srp_annual_interest_rate', $instance['interest_rate']);
		$pmi = srp_get_option('srp_pmi');
		$output = $title . '
<div class="srp_AffordabilityCalcwidget" '.$width.'>
				<table class="srp_table">
				  <tr>
					<td>
				    Monthly Gross Income </td>
					<td>$</td>
					<td><input id="' . $this->get_field_id('mo_gross_income') . '" name="' . $this->get_field_name('mo_gross_income') . '" type="text" size="8"></td>
				  </tr>
				  <tr>
					<td>Monthly Debt Expences <sup><a href="#TB_inline?height=200&width=300&inlineId=' . $this->get_field_id('sac_help') . '" class="thickbox" title="What are Monthly Debt and Obligations?">[?]</a></sup>
						<div id="' . $this->get_field_id('sac_help') . '" style="display: none">
							<h3>Monthly Debt and Obligations Should Include:</h3>
							<ol>
								<li>Monthly Credit Card Payments</li>
								<li>Monthly Auto Payments</li>
								<li>Monthly Child Support</li>
								<li>Monthly Association Fees</li>
								<li>Other Monthly Obligations, but NOT utility bills.</li>
							</ol>
						</div>
					</td>
					<td>$</td>
					<td><input id="' . $this->get_field_id('mo_debt_expences') . '" name="' . $this->get_field_name('mo_debt_expences') . '" type="text" size="8"></td>
				  </tr>
				  <tr>
					<td>Down Payment:</td>
					<td>$
				    </td>
					<td><input id="' . $this->get_field_id('down_payment') . '" name="' . $this->get_field_name('down_payment') . '" type="text" size="8" value="0"></td>
				  </tr>				  
				  <tr>
					<td>Interest Rate:</td>
					<td>%</td>
					<td><input id="' . $this->get_field_id('interest_rate') . '" name="' . $this->get_field_name('interest_rate') . '2" type="text" size="8" value="' . $interest_rate . '" />
					<input id="' . $this->get_field_id('property_tax') . '" name="' . $this->get_field_name('property_tax') . '2" type="hidden" value="' . srp_get_option('srp_property_tax_rate') . '" />
					<input id="' . $this->get_field_id('home_insurance') . '" name="' . $this->get_field_name('home_insurance') . '2" type="hidden" value="' . srp_get_option('srp_home_insurance_rate') . '" />
					<input id="' . $this->get_field_id('pmi') . '" name="' . $this->get_field_name('pmi') . '2" type="hidden" value="' . $pmi . '" />
					</td>
				  </tr>
				  <tr>
					<td colspan="3">
						<div id="' . $this->get_field_id('result') . '" class="srp_additional-info" style="display: none"></div></td>
				  </tr>
				</table>

			</div>
		' . $after_widget;
		if($instance['return'] == true){
			return $output;
		}else{
			echo $output;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;		
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['mo_gross_income'] 		= $new_instance['mo_gross_income'];
		$instance['mo_debt_expences'] 		= $new_instance['mo_debt_expences'];
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Affordability Calculator' ) );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

<?php
	}
}


/*---------------------------------------------*
** Closing Cost Estimator Widget
**---------------------------------------------*/
class srp_ClosingCosts extends WP_Widget {

	function srp_ClosingCosts() {
		$widget_ops = array('classname' => 'srp_ClosingCosts', 'description' => __('Closing Cost Estimator'));
		$control_ops = array('width' => 280);
		$this->WP_Widget('srp_ClosingCosts', __('[SRP] Closing Cost Estimator'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('srp_ClosingCosts', empty($instance['title']) ? '' : $instance['title']);
		$text = apply_filters( 'srp_ClosingCosts', $instance['text'] );
		echo $before_widget;
		if ( !empty( $title ) ) { $title = $before_title . $title . $after_title; }
		if($instance['width']){ $width = 'style="width:'.$instance['width'].'px"'; }
		
		$output = $title . '
<div class="srp_ClosingCostswidget" '.$width.'>
				<table class="srp_table">
					  <tr>
						<td colspan="3"><strong>Loan Information</strong> </td>
					  </tr>
					  <tr>
						<td>Loan Amount</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('loan_amount') . '" name="' . $this->get_field_name('loan_amount') . '" value="'.$instance['loan_amount'].'" type="text" size="8"></td>
					  </tr>
					  <tr>
					  	<td colspan="3">
						<div id="' . $this->get_field_id('result') . '" class="srp_additional-info" style="display:none"></div>
						<a href="javascript:showClosingDetails(\'' . $this->get_field_id('closing_details') . '\');">View/Edit Closing Cost Details</a>
						</td>
					  </tr>
				</table>				
				<table class="srp_table" id="' . $this->get_field_id('closing_details') . '" style="display:none">
					  <tr>
						<td colspan="3"><strong>Traditional Closing Expences</strong> </td>
					  </tr>
					  <tr>
						<td>Discount Points</td>
						<td>&nbsp;</td>
						<td><input id="' . $this->get_field_id('discount_points') . '"  name="' . $this->get_field_name('discount_points') . '" type="text" size="8" value="0"></td>
					  </tr>
					  <tr>
						<td>Origination Fee</td>
						<td>%</td>
						<td><input id="' . $this->get_field_id('origination_fee') . '"  name="' . $this->get_field_name('origination_fee') . '" type="text" size="8" value="' . srp_get_option('srp_origination_fee', $instance['origination_fee']) . '"></td>
					  </tr>
					  <tr>
						<td>Lender Processing Fees</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('lender_fees') . '"  name="' . $this->get_field_name('lender_fees') . '" type="text" size="8" value="' . srp_get_option('srp_lender_fees', $instance['lender_fees']) . '"></td>
					  </tr>
					  <tr>
						<td>Credit Report</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('credit_report_fee') . '" name="' . $this->get_field_name('credit_report_fee') . '" type="text" size="8" value="' . srp_get_option('srp_credit_report_fee', $instance['credit_report_fee']) . '"></td>
					  </tr>
					  <tr>
						<td>Appraisal</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('appraisal') . '" name="' . $this->get_field_name('appraisal') . '" type="text" size="8" value="' . srp_get_option('srp_appraisal', $instance['appraisal']) . '"></td>
					  </tr>
					  <tr>
						<td>Title Insurance</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('title_insurance') . '" name="' . $this->get_field_name('title_insurance') . '" type="text" size="8" value="' . srp_get_option('srp_title_insurance', $instance['title_insurance']) . '"></td>
					  </tr>
					  <tr>
						<td>Reconveyance Fee</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('reconveyance_fee') . '" name="' . $this->get_field_name('reconveyance_fee') . '" type="text" size="8" value="' . srp_get_option('srp_reconveyance_fee', $instance['reconveyance_fee']) . '"></td>
					  </tr>
					  <tr>
						<td>Recording Fee</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('recording_fee') . '" name="' . $this->get_field_name('recording_fee') . '" type="text" size="8" value="' . srp_get_option('srp_recording_fee', $instance['recording_fee']) . '"></td>
					  </tr>
					  <tr>
						<td>Wire and Courier Fees</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('wire_courier_fee') . '" name="' . $this->get_field_name('wire_courier_fee') . '" type="text" size="8" value="' . srp_get_option('srp_wire_courier_fee', $instance['wire_courier_fee']) . '"></td>
					  </tr>
					  <tr>
						<td>Endorsement Fee</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('endorsement_fee') . '" name="' . $this->get_field_name('endorsement_fee') . '" type="text" size="8" value="' . srp_get_option('srp_endorsement_fee', $instance['endorsement_fee']) . '"></td>
					  </tr>
					  <tr>
						<td>Title Closing Fee</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('title_closing_fee') . '" name="' . $this->get_field_name('title_closing_fee') . '" type="text" size="8" value="' . srp_get_option('srp_title_closing_fee', $instance['title_closing_fee']) . '"></td>
					  </tr>
					  <tr>
						<td>Title Document Prep Fee</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('title_doc_prep_fee') . '" name="' . $this->get_field_name('title_doc_prep_fee') . '" type="text" size="8" value="' . srp_get_option('srp_title_doc_prep_fee', $instance['title_doc_prep_fee']) . '"></td>
					  </tr>
					  <tr>
						<td>Other Fees</td>
						<td>$</td>
						<td><input id="' . $this->get_field_id('other_fees') . '" name="' . $this->get_field_name('other_fees') . '" type="text" size="8" value="0"></td>
					  </tr>
			</table>
			</div>
		' . $after_widget;
		
		if($instance['return'] == true){
			return $output;
		}else{
			echo $output;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;		
		$instance['title'] 				= strip_tags($new_instance['title']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Closing Cost Estimator') );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

<?php
	}
}



/*---------------------------------------------*
** Mortgage Rates Widget (Zillow API)
**---------------------------------------------*/
class srp_MortgageRates extends WP_Widget {

	function srp_MortgageRates() {
		$widget_ops = array('classname' => 'srp_MortgageRates', 'description' => __('Mortgage Rates by Zillow'));
		$control_ops = array('width' => 280);
		$this->WP_Widget('srp_MortgageRates', __('[SRP] Mortgage Rates'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('srp_MortgageRates', empty($instance['title']) ? '' : $instance['title']);
		if ( !empty( $title ) ) { $title = $before_title . $title . $after_title; } 
		if($instance['width']){ $width = 'style="width:'.$instance['width'].'px"'; }
		$output = $title . srp_get_zillow_mortgage_rates() . $after_widget;
		if($instance['return'] == true){
			return $output;
		}else{
			echo $output;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;		
		$instance['title'] 				= strip_tags($new_instance['title']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Mortgage Rates' ) );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}

function srp_get_zillow_mortgage_rates($return_rate = false){
	$ZWSID = get_option('srp_getratesummary_api_key');
	$state = get_option('srp_getratesummary_state');
	
	$url = "http://www.zillow.com/webservice/GetRateSummary.htm?zws-id=";
	
	if($state){
		$request_url = $url.$ZWSID.'&state='.$state;
	}else{
		$request_url = $url.$ZWSID;
	}
	
	$xml = simplexml_load_file($request_url, 'SimpleXMLElement');
	//print '<pre>';
	//print_r($xml);
	//print '</pre>';
	
	if($xml->message->code != 0){
		exit($xml->message->text);
	}else{
		$loan_types = array(
			'30 Year Fixed',
			'15 Year Fixed',
			'5/1 ARM',
		);
		$srp_display_rates = get_option('srp_display_rates');
		$width = 'width="100%"';
		
		$output .= '
		<div class="simpleMortgageCalcwidget" ' . $width . '>
		<table class="srp_table" ' . $width . '>';
		  
		if($srp_display_rates == 1){
			$output .= '<tr>
							<td>&nbsp;</td>
							<td><div align="right">Today</div></td>
							<td><div align="right">Last Week</div></td>
						</tr>';
		}
		  
		foreach($loan_types as $k => $v){
			if($srp_display_rates == 0){
				if($xml->response->today->rate[$k] > $xml->response->lastWeek->rate[$k]){
					$change = ' class="srp_rte_up"';
				}else{
					$change = ' class="srp_rte_down"';
				}
			}

			$output .='
			<tr>
				<td>' . $v . '</td>
				<td class="srp_mrtg_rte"><span'. $change .'>' . number_format($xml->response->today->rate[$k], 2) . '%</span></td>';
				if($srp_display_rates == 1){
					$output .= '<td class="srp_mrtg_rte"><span'. $change .'>' . number_format($xml->response->lastWeek->rate[$k], 2) . '%</span></td>';
				}
			$output .= '</tr>';
		}
		  
		  $output .='
		  <tr>
			<td colspan="3">
				<div align="center"><a href="http://www.zillow.com/Mortgage_Rates/">
					<small><a href="http://www.zillow.com/Mortgage_Rates/">See more mortgage rates at Zillow.com</a></small><br />
					<img src="http://www.zillow.com/static/logos/zmm_logo_small.gif" width="145" height="15" alt="Zillow Mortgages" /></a>				
				</div>
			</td>
		  </tr>
		</table>
		</div>';
		
		//add disclaimer to the footer
		add_filter('wp_footer', 'srp_zillow_disclaimer');
		
		if($return_rate){
			return $xml->response->today->rate[0];
		}		
		return $output;		
	}
}

function srp_zillow_disclaimer(){
	$content = '<div class="spr_disclaimer">&copy; Zillow, Inc., 2008. Use is subject to <a href="http://www.zillow.com/corp/Terms.htm">Terms of Use</a></div>';
	echo $content;
}



/*---------------------------------------------*
** Rental Rates Meter (RentoMeter API)
**---------------------------------------------*/
class srp_RentMeter extends WP_Widget {		
	
	function srp_RentMeter() {
		$widget_ops = array('classname' => 'srp_RentMeter', 'description' => __('Rental Rates Meter by Rentometer.com'));
		$control_ops = array('width' => 280);
		$this->WP_Widget('srp_RentMeter', __('[SRP] Rental Rates Meter'), $widget_ops, $control_ops);		
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('srp_RentMeter', empty($instance['title']) ? '' : $instance['title']);
		if ( !empty( $title ) ) { $title = $before_title . $title . $after_title; } 
		if($instance['width']){ $width = 'style="width:'.$instance['width'].'px"'; }
		
		$output = $title . '<table class="srp_table">
			  <tr>
				<td>Zipcode</td>
				<td>&nbsp;</td>
				<td><input id="' . $this->get_field_id('citystatezip') . '" name="' . $this->get_field_name('citystatezip') . '" type="text" size="8" value="'.$instance['citystatezip'].'"></td>
			  </tr>
			  <tr>
				<td>Bedrooms</td>
				<td>&nbsp;</td>
				<td><input id="' . $this->get_field_id('beds') . '" name="' . $this->get_field_name('beds') . '" type="text" size="8" value="'.$instance['beds'].'"></td>
			  </tr>
			  <tr>
				<td>Rent Amount </td>
				<td>$</td>
				<td><input id="' . $this->get_field_id('rent') . '" name="' . $this->get_field_name('rent') . '" type="text" size="8" value="'.$instance['rent'].'"></td>
			  </tr>
			</table>'
			 . '<div id="' . $this->get_field_id('result') . '" class="srp_additional-info" style="display: none"></div>'
			 . '<div align="center"><a href="http://www.rentometer.com"><img src="' . WP_PLUGIN_URL . '/simple-real-estate-pack/branding/rentometer_logo_api-med.gif" width="145" height="50" /></a></div>'
			 . $after_widget;
			 //add disclaimer to the footer
			 add_filter('wp_footer', 'srp_rentometer_disclaimer');			 			 
			 $_SESSION['srp_rentometer_api_key'] = get_option('srp_rentometer_api_key');
			 if(!$_SESSION['srp_rentometer_api_key']){ return; }
		if($instance['return'] == true){
			return $output;
		}else{
			echo $output;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;		
		$instance['title'] 				= strip_tags($new_instance['title']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Rental Rates Meter' ) );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}

function srp_rentometer_disclaimer(){
	$content = '<div class="spr_disclaimer">&copy; Rentometer, 2007. Use is subject to <a href="http://www.rentometer.com/terms">Terms of Use</a></div>';
	echo $content;
}



/*
** Init all widgets
*/

add_action('widgets_init', create_function('', 'return register_widget("srp_MortgageCalc");'));
add_action('widgets_init', create_function('', 'return register_widget("srp_AffordabilityCalc");'));
add_action('widgets_init', create_function('', 'return register_widget("srp_ClosingCosts");'));
if(get_option('srp_getratesummary_api_key')){
	add_action('widgets_init', create_function('', 'return register_widget("srp_MortgageRates");'));
}
if(get_option('srp_rentometer_api_key')){
	add_action('widgets_init', create_function('', 'return register_widget("srp_RentMeter");'));
}
?>