<?php

function srp_Walkscore_options_page(){

  echo '<div class="wrap srp">';
  echo '<h2>Walkscore API by Walkscore.com</h2>';
  srp_updated_message();
  ?>
  <form method="post" action="options.php">
  <?php settings_fields('srp-walkscore-options'); ?>
  <div class="postbox-container" style="width:70%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables">
				<div class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
					<h3 class="hndle"><span>Walkscore API Options</span></h3>
					<div class="inside">	
					  <table class="form-table">
						<tr valign="bottom">
						  <th scope="row"><div align="right">Walkscore API Key: </div></th>
						  <td><input name="srp_walkscore_api_key" type="text" value="<?php echo get_option('srp_walkscore_api_key');?>" size="30" />
							<br /> 
							To obtain your free API key <a href="http://www.walkscore.com/request-tile-key.php" target="_blank">register at Walkscore.com</a>.</td>
						</tr>
					  </table>
						<p class="submit">
						<input name="srp_walkscore_submit" type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
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