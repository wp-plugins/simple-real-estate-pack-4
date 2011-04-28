<?php
/*
Plugin Name: Advanced Text Widget
Plugin URI: 
Description: Text widget that has extensive conditional options to display content on pages, posts, specific categories etc. It supports regular HTML as well as PHP code. This widget is an extension of Daiko's Text Widget by Rune Fjellheim.
Author: Max Chirkov
Version: 2.0.0
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
        //parent::WP_Widget(false, $name = 'Simple Sidebar Navigation');	
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'advanced_text', 'description' => __('Advanced text widget. Raw PHP code support.', 'advanced_text'));

		/* Widget control settings. */
		$control_ops = array( 'width' => 400, 'height' => 450, 'id_base' => 'advanced_text' );

		/* Create the widget. */
		$this->WP_Widget( 'advanced_text', __('Advanced Text', 'advanced_text'), $widget_ops, $control_ops );
    }

	function form($instance) {
 
		$title = apply_filters('widget_title', $instance['title']);
		$allSelected = $homeSelected = $postSelected = $postInCategorySelected = $pageSelected = $categorySelected = $blogSelected = $searchSelected = false;
		switch ($instance['action']) {
			case "1":
			$showSelected = true;
			break;
			case "0":
			$dontshowSelected = true;
			break;
		}
		
	?>
				<label for="<?php echo $this->get_field_id('title'); ?>" title="Title above the widget">Title:<input style="width:400px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label> 
				<p>PHP Code (MUST be enclosed in &lt;?php and ?&gt; tags!):</p>
				<label for="<?php echo $this->get_field_id('text'); ?>" title="PHP Code (MUST be enclosed in &lt;?php and ?&gt; tags!):"><textarea id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" cols="20" rows="16" style="width:400px;"><?php echo format_to_edit($instance['text']); ?></textarea></label>
				<label for="<?php echo $this->get_field_id('action'); ?>"  title="Show only on specified page(s)/post(s)/category. Default is All" style="line-height:35px;"><select name="<?php echo $this->get_field_name('action'); ?>"><option value="1" <?php if ($showSelected){echo "selected";} ?>>Show</option><option value="0" <?php if ($dontshowSelected){echo "selected";} ?>>Do NOT show</option></select> only on: <select name="<?php echo $this->get_field_name('show'); ?>" id="<?php echo $this->get_field_id('show'); ?>">
				<?php	
				global $atw;		
				foreach($atw->options['condition'] as $k => $item){
					$output .= '<option label="' . $item['name'] . '" value="'. $k . '"' . selected($instance['show'], $k) . '>' . $item['name'] .'</option>';
				}				

				/* Make compatible with previous version
				** Check if condition keys are numeric
				** if not - they have old conditions format
				*/
				if(!is_numeric($instance['show']) && $instance['show'] != 'all'){
				?>
					<option label="All" value="all" <?php if ($allSelected){echo "selected";} ?>>All</option>
					<option label="Home" value="home" <?php if ($homeSelected){echo "selected";} ?>>Home</option>
					<option label="Post" value="post" <?php if ($postSelected){echo "selected";} ?>>Post(s)</option>
					<option label="Post in Category ID(s)" value="post_in_category" <?php if ($postInCategorySelected){echo "selected";} ?>>Post In Category ID(s)</option>
					<option label="Page" value="page" <?php if ($pageSelected){echo "selected";} ?>>Page(s)</option>
					<option label="Category" value="category" <?php if ($categorySelected){echo "selected";} ?>>Category</option>
					<option label="Blog" value="blog" <?php if ($blogSelected){echo "selected";} ?>>Blog Main Page, Posts and Archives</option>
					<option label="Search Results Page" value="search" <?php if ($searchSelected){echo "selected";} ?>>Search Results Page</option>			
				<?php
				}else{
					echo $output;
				}
				?>

				</select></label><br/> 
				<label for="<?php echo $this->get_field_id('slug'); ?>"  title="Optional limitation to specific page, post or category. Use ID, slug or title.">Slug/Title/ID: <input type="text" style="width: 250px;" id="<?php echo $this->get_field_id('slug'); ?>" name="<?php echo $this->get_field_name('slug'); ?>" value="<?php echo htmlspecialchars($instance['slug']); ?>" /></label>
				<?php if ($postInCategorySelected) echo "<p>In <strong>Post In Category</strong> add one or more cat. IDs (not Slug or Title) comma separated!</p>" ?>
				<br /><label for="<?php echo $this->get_field_id('suppress_title'); ?>"  title="Do not output widget title in the front-end."><input idx="<?php echo $this->get_field_name('suppress_title'); ?>" name="<?php echo $this->get_field_name('suppress_title'); ?>" type="checkbox" value="1" <?php checked($instance['suppress_title'],'1', true);?> /> Suppress Title Output</label>
				
	<?php
	}
	
	function update($new_instance, $old_instance) {			
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['text'] = $new_instance['text'];
		$instance['action'] = $new_instance['action'];
		$instance['show'] = $new_instance['show'];
		$instance['slug'] = $new_instance['slug'];
		$instance['suppress_title'] = $new_instance['suppress_title'];
		return $instance;
	}
	
	function widget($args, $instance) {
		extract($args);
		if(empty($instance['suppress_title'])){
			$title 	 = $instance['title'];
		}
			
		$text = apply_filters( 'widget_text', $instance['text'], $instance );
		$action  = $instance['action'];
		$show 	 = $instance['show'];
		$slug 	 = $instance['slug'];				
					
		?>
		<?php 
 /* Do the conditional tag checks. */
 		global $atw;
 		$arg = explode('|', $slug);

 		//Checking if $show in not numeric - in that case we have older version conditions
		if(!is_numeric($show)){
			$old_conditions['all']				= 'true';
			$old_conditions['home']				= 'is_home()';
			$old_conditions['post']				= 'is_sing($arg)';
			$old_conditions['post_in_category']	= 'in_category($arg)';
			$old_conditions['page']				= 'is_page($arg)';
			$old_conditions['category']			= 'is_category($arg)';
			$old_conditions['blog']				= 'is_home($slug) || is_single() || is_archive()';
			$old_conditions['search']			= 'is_search()';

			$code = $old_conditions[$show];
		}else{
 			$code = $atw->options['condition'][$show]['code'];
 		} 		
 		
 		$num = count($arg);
 		$i = 1;
 		foreach($arg as $k => $v){
 			$ids = explode(",", $v); 			
 			$str = ''; 			
 			foreach($ids as $val){
 				if($val !="")			
 					$str .= '"' . $val . '",';
 			}
 			if($str != ''){
 				$str = "array($str)";
 			}else{
 				$str = '';
 			}
 			
 			if($num > 1){
 				$code = str_replace('$arg' . $i, $str, $code);
 			}else{
 				$code = str_replace('$arg', $str, $code);
 			} 
 			$i++;			 			
 		} 				
 		 		
		if($action == "1"){									
			$code = "if($code){ return true; }else{ return false; }";							
			if(eval($code)){
					echo $before_widget;
					echo "<div class='AdvancedText'>"; 
					$title ? print($before_title . $title . $after_title) : null;
					eval('?>'.$text);
					echo "</div>"; 
					echo $after_widget."
					";		
			}
			
		}else{			
			$code = "if($code){ return false; }else{ return true; }";		
			if(eval($code)){
					echo $before_widget;
					echo "<div class='AdvancedText'>"; 
					$title ? print($before_title . $title . $after_title) : null;
					eval('?>'.$text);
					echo "</div>"; 
					echo $after_widget."
					";		
			}
		}
		?>
	<?php
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
?>