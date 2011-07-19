<?php

function srp_ContactInfo_options() {
	
	//Default options
	$opt = array(
		'srp_quickinfo_biz_name'			=> '',
		'srp_quickinfo_rep_name'			=> '',
		'srp_quickinfo_title'				=> '',
		'srp_quickinfo_credentials'			=> '',
		'srp_quickinfo_toll_free'			=> '',
		'srp_quickinfo_office_phone'		=> '',
		'srp_quickinfo_mob_phone'			=> '',
		'srp_quickinfo_alt_phone_1'			=> '',
		'srp_quickinfo_alt_phone_2'			=> '',
		'srp_quickinfo_fax'					=> '',
		'srp_quickinfo_street'				=> '',
		'srp_quickinfo_city_state_zip'		=> '',
		'srp_quickinfo_email'				=> '',
		'srp_quickinfo_website'				=> '',
		'srp_quickinfo_logo_url'			=> '',
		'srp_quickinfo_photo_url'			=> '',
		'srp_quickinfo_signature1'			=> '',
		'srp_quickinfo_signature2'			=> '',
		'srp_quickinfo_signature3'			=> '',
	);

	add_option('srp_quickinfo',$opt);

	echo '<div class="wrap">';
	echo '<h2>Quick Contact Info</h2>';	
	if ( isset($_POST['submit']) ) {

		check_admin_referer('srp_quickinfo-update-options');			
		
		//Check if options were posted
		foreach($opt as $option_name => $option_value){
			if(isset($_POST[$option_name])){	
				$new_opt[$option_name] = $_POST[$option_name];
			}
		}
		
		update_option('srp_quickinfo', $new_opt);
		srp_updated_message(true);
	}
	
	$opt  = get_option('srp_quickinfo');
	foreach($opt as $k => $v){
		$tmp[$k] = stripslashes($v);
	}
	$opt = $tmp;
	
	?>
	<form action="" method="post">
		<?php 
			if (function_exists('wp_nonce_field'))
				wp_nonce_field('srp_quickinfo-update-options'); ?>
		<table class="form-table">
			<tr valign="bottom">
				<th scope="row"><div align="right">Repersentative Name: </div></th>
				<td><input name="srp_quickinfo_rep_name" type="text" value="<?php echo $opt['srp_quickinfo_rep_name'];?>" size="30" /></td>
			</tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Business Name: </div></th>
			  <td><input name="srp_quickinfo_biz_name" type="text" value="<?php echo $opt['srp_quickinfo_biz_name'];?>" size="30" /></td>
		  </tr>
		  <tr valign="bottom">
			  <th scope="row"><div align="right">Professional Title: </div></th>
			  <td><input name="srp_quickinfo_title" type="text" value="<?php echo $opt['srp_quickinfo_title'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Credentials:</div></th>
			  <td><input name="srp_quickinfo_credentials" type="text" value="<?php echo $opt['srp_quickinfo_credentials'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Toll Free: </div></th>
			  <td><input name="srp_quickinfo_toll_free" type="text" value="<?php echo $opt['srp_quickinfo_toll_free'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Office Phone:</div></th>
			  <td><input name="srp_quickinfo_office_phone" type="text" value="<?php echo $opt['srp_quickinfo_office_phone'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Mobile Phone: </div></th>
			  <td><input name="srp_quickinfo_mob_phone" type="text" value="<?php echo $opt['srp_quickinfo_mob_phone'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Alternative Phone 1: </div></th>
			  <td><input name="srp_quickinfo_alt_phone_1" type="text" value="<?php echo $opt['srp_quickinfo_alt_phone_1'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Alternative Phone 2: </div></th>
			  <td><input name="srp_quickinfo_alt_phone_2" type="text" value="<?php echo $opt['srp_quickinfo_alt_phone_2'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Fax:</div></th>
			  <td><input name="srp_quickinfo_fax" type="text" value="<?php echo $opt['srp_quickinfo_fax'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Street Address:</div></th>
			  <td><input name="srp_quickinfo_street" type="text" value="<?php echo $opt['srp_quickinfo_street'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">City, State, Zip:</div></th>
			  <td><input name="srp_quickinfo_city_state_zip" type="text" value="<?php echo $opt['srp_quickinfo_city_state_zip'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Email:</div></th>
			  <td><input name="srp_quickinfo_email" type="text" value="<?php echo $opt['srp_quickinfo_email'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">WebSite:</div></th>
			  <td><input name="srp_quickinfo_website" type="text" value="<?php echo $opt['srp_quickinfo_website'];?>" size="30" /></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Business Logo URL: 
			  	<?php
					if(!empty($opt['srp_quickinfo_logo_url'])){
						echo '<br /><small><a href="'.$opt['srp_quickinfo_logo_url'].'" class="thickbox" rel="srp-images">Preview Logo</a></small>';
					}
				?>
			  </div></th>
			  <td><input name="srp_quickinfo_logo_url" type="text" value="<?php echo $opt['srp_quickinfo_logo_url'];?>" size="50" />
		      	<a href="<?php echo get_bloginfo( 'url' ) . '/wp-admin/media-upload.php'; ?>?post_id=0&TB_iframe=true&width=640&height=537" class="thickbox" title="Upload Logo/Get Logo URL">Upload Logo/Get Logo URL</a>
			  </td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Personal Photo URL: 
			  	<?php
					if(!empty($opt['srp_quickinfo_photo_url'])){
						echo '<br /><small><a href="'.$opt['srp_quickinfo_photo_url'].'" class="thickbox" rel="srp-images">Preview Photo</a></small>';
					}
				?>
			  </div></th>
			  <td><input name="srp_quickinfo_photo_url" type="text" value="<?php echo $opt['srp_quickinfo_photo_url'];?>" size="50" />
		      	<a href="<?php echo get_bloginfo( 'url' ) . '/wp-admin/media-upload.php'; ?>?post_id=0&TB_iframe=true&width=640&height=537" class="thickbox" title="Upload Logo/Get Logo URL">Upload Photo/Get Photo URL</a></td>
		  </tr>
		</table>
		
		<h3>Quick Signatures</h3>
		<table class="form-table">
			<tr valign="bottom">
				<th scope="row"><div align="right">Signature 1: </div></th>
				<td><textarea name="srp_quickinfo_signature1" cols="70" rows="8"><?php echo $opt['srp_quickinfo_signature1'];?></textarea></td>
			</tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Signature 2: </div></th>
			  <td><textarea name="srp_quickinfo_signature2" cols="70" rows="8"><?php echo $opt['srp_quickinfo_signature2'];?></textarea></td>
		  </tr>
			<tr valign="bottom">
			  <th scope="row"><div align="right">Signature 3:</div></th>
			  <td><textarea name="srp_quickinfo_signature3" cols="70" rows="8"><?php echo $opt['srp_quickinfo_signature3'];?></textarea></td>
		  </tr>
		 </table>
		
		<p class="submit">
			<input name="submit" type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
  <?php
  
  echo '</div>';
}
?>