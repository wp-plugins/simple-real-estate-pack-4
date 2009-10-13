<div class="wrap srp">
	<h2>Simple Real Estate Pack</h2>
	<div class="postbox-container" style="width:70%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">
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
					<h3 class="hndle"><span>Available ShortCodes</span></h3>
					<div class="inside">											
						<?php
							global $shortcode_atts;
							foreach($shortcode_atts as $name => $options){
								$required = $options['required'];
								$optional = $options['optional'];
								$description = $options['description'];
								unset($optional['return']);
								if($name == 'schoolsearch') { $title = 'Schools'; }
								elseif($name == 'yelp') { $title = 'Yelp'; }
								elseif($name == 'srpmap') { $title = 'Google Map'; $closingtag = ' Location description. [/'.$name.']';}
								else{ $title = $optional['title']; }
								echo '<h4>' . $title . ':</h4>';
								echo '<u>Usage</u>: ['.$name.' <em>{attributes}</em>]'. $closingtag .'<br/><br/>';
								echo '<u>Attributes</u>:';
								if(!empty($required))
									echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;<em>required</em>: <code>' . implode('=" " ', array_keys($required)) . '=" " </code></p>';
								if(!empty($optional))
									echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;<em>optional</em>: <code>' . implode('=" " ', array_keys($optional)) . '=" " </p>';		
								
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