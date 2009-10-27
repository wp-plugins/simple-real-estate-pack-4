<?php
//TO DO
/**
*	Add Options to Show:
	* Mortgage/Financing:
		* Morgage Calc, Closing Cost Estimator, Affordabilitry Calc, Rental Rates Meter
	* Market Trends
	* Schools
	* Nearby Businesses
**/
function srp_ext_gre_options(){
	$default_options = array(
		'tabs'		=> array(
			'mortgage'	=> array(
				'name'		=> 'Financial Section',
				'tabname'	=> 'Financing',
				'heading'	=> 'Financial Tools',
			),
			'stats'		=> array(
				'name'		=> 'Market Stats Section',
				'tabname'	=> 'Market Trends',
				'heading'	=> 'Market Statistics and Trends',
			),
			'schools'		=> array(
				'name'		=> 'Schools Section',
				'tabname'	=> 'Schools',
				'heading'	=> 'Local Schools',
			),
			'yelp'		=> array(
				'name'		=> 'Businesses/Yelp Section',
				'tabname'	=> 'Nearby Businesses',
				'heading'	=> 'Businesses in the Neighborhood',
			),
			'walkscore'		=> array(
				'name'		=> 'Walkscore Section',
				'tabname'	=> 'Walkability',
				'heading'	=> 'Walkability of the Neighborhood',
			),
		),
		'content'	=> array(
			'mortgage_calc'		=> array(
				'name'	=> 'Mortgage Calculator',
				'notes'	=> null,
				'value'	=> 1,
			),
			'closing_estimator'		=> array(
				'name'	=> 'Closing Cost Estimator',
				'notes'	=> null,
				'value'	=> 1,
			),
			'affordability_calc'		=> array(
				'name'	=> 'Affordabilitry Calculator',
				'notes'	=> null,
				'value'	=> 0,
			),
			'rental_meter'				=> array(
				'name'	=> 'Rental Rates Meter',
				'notes'	=> null,
				'value'	=> 0,
			),
			'trulia_stats'		=> array(
				'name'	=> 'Trulia Stats',
				'notes'	=> 'Select only 1 source of statistics - Trulia OR Altos.',
				'value'	=> 0,
			),
			'altos_stats'		=> array(
				'name'	=> 'Altos Stats',
				'notes'	=> 'Select only 1 source of statistics - Trulia OR Altos.',
				'value'	=> 1,
			),
			'schools'		=> array(
				'name'	=> 'Local Schools',
				'notes'	=> null,
				'value'	=> 1,
			),
			'yelp'		=> array(
				'name'	=> 'Nearby Businesses',
				'notes'	=> null,
				'value'	=> 1,
			),
			'walkscore'		=> array(
				'name'	=> 'Walkscore Widget',
				'notes'	=> null,
				'value'	=> 1,
			),
		),
	  );

  if(!$options = get_option('srp_ext_gre_options')){ 
  	$options = array('content'=>array(), 'tabs'=>array());
	  foreach($default_options['content'] as $k =>$v){
	  	if($v['value'] == 1){
			$options['content'][$k] = 'on';
		}
	  }
	  foreach($default_options['tabs'] as $k =>$v){
			$options['tabs'][$k] = $v;
	  }
	  update_option('srp_ext_gre_options', $options);	  
  }
  $options = get_option('srp_ext_gre_options');
  echo '<div class="wrap srp">';
  echo '<h2>Extended GRE Options</h2>';
  srp_updated_message();
  ?>
  <form method="post" action="options.php">
  <?php wp_nonce_field('update-options'); ?>
  <div class="postbox-container" style="width:70%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">
				<div class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class="hndle"><span>Extended GRE Options</span></h3>
					<div class="inside">
					<h4>Extend Your Listing Presentation</h4>
					<p>The selected tools and content will appear on the listing details pages created by the Great Real Estate plugin.</p>	
					  <table class="form-table">
					<?php 
					foreach($default_options['content'] as $k => $option){
					?>
						<tr valign="bottom">
						  <th scope="row"><div align="right"><?php echo $default_options['content'][$k]['name'];?>: </div></th>
						  <td><input type="checkbox" name="srp_ext_gre_options[content][<?php echo $k;?>]" <?php if($options['content'][$k]){ echo 'checked'; }?>/>
							<?php echo $default_options['content'][$k]['notes'];?>
							</td>
						</tr>
					<?php
					}
					?>					
					  </table>
					  <h4>Tab Titles and Content Headers</h4>
					  <p>Tab names apply only if you're using tabbed layout in your listingpage.php template file.</p>
					  <table class="form-table">
					<?php 
					foreach($options['tabs'] as $k => $v){
					?>
						<tr valign="bottom">
						  <th scope="row"><div align="right"><?php echo $default_options['tabs'][$k]['name'];?>: </div></th>
						  	<td>
								<input type="text" name="srp_ext_gre_options[tabs][<?php echo $k;?>][tabname]" value="<?php echo $options['tabs'][$k]['tabname'];?>" size="50"/><br />
						  		<input type="text" name="srp_ext_gre_options[tabs][<?php echo $k;?>][heading]" value="<?php echo $options['tabs'][$k]['heading'];?>" size="50"/>
							</td>
						</tr>
					<?php
					}
					?>					
					  </table>
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="page_options" value="srp_ext_gre_options" />
						<p class="submit">
						<input name="srp_gmap_submit" type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
						</p>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<?php
		echo srp_settings_right_column();
	?>	
</form>

  <?php
  
  echo '</div>';
}
?>