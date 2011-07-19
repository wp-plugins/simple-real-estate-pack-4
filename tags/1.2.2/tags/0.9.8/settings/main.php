<?php
function srp_general_options_page(){
	$default_options = array(
		'content'	=> array(
			'srp_gre_css'=> array(
				'name'	=> 'SRP CSS for Tabs',
				'notes'	=> 'Included styles for the SRP plugin. Leave it off if you\'re using GRE tabs or custom ones.',
				'value'	=> 1,
			),
                        'srp_profile_tabs'=> array(
				'name'	=> 'Tabbed Presentation',
				'notes'	=> 'Content will be output as tabs (CSS for Tabs options has to be activated).',
				'value'	=> 1,
			),
                        'srp_profile_ajax'=> array(
				'name'	=> 'Data via AJAX',
				'notes'	=> 'Neighborhood data will be loaded via AJAX - much faster than static output.',
				'value'	=> 1,
			),
		),
	);
		
	if(!$options = get_option('srp_general_options')){ 		
		$options = array('content'=>array());
		foreach($default_options['content'] as $k =>$v){
			if($v['value'] == 1){
				$options['content'][$k] = 'on';
			}
		}
		if(count($options < 1)){
			$options = array('content'=>array());
		}
		
		//Since we moved this option into general options section, make a smooth transition from older version.
		if($ext_gre_options = get_option('srp_ext_gre_options')){
			if($ext_gre_options['content']['srp_gre_css']){				
				$options['content']['srp_gre_css'] = $ext_gre_options['content']['srp_gre_css'];
				//removing old setting from ext_gre_options so we can have a clean record.
				unset($ext_gre_options['content']['srp_gre_css']);
				update_option('srp_ext_gre_options', $ext_gre_options);				
			}			
		}
		update_option('srp_general_options', $options);
	}  			
  
  $options = get_option('srp_general_options');
  
?>
<div class="wrap srp">
	<h2>Simple Real Estate Pack</h2>
	<?php srp_updated_message(); ?>	
	<div class="postbox-container" style="width:70%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">			
                            <form method="post" action="options.php">
                                <?php settings_fields('srp-general-options'); ?>
				<div class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class="hndle"><span>About This Plugin</span></h3>
					<div class="inside">
						<p>Simple Real Estate Pack is designed to add functionality specific to real estate industry web sites and blogs based on WordPress, and provides the following tools:</p>
						<ul>
						  <li>Mortgage Calculator</li>
						  <li>Affordability Calculator</li>
						  <li>Closing Cost Estimator</li>
						  <li>Live Mortgage Rates</li>
						  <li>Trulia Statistical Graphs </li>
						  <li>Rental Rates Meter</li>
						  <li>Schools</li>
						  <li>Mapped schools and nearby businesses via Yelp API <em>(used in conjunction with Great Real Estate plugin) </em> </li>
						</ul>						
					</div>
				</div>
				
				<div class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class="hndle"><span>General Settings</span></h3>
					<div class="inside">
						<table class="form-table">
							<?php 
							foreach($default_options['content'] as $k => $option){							
							?>
								<tr valign="bottom">
								  <th scope="row"><div align="right"><?php echo $default_options['content'][$k]['name'];?>: </div></th>
								  <td><input type="checkbox" name="srp_general_options[content][<?php echo $k;?>]" <?php if($options['content'][$k]){ echo 'checked'; }?>/>
									<?php echo $default_options['content'][$k]['notes'];?>
									</td>
								</tr>
							<?php
							}
							?>					
						</table>                                                
						<p class="submit">
						<input name="srp_general_submit" type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />						
					</div>
				</div>
				
				</form>
				
				<div class="postbox closed">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class="hndle"><span>Available ShortCodes</span></h3>
					<div class="inside">											
						<?php
							global $shortcode_atts;
							foreach($shortcode_atts as $name => $options){
								$required = $options['required'];
								$optional = $options['optional'];
								$description = $options['description'];
								unset($optional['return']);
                                                                unset($closingtag);
                                                                /*
                                                                 * ToDo Max: Avoid conditional titles - incorporate them into the array of parameters for each shrotcode.
                                                                 * See file srp-shortcodes.php
                                                                 */
								if($name == 'schoolsearch') { $title = 'Schools'; }
								elseif($name == 'yelp') { $title = 'Yelp'; }
								elseif($name == 'srpmap') { $title = 'Google Map'; $closingtag = ' Location description. [/'.$name.']';}
								elseif($name == 'walkscore') { $title = 'Walkscore'; }
                                                                elseif($name == 'srp_profile') {$title = 'Neighborhood Profile';}
								else{ $title = $optional['title']; }
								echo '<h4>' . $title . ':</h4>';
								echo '<u>Usage</u>: ['.$name.' <em>{attributes}</em>]'. $closingtag .'<br/><br/>';
								echo '<u>Attributes</u>:';
								if(!empty($required))
									echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;<em>required</em>: <code>' . implode('=" " ', array_keys($required)) . '=" " </code></p>';
								if(!empty($optional))
									echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;<em>optional</em>: <code>' . implode('=" " ', array_keys($optional)) . '=" " </code></p>';
								
								if(!empty($description))
									echo $description;
							}
							echo '</p>';
							echo '<p>*<em>Rental Rates Meter requires an <a href="' . get_bloginfo(url) 
						 .'/wp-admin/admin.php?page=srp_rentmeter" target="_blank">API Key</a>.</em></p>';
							echo '<p><em>NOTE: If you selected to <a href="' . get_bloginfo(url) 
						 .'/wp-admin/admin.php?page=srp_mortgage_rates" target="_blank">use live rates in calculators</a> via Mortgage Rates options, the interest_rate attribute will be ignored.</em></p>';
						?>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<?php
		echo srp_settings_right_column();
	?>
</div>
<?php
}
?>