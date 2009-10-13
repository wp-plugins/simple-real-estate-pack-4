<?php

function srp_gmap_options(){

  echo '<div class="wrap srp">';
  echo '<h2>Google Maps API</h2>';
  srp_updated_message();
  ?>
  <form method="post" action="options.php">
  <?php wp_nonce_field('update-options'); ?>
  <div class="postbox-container" style="width:70%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">
				<div class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class="hndle"><span>Yelp API Options</span></h3>
					<div class="inside">	
					  <table class="form-table">
						<tr valign="bottom">
						  <th scope="row"><div align="right">Google Maps API Key: </div></th>
						  <td><input name="srp_gmap_api_key" type="text" value="<?php echo get_option('srp_gmap_api_key');?>" size="60" />
							<br /> 
							Paste your domain's <a title="get a Google API key" href="http://code.google.com/apis/maps/signup.html">Google API key</a> here to enable maps.</td>
						</tr>
					  </table>
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="page_options" value="srp_gmap_api_key" />
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