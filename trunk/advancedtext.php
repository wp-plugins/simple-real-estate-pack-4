<?php
/*
Plugin Name: Advanced Text Widget
Plugin URI: 
Description: Text widget that has extensive conditional options to display content on pages, posts, specific categories etc. It supports regular HTML as well as PHP code. This widget is an extension of Daiko's Text Widget by Rune Fjellheim.
Author: Max Chirkov
Version: 3.0.0
Author URI: http://www.ibsteam.net
*/
          
define("ATW_BASENAME", plugin_basename(dirname(__FILE__)));
define("ATW_DIR", WP_PLUGIN_DIR . '/' . ATW_BASENAME);
define("ATW_URL", WP_PLUGIN_URL . '/' . ATW_BASENAME);
define("ATW_LIB", ATW_DIR . '/lib');

include 'options/options.php';
$atw_opt = get_option('atw');


		  
class advanced_text extends WP_Widget {

	function advanced_text() {        
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'advanced_text', 'description' => __('Advanced text widget. Raw PHP code support.', 'advanced_text'));

		/* Widget control settings. */
		$control_ops = array( 'width' => 400, 'height' => 450, 'id_base' => 'advanced_text' );

		/* Create the widget. */
		$this->WP_Widget( 'advanced_text', __('Advanced Text', 'advanced_text'), $widget_ops, $control_ops );
    }

	function form($instance) {
 
		$title = apply_filters('widget_title', $instance['title']);		
		
	?>
				<label for="<?php echo $this->get_field_id('title'); ?>" title="Title above the widget">Title:<input style="width:400px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label> 
				<p>PHP Code (MUST be enclosed in &lt;?php and ?&gt; tags!):</p>
				<label for="<?php echo $this->get_field_id('text'); ?>" title="PHP Code (MUST be enclosed in &lt;?php and ?&gt; tags!):"><textarea id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" cols="20" rows="16" style="width:400px;"><?php echo format_to_edit($instance['text']); ?></textarea></label>										
				
	<?php
	}
	
	function update($new_instance, $old_instance) {			
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['text'] = $new_instance['text'];		
		return $instance;
	}
	
	function widget($args, $instance) {
		extract($args);

		$title 	 = $instance['title'];			
		$text = apply_filters( 'widget_text', $instance['text'], $instance );
		
		echo $before_widget;
		echo "<div class='AdvancedText'>"; 
		$title ? print($before_title . $title . $after_title) : null;
		eval('?>'.$text);
		echo "</div>"; 
		echo $after_widget."\n";		
			
	}
		
}

function advanced_text_do_shortcode(){
	if (!is_admin()){
		add_filter('widget_text', 'do_shortcode', SHORTCODE_PRIORITY);
		add_filter('widget_advanced_text', 'do_shortcode', SHORTCODE_PRIORITY);
	}
}
	
// Tell Dynamic Sidebar about our new widget and its control
add_action('widgets_init', create_function('', 'return register_widget("advanced_text");'));
add_action('widgets_init', 'advanced_text_do_shortcode');

function atw_admin_scripts(){
    if (isset($_GET['page']) && $_GET['page'] == 'atw'){
		wp_enqueue_script('postbox');
		wp_enqueue_script('dashboard');
		wp_enqueue_style('dashboard');
		wp_enqueue_style('global');
		wp_enqueue_style('wp-admin');
		wp_register_script( 'atw', ATW_URL . '/js/scripts.js');
		wp_enqueue_script( 'atw' );
	}
}
add_action('init', 'atw_admin_scripts');

function atw_only(){
	global $atw;
	//check if conditions should apply to ATW only
	if( isset($atw->options['misc']['atw-only']) ){
		return true;
	}
	return false;
}

add_action('in_widget_form', 'atw_condition_fields');
//this action remves widget form the global $sidevar_widgets if visibility is set to false
add_filter('sidebars_widgets', 'awc_remove_hidden_widgets');
//this acction applies formating to the widget output if any (example: title suppression)
add_filter('widget_display_callback', 'atw_check_widget_visibility', 10, 3);

function atw_condition_fields($widget){
	global $atw;

	//check if conditions should apply to ATW only
	if( atw_only() ){
		if( 'advanced_text' !== get_class($widget) )
			return;
	}

	$widget_settings = get_option($widget->option_name);
	$instance = $widget_settings[$widget->number];

	$allSelected = $homeSelected = $postSelected = $postInCategorySelected = $pageSelected = $categorySelected = $blogSelected = $searchSelected = false;
	switch ($instance[$atw->prefix . 'action']) {
		case "1":
		$showSelected = true;
		break;
		case "0":
		$dontshowSelected = true;
		break;
	}
				
	?>				
	<label for="<?php echo $widget->get_field_id($atw->prefix . 'action'); ?>"  title="Show only on specified page(s)/post(s)/category. Default is All" style="line-height:35px;">		
		<select name="<?php echo $widget->get_field_name($atw->prefix . 'action'); ?>">
			<option value="1" <?php if ($showSelected){echo "selected";} ?>>Show</option>
			<option value="0" <?php if ($dontshowSelected){echo "selected";} ?>>Do NOT show</option>
		</select> only on: 
		<select name="<?php echo $widget->get_field_name($atw->prefix . 'show'); ?>" id="<?php echo $widget->get_field_id($atw->prefix . 'show'); ?>">
		
			<?php						
			foreach($atw->options['condition'] as $k => $item){
				echo '<option label="' . $item['name'] . '" value="'. $k . '"' . selected($instance[$atw->prefix . 'show'], $k) . '>' . $item['name'] .'</option>';
			}				

			
			?>

		</select>
	</label>
	<br/> 
	<label for="<?php echo $widget->get_field_id($atw->prefix . 'slug'); ?>"  title="Optional limitation to specific page, post or category. Use ID, slug or title.">Slug/Title/ID: 
		<input type="text" style="width: 99%;" id="<?php echo $widget->get_field_id($atw->prefix . 'slug'); ?>" name="<?php echo $widget->get_field_name($atw->prefix . 'slug'); ?>" value="<?php echo htmlspecialchars($instance[$atw->prefix . 'slug']); ?>" />
	</label>
	<?php 
	if ($postInCategorySelected) echo "<p>In <strong>Post In Category</strong> add one or more cat. IDs (not Slug or Title) comma separated!</p>" 
	?>
	<br />
	<label for="<?php echo $widget->get_field_id($atw->prefix . 'suppress_title'); ?>"  title="Do not output widget title in the front-end.">
		<input idx="<?php echo $widget->get_field_name($atw->prefix . 'suppress_title'); ?>" name="<?php echo $widget->get_field_name($atw->prefix . 'suppress_title'); ?>" type="checkbox" value="1" <?php checked($instance[$atw->prefix . 'suppress_title'],'1', true);?> /> Suppress Title Output
	</label>
	
<?php
	$return = null;
}

add_filter('widget_update_callback', 'atw_update_callback', 1, 4);
function atw_update_callback($instance, $new_instance, $old_instance, $this){	
	global $atw;
	
	$instance[$atw->prefix . 'action'] = $new_instance[$atw->prefix . 'action'];
	$instance[$atw->prefix . 'show'] = $new_instance[$atw->prefix . 'show'];
	$instance[$atw->prefix . 'slug'] = $new_instance[$atw->prefix . 'slug'];
	$instance[$atw->prefix . 'suppress_title'] = $new_instance[$atw->prefix . 'suppress_title'];
	return $instance;
}


function atw_check_widget_visibility($instance, $widget_obj = null, $args = false){
	global $atw, $post;
		
		if( false !== $widget_obj){
			//check if conditions should apply to ATW only
			if( atw_only() ){
				if( 'advanced_text' !== get_class($widget_obj) )
					return $instance;
			}
		}
			
		if(false != $instance[$atw->prefix . 'suppress_title']){
			unset($instance['title']);
		}
					
		$action  = $instance[$atw->prefix . 'action'];
		$show 	 = $instance[$atw->prefix . 'show'];
		$slug 	 = $instance[$atw->prefix . 'slug'];				
							
		/* Do the conditional tag checks. */
 		$arg = explode('|', $slug);
 		
 		$code = $atw->options['condition'][$show]['code'];				
 		
 		$num = count($arg); 		
 		$i = 1;
 		
		foreach($arg as $k => $v){ 			
			$ids = explode(",", $v); 			
			$str = '';
			$values = array();
			
			//wrap each value into quotation marks
			foreach($ids as $val){
				if($val !="")			
					$values[] = '"' . $val . '"';
			} 			 			
			
			
			$str = ( 1 == count($values) ) ? $values[0] : "array(" . implode(',', $values) . ")";	
			

			//if multiple values, then put them into an array			
			if( 1 < $num ){			
				$code = str_replace('$arg' . $i, $str, $code);
			}else{ 	 							
				$code = str_replace('$arg', $str, $code);
			} 
			$i++;			 			
		}
 		
	 		 				
 		 		
		if($code != false && $action == "1"){									
			$code = "if($code){ return true; }else{ return false; }";						
			if(eval($code)){
				return $instance;
			}			
		}elseif($code != false){			
			$code = "if($code){ return false; }else{ return true; }";				
			if(eval($code)){
				return $instance;
			}
		}
	return false;
	
}

function awc_remove_hidden_widgets($sidebar_widgets){
	global $wp_registered_widgets;

	//don't apply conditions in the admin dashboard
	if(is_admin() || empty($sidebar_widgets))
		return $sidebar_widgets;
		
	//loop through each sidebar
	foreach($sidebar_widgets as $sidebar => $widgets){		
		//loop through each registered widget
		foreach ($widgets as $widget_id) {			

			//check if widget_id is within the sidebar
			//if( in_array($widget_id, $widgets) ) {
				//grab widget object
				$widget = $wp_registered_widgets[$widget_id]['callback'][0];

				//check if conditions should apply to ATW only
				if( atw_only() ){						
					if( 'advanced_text' !== get_class($widget) ){
						continue;
					}						
				}
								
				//get widget settings
				$widget_settings = get_option($widget->option_name);
				//get instance of this particular widget
				$instance = $widget_settings[$widget->number];
				//check visibility
				$show = atw_check_widget_visibility($instance);
				//if not show - unset widget from the sidebar
				if (!$show) { 
					$key = array_search($widget_id, $widgets);
					unset($sidebar_widgets[$sidebar][$key]);
				}
			//}											
			
		}
	}	
	return $sidebar_widgets;
}


?>