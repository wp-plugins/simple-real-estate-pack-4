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
function srp_profile_options_page(){
    global $srp_widgets;
    srp_prepare_widgets_object();

    foreach($srp_widgets->widgets as $widget){
        $_default_options['tabs'][$widget->name]= array(
            'name'		=> ucwords(str_replace('_', ' ', $widget->name)) . ' Section',
            'tabname'	=> ucwords(str_replace('_', '', $widget->name)),
            'heading'	=> $widget->title,
        );
        $_default_options['content'][$widget->name]= array(
            'name'	=> ucwords(str_replace('_', ' ', $widget->name)),
            'notes'	=> null,
            'value'	=> 1,
        );
    }    
    
    $default_options = $_default_options;

    /* ToDo Max: Predefine tabs for the GRE options as well.
     */
	$preset_options = array(            
		'tabs'		=> array(			
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
				'notes'	=> null,
				'value'	=> 0,
			),
			'altos_stats'		=> array(
				'name'	=> 'Altos Stats',
				'notes'	=> null,
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

        if(function_exists('greatrealestate_init')){
            $gre_preset_options = array(
		'tabs'		=> array(
			'description'	=> array(
				'name'		=> 'Property Description (GRE)',
				'tabname'	=> 'Description',
				'heading'	=> 'Property Description',
			),
			'photos'		=> array(
				'name'		=> 'Property Photos (GRE)',
				'tabname'	=> 'Photos',
				'heading'	=> 'Property Photos',
			),
			'video'		=> array(
				'name'		=> 'Video (GRE)',
				'tabname'	=> 'Video',
				'heading'	=> 'Property Video',
			),
			'panorama'		=> array(
				'name'		=> 'Panorama (GRE)',
				'tabname'	=> 'Panorama',
				'heading'	=> 'Panorama',
			),
			'downloads'		=> array(
				'name'		=> 'Downloads (GRE)',
				'tabname'	=> 'Downloads',
				'heading'	=> 'Downloads',
			),
                         'community'		=> array(
				'name'		=> 'Community (GRE)',
				'tabname'	=> 'Community',
				'heading'	=> 'Community',
			),
		),
		'content'	=> array(
                        'description'	=> array(
				'name'		=> 'Property Description (GRE)',
				'notes'	=> 'Only applies to property listing pages for Great Real Estate plugin.',
				'value'	=> 1,
			),
			'photos'		=> array(
				'name'		=> 'Property Photos (GRE)',
				'notes'	=> 'Only applies to property listing pages for Great Real Estate plugin.',
				'value'	=> 1,
			),
			'video'		=> array(
				'name'		=> 'Video (GRE)',
				'notes'	=> 'Only applies to property listing pages for Great Real Estate plugin.',
				'value'	=> 1,
			),
			'panorama'		=> array(
				'name'		=> 'Panorama (GRE)',
				'notes'	=> 'Only applies to property listing pages for Great Real Estate plugin.',
				'value'	=> 1,
			),
			'downloads'		=> array(
				'name'		=> 'Downloads (GRE)',
				'notes'	=> 'Only applies to property listing pages for Great Real Estate plugin.',
				'value'	=> 1,
			),
                         'community'		=> array(
				'name'		=> 'Community (GRE)',
				'notes'	=> 'Only applies to property listing pages for Great Real Estate plugin.',
				'value'	=> 1,
			),
     		),
	  );
            foreach($gre_preset_options['tabs'] as $k => $tab){
                $preset_options['tabs'][$k] = $tab;
            }
            foreach($gre_preset_options['content'] as $k => $v){
                $preset_options['content'][$k] = $v;
            }
        }
        foreach($preset_options['tabs'] as $k => $tab){
            $default_options['tabs'][$k] = $tab;
        }
 
        foreach($preset_options['content'] as $k => $v){
            $default_options['content'][$k] = $v;
        }

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
  echo '<h2>Neighborhood Profiles</h2>';
  srp_updated_message();
  ?>
  <form method="post" action="options.php">
  <?php settings_fields('srp-gre-extension-options'); ?>
  <div class="postbox-container" style="width:70%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">
				<div class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class="hndle"><span>Neighborhood Profile Options</span></h3>
					<div class="inside">
					<h4>Neighborhood Profile Widgets</h4>
                                        <?php
                                        //ToDo Max: update the line below to reflect current functinality of the plugin.
                                        ?>
					<p>The selected tools and widgets/content will appear on the listing details pages created by the Great Real Estate plugin.</p>	
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
					  <h4>Tab Titles and Content/Widget Subtitles</h4>					  
					  <table class="form-table">
					<?php 
					foreach($default_options['tabs'] as $k => $v){
					?>
						<tr valign="bottom">
						  <th scope="row"><div align="right"><?php echo $default_options['tabs'][$k]['name'];?>: </div></th>
						  	<td>
								<input type="text" name="srp_ext_gre_options[tabs][<?php echo $k;?>][tabname]" value="<?php echo $options['tabs'][$k]['tabname'];?>" size="50"/>(tab name)<br />
                                                                <?php if(!strstr($default_options['tabs'][$k]['name'], '(GRE)')) {?>
						  		<input type="text" name="srp_ext_gre_options[tabs][<?php echo $k;?>][heading]" value="<?php echo $options['tabs'][$k]['heading'];?>" size="50"/>(sub-title)
                                                                <?php } ?>
							</td>
						</tr>
					<?php
					}
					?>					
					  </table>
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