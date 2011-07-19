<?php
function srp_gmap_options(){

  if(function_exists('greatrealestate_init') && $g_api = get_option('greatrealestate_googleAPIkey')){
	$disabled = "disabled";
	$note = 'Currently the <a href="' . ADMIN_URL . '/admin.php?page=greatrealestate-options">Great Real Estate plugin\'s</a> Google API key is being used. If you disable that plugin, you will need to re-enter the API key here.';
  }else{
	$g_api = get_option('srp_gmap_api_key');
	$note = 'Paste your domain\'s <a title="get a Google API key" href="http://code.google.com/apis/maps/signup.html">Google API key</a> here to enable maps.';
  }

  echo '<div class="wrap srp">';
  echo '<h2>Google Maps</h2>';
  srp_updated_message();
  ?>
  <form method="post" action="options.php">
  <?php wp_nonce_field('update-options'); ?>
  <div class="postbox-container" style="width:70%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">
				<div class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class="hndle"><span>Google Maps Options</span></h3>
					<div class="inside">	
					  <table class="form-table">
<!--
                                              <tr valign="bottom">
						  <th scope="row"><div align="right">Google Maps API Key: </div></th>
						  <td><input name="srp_gmap_api_key" type="text" value="<?php echo $g_api;?>" size="60" <?php echo $disabled;?>/>
							<br /> <?php echo $note;?>
							</td>
						</tr>
API key is no longer required in API version 3
-->
						<tr valign="bottom">
						  <th scope="row"><div align="right">Mapping options from Yelp: </div></th>
						  <td><input type="checkbox" name="srp_gmap_yelp" <?php if(get_option('srp_gmap_yelp')){ echo 'checked'; }?>/>
							 <a href="<?php echo ADMIN_URL;?>/admin.php?page=srp_yelp">Yelp API key</a> is required.
                                                         <br/>A box with options like Schools, Grocery Stores, Hospitals etc. will be added to your Google Maps.
							</td>
						</tr>
<!-- Google search is not supported yet in API v3
						<tr valign="bottom">
						  <th scope="row"><div align="right">Google Map Search: </div></th>
						  <td>
                                                        <input type="checkbox" name="srp_gmap_search" <?php if(get_option('srp_gmap_search')){ echo 'checked'; }?>/>
                                                  </td>
                                                </tr>
-->
					  </table>
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="page_options" value="srp_gmap_api_key,srp_gmap_yelp,srp_gmap_search" />
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